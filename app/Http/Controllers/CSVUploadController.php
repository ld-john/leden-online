<?php

namespace App\Http\Controllers;

use App\Company;
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
        return view('upload.csv-upload');
    }

    public function showRingFenceUpload()
    {
        return view('upload.rf-upload', ['brokers' => Company::orderBy('company_name', 'asc')->where('company_type', 'broker')->get()]);
    }

    public function executeRfUpload(Request $request)
    {
        $file = $request->file('file');

        $request->validate([
            'file' => 'required|max:10240',
            'broker' => 'required'
        ]);

        $broker = $request['broker'];

        $vehicle_uploads = $this->csvToArray($file);

        foreach ($vehicle_uploads as $vehicle_upload) {
            $upload_manufacturer = Manufacturer::where('name', $vehicle_upload['make'])->firstOrCreate();

            switch($vehicle_upload['status']) {
                case ('In Stock'):
                    $upload_status = 1;
                    break;
                case('Ready for Delivery'):
                    $upload_status = 3;
                    break;
                case('UK VHC'):
                    $upload_status = 11;
                    break;
                case('Delivery Booked') :
                    $upload_status = 6;
                    break;
                case('Completed Orders'):
                    $upload_status = 7;
                    break;
                case('Europe VHC'):
                    $upload_status = 10;
                    break;
                case ('At Converter'):
                    $upload_status = 12;
                    break;
                case ('Awaiting Ship'):
                    $upload_status = 13;
                    break;
                default :
                    $upload_status = 4;
            }

            $vehicle = Vehicle::updateOrCreate(['orbit_number' => $vehicle_upload['orbit_number']],
            [
                'vehicle_status' => $upload_status,
                'ford_order_number' => $vehicle_upload['ford_order_number'],
                'make' => $upload_manufacturer->id,
                'model' => $vehicle_upload['model'],
                'derivative' => $vehicle_upload['derivative'],
                'engine' => $vehicle_upload['engine'],
                'colour' => $vehicle_upload['colour'],
                'fuel_type' => $vehicle_upload['type'],
                'chassis' => $vehicle_upload['chassis'],
                'reg' => $vehicle_upload['registration'],
                'ring_fenced_stock' => 1,
                'broker_id' => $broker
            ]);

        }
        return redirect()->route('ring_fenced_stock')->with('successMsg', 'Your vehicles have been added to the system. You can edit any extra information below.');
    }

    public function executeCsvUpload(Request $request)
    {
        $file = $request->file('file');

        $request->validate([
            'upload_type' => 'required',
            'file' => 'required|max:10240',
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
                    if ( $vehicle->vehicle_status === 6 || $vehicle->vehicle_status === 7 || $vehicle->vehicle_status === 3) {
                        continue;
                    }

                    $prefix = array_shift($ford_report);

                    switch ($ford_report['LOCATION']) {
                        case 'DELIVERED':
                            $location = 1;
                            break;
                        case 'VFS-NORTH':
                        case 'VFS-SOUTH':
                        case 'VFS-SOTON' :
                            $location = 12;
                            break;
                        case 'DBN DOCKS':
                        case 'SILV EXP':
                        case 'VAL PORT':
                        case 'VALENCIA':
                            $location = 13;
                            break;
                        case 'ANTWERP':
                        case 'AUTOPORT':
                        case 'NEW FLUSH':
                            $location = 10;
                            break;
                        case 'DAGTOPS':
                        case 'LIV DOCKS':
                        case 'LIVTOPS':
                        case 'SOTTOPS':
                            $location = 11;
                            break;
                        default:
                            $location = 4;
                    }

                    if ($ford_report['PLAN_BUILD_DATE']) {
                        $build_date = Carbon::createFromFormat('d/m/Y', $ford_report['PLAN_BUILD_DATE'])->format('Y-m-d h:i:s');
                    } else {
                        $build_date = null;
                    }

                    $vehicle->update([
                        'chassis' => $ford_report['VIN'],
                        'chassis_prefix' => $prefix,
                        'vehicle_status' => $location,
                        'build_date' => $build_date,
                    ]);

                    if($ford_report['ETA_DATE']) {
                        $order = $vehicle->order;

                        if($order) {
                            $order->update([
                                'due_date' => Carbon::createFromFormat('d/m/Y', $ford_report['ETA_DATE'])->format('Y-m-d h:i:s')
                            ]);
                        }
                    }

                }

            }
            return redirect()->route('csv_upload')->with('successMsg', 'All vehicles have been updated');
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
            while (($row = fgetcsv($handle, null, $delimiter)) !== false) {
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
