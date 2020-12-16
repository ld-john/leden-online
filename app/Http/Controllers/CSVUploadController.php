<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Notification;
use App\Notifications\notifications;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DataTables;
use App\User;
use Helper;
use Auth;
use DB;

class CSVUploadController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    public function showCsvUpload() {
        if (Helper::roleCheck(Auth::user()->id)->role != 'admin') {
            return view('unauthorised');
        }

        return view('csv-upload');
    }

    public function executeCsvUpload(Request $request) {
        $file = $request->file('file');

        $request->validate([
            'file' => 'required|max:1024'
        ]);

        $order_upload = $this->csvToArray($file);

        $brokers = User::where('role', 'broker')->get();

        $all_orders = [];

        if ($request->input('upload_type') == 'create') {

            foreach ($order_upload as $order_row) {
                $order_row['created_at'] = Carbon::now();
                $order_row['updated_at'] = Carbon::now();

                if ($request->input('show_in_ford_pipeline') == 1) {
                    $order_row['show_in_ford_pipeline'] = true;
                } else {
                    $order_row['show_in_ford_pipeline'] = false;
                }
                
                $order_id = DB::table('order')->insertGetId($order_row);

                $message = 'A new ' . $order_row['vehicle_make'] . ' ' . $order_row['vehicle_model'] . ' has been added';
                $type = 'vehicle';

                Notification::send($brokers, new notifications($message, $order_id, $type));

                $order = [
                    'order_id' => $order_id,
                    'vehicle_make' => $order_row['vehicle_make'],
                    'vehicle_model' => $order_row['vehicle_model'],
                ];

                array_push($all_orders, $order);
            }

            return view('csv-upload-complete', [
                    'all_orders' => $all_orders,
                ])->with('successMsg', 'Your orders have been added to the system. You can edit any extra information below.');
        } else {

            foreach ($order_upload as $delete_order) {
                DB::table('order')
                    ->where('chassis', $delete_order['chassis'])
                    ->where('customer_name', null)
                    ->where('company_name', null)
                    ->delete();
            }

            return redirect()->route('pipeline')->with('successMsg', 'All orders that have not been booked have been successfully deleted');
            
        }
    }

    function csvToArray($filename = '', $delimiter = ',') {
        $header = null;
        $data = [];
        if (($handle = fopen($filename, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                if (!$header) {
                    $header = $row;
                } else {
                    $data[] = array_combine($header, $row);
                }
            }
            fclose($handle);
        }

        return $data;
    }
}
