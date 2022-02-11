<?php

namespace App\Http\Controllers;

use App\Manufacturer;
use App\Vehicle;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
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
     * @return Application|Factory|View
     */

    public function showCsvUpload() {
        return view('csv-upload');
    }

    public function executeCsvUpload(Request $request)
    {
        $file = $request->file('file');

        $request->validate([
            'upload_type' => 'required',
            'file' => 'required|max:1024',
            'show_in_ford_pipeline' => 'required'
        ]);

        $vehicle_uploads = $this->csvToArray($file);


        if ($request->input('upload_type') === 'create') {
            $i = 0;
            foreach ($vehicle_uploads as $vehicle_upload) {

                $upload_manufacturer = Manufacturer::where('name', $vehicle_upload['make'])->firstOrCreate();

                $values = [];

                $values[$i]['vehicle_status'] = 1;
                $values[$i]['reg'] = $vehicle_upload['registration'];
                $values[$i]['make'] = $upload_manufacturer->id;
                $values[$i]['model'] = $vehicle_upload['model'];
                $values[$i]['derivative'] = $vehicle_upload['derivative'];
                $values[$i]['engine'] = $vehicle_upload['engine'];
                $values[$i]['transmission'] = $vehicle_upload['transmission'];
                $values[$i]['fuel_type'] = $vehicle_upload['fuel_type'];
                $values[$i]['doors'] = $vehicle_upload['doors'];
                $values[$i]['colour'] = $vehicle_upload['colour'];
                $values[$i]['trim'] = $vehicle_upload['trim'];
                $values[$i]['chassis_prefix'] = $vehicle_upload['chassis_prefix'];
                $values[$i]['chassis'] = $vehicle_upload['chassis'];
                $values[$i]['model_year'] = $vehicle_upload['model_year'];
                $values[$i]['list_price'] = $vehicle_upload['list_price'];
                $values[$i]['metallic_paint'] = $vehicle_upload['metallic_paint'];
                $values[$i]['first_reg_fee'] = $vehicle_upload['first_reg_fee'];
                $values[$i]['rfl_cost'] = $vehicle_upload['rfl_cost'];
                $values[$i]['onward_delivery'] = $vehicle_upload['onward_delivery'];
                $values[$i]['dealer_id'] = $vehicle_upload['dealer_id'];
                $values[$i]['show_in_ford_pipeline'] = $request->show_in_ford_pipeline;

                Vehicle::insert($values[$i]);

            }

            if ($request->show_in_ford_pipeline === '0') {
                return redirect()->route('pipeline')->with('successMsg', 'Your orders have been added to the system. You can edit any extra information below.');
            } else {
                return redirect()->route('pipeline.ford')->with('successMsg', 'Your orders have been added to the system. You can edit any extra information below.');
            }
        } elseif (($request->input('upload_type') === 'delete')) {
            foreach ($vehicle_uploads as $delete_order) {
                Vehicle::where('chassis', $delete_order['chassis'])
                    ->where('vehicle_status', 1)
                    ->delete();
            }

            return redirect()->route('pipeline')->with('successMsg', 'All vehicles still in stock have been deleted');

        } elseif(($request->input('upload_type') === 'ford_create')) {
            foreach( $vehicle_uploads as $ford_report) {
                $vehicle = Vehicle::where('orbit_number', '=', $ford_report['ORBITNO'])->first();

                if($vehicle) {
                    $prefix = array_shift($ford_report);

                    $vehicle->update([
                        'chassis' => $ford_report['VIN'],
                        'chassis_prefix' => $prefix
                    ]);
                    $order = $vehicle->order;

                    if($order) {
                        $order->update([
                            'due_date' => Carbon::parse($ford_report['ETA_DATE'])->format('Y-m-d h:i:s')
                        ]);
                    }

                }

            }
            return redirect()->route('pipeline')->with('successMsg', 'All vehicles have been updated');
        } else
        {
            return false;
        }
    }

    function csvToArray($filename = '', $delimiter = ','): array
    {
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
