<?php

namespace App\Http\Controllers;

use App\Http\Requests\CsvImportRequest;
use App\Imports\FitOptionImport;
use App\Models\Company;
use App\Models\CsvData;
use App\Models\FitOption;
use App\Models\Location;
use App\Models\Manufacturer;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleModel;
use App\Notifications\VehicleInStockNotification;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;

class CSVUploadController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return Application|Factory|View
     */

    public function showCsvUpload(): View|Factory|Application
    {
        return view('upload.csv-upload');
    }

    public function showRingFenceUpload(): Factory|View|Application
    {
        return view('upload.rf-upload', [
            'brokers' => Company::orderBy('company_name')
                ->where('company_type', 'broker')
                ->get(),
        ]);
    }

    public function executeRfUpload(Request $request): RedirectResponse
    {
        $file = $request->file('file');

        $request->validate([
            'file' => 'required|max:10240',
            'broker' => 'required',
        ]);

        $broker = $request['broker'];

        $vehicle_uploads = $this->csvToArray($file);

        foreach ($vehicle_uploads as $vehicle_upload) {
            $dealer = Company::where('company_name', $vehicle_upload['dealer'])
                ->where('company_type', 'dealer')
                ->first();

            $upload_manufacturer = Manufacturer::where(
                'name',
                $vehicle_upload['make'],
            )->firstOrCreate();

            $upload_status = match ($vehicle_upload['status']) {
                'In Stock' => 1,
                'Ready for Delivery' => 3,
                'UK VHC' => 11,
                'Delivery Booked' => 6,
                'Completed Orders' => 7,
                'Europe VHC' => 10,
                'At Converter' => 12,
                'Awaiting Ship' => 13,
                default => 4,
            };

            $upload[0]['orbit_number'] = $vehicle_upload['orbit_number'];

            $upload[1]['vehicle_status'] = $upload_status;
            $upload[1]['ford_order_number'] =
                $vehicle_upload['ford_order_number'];
            $upload[1]['make'] = $upload_manufacturer->id;
            $upload[1]['model'] = $vehicle_upload['model'];
            $upload[1]['derivative'] = $vehicle_upload['derivative'];
            $upload[1]['engine'] = $vehicle_upload['engine'];
            $upload[1]['colour'] = $vehicle_upload['colour'];
            $upload[1]['type'] = $vehicle_upload['type'];
            $upload[1]['chassis'] = $vehicle_upload['chassis'];
            $upload[1]['transmission'] = $vehicle_upload['transmission'];
            $upload[1]['reg'] = $vehicle_upload['registration'];
            $upload[1]['ring_fenced_stock'] = 1;
            $upload[1]['broker_id'] = $broker;
            $upload[1]['updated_at'] = Carbon::now();
            $upload[1]['created_at'] = Carbon::now();
            $upload[1]['dealer_id'] = $dealer->id;

            Vehicle::updateOrInsert($upload[0], $upload[1]);
        }
        notify()->success(
            'Your vehicles have been added to the system. You can edit any extra information below.',
            'Import Successful',
        );
        return redirect()->route('ring_fenced_stock');
    }

    public function executeCsvUpload(
        Request $request,
    ): bool|RedirectResponse|View
    {
        $file = $request->file('file');

        $request->validate([
            'upload_type' => 'required',
            'file' => 'required|max:10240',
            'show_in_ford_pipeline' => 'required',
        ]);

        $vehicle_uploads = $this->csvToArray($file);

        if ($request->input('upload_type') === 'create') {
            $i = 0;
            foreach ($vehicle_uploads as $vehicle_upload) {
                $upload_manufacturer = Manufacturer::where(
                    'name',
                    $vehicle_upload['make'],
                )->firstOrCreate();

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
                $values[$i]['chassis_prefix'] =
                    $vehicle_upload['chassis_prefix'];
                $values[$i]['chassis'] = $vehicle_upload['chassis'];
                $values[$i]['model_year'] = $vehicle_upload['model_year'];
                $values[$i]['list_price'] = $vehicle_upload['list_price'];
                $values[$i]['metallic_paint'] =
                    $vehicle_upload['metallic_paint'];
                $values[$i]['first_reg_fee'] = $vehicle_upload['first_reg_fee'];
                $values[$i]['rfl_cost'] = $vehicle_upload['rfl_cost'];
                $values[$i]['onward_delivery'] =
                    $vehicle_upload['onward_delivery'];
                $values[$i]['dealer_id'] = $vehicle_upload['dealer_id'];
                $values[$i]['show_in_ford_pipeline'] =
                    $request->show_in_ford_pipeline;

                Vehicle::insert($values[$i]);
            }
            notify()->success(
                'Your orders have been added to the system. You can edit any extra information below.',
                'Import Successful',
            );
            if ($request->show_in_ford_pipeline === '0') {
                return redirect()->route('pipeline');
            } else {
                return redirect()->route('pipeline.ford');
            }
        } elseif ($request->input('upload_type') === 'delete') {
            foreach ($vehicle_uploads as $delete_order) {
                Vehicle::where('chassis', $delete_order['chassis'])
                    ->where('vehicle_status', 1)
                    ->delete();
            }
            notify()->success(
                'All vehicles still in stock have been deleted',
                'Import Deletion successful',
            );
            return redirect()->route('pipeline');
        } elseif ($request->input('upload_type') === 'ford_create') {
            $exclude_status = [1, 3, 5, 6, 7, 14, 15];

            foreach ($vehicle_uploads as $ford_report) {

                $vehicle = Vehicle::where(
                    'orbit_number',
                    '=',
                    $ford_report['ORBITNO'],
                )->first();


                if ($vehicle) {
                    if (in_array($vehicle->vehicle_status, $exclude_status)) {
                        \Log::debug(
                            $vehicle->id .
                            ' ' .
                            $vehicle->niceName() .
                            ' has the vehicle status ' .
                            Vehicle::statusMatch($vehicle->vehicle_status) .
                            ' and was skipped in the Ford Report import.',
                        );

                    } else {

                        $prefix = array_shift($ford_report);

                        $location = Location::where(
                            'location',
                            '=',
                            $ford_report['LOCATION'],
                        )->first();

                        if ($location) {
                            $location = intval($location->status);
                        } else {
                            $location = 4;
                        }

                        $build_date = null;

                        if ($ford_report['PLAN_BUILD_DATE']) {
                            $build_date = Carbon::createFromFormat(
                                'd/m/Y',
                                $ford_report['PLAN_BUILD_DATE'],
                            )->format('Y-m-d h:i:s');
                        }

                        if ($ford_report['ETA_DATE']) {
                            $due_date = Carbon::createFromFormat(
                                'd/m/Y',
                                $ford_report['ETA_DATE'],
                            )->format('Y-m-d h:i:s');
                        } else {
                            $due_date = null;
                        }

                        $vehicle->update([
                            'chassis' => $ford_report['VIN'],
                            'chassis_prefix' => $prefix,
                            'vehicle_status' => $location,
                            'build_date' => $build_date,
                            'due_date' => $due_date,
                        ]);

                        $order = $vehicle->order;
                        if ($order) {
                            if ($vehicle->wasChanged('vehicle_status')) {
                                if ($vehicle->vehicle_status === '1') {
                                    $brokers = User::where(
                                        'company_id',
                                        $order->broker,
                                    )->get();
                                    foreach ($brokers as $broker) {
                                        $broker->notify(
                                            new VehicleInStockNotification(
                                                $vehicle,
                                            ),
                                        );
                                    }
                                }
                            }
                        }
                    }
                }
            }
            notify()->success(
                'All vehicles have been updated',
                'Import Update Successfully',
            );
            return redirect()->route('csv_upload');

        }
        return false;
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

    public function showFitOptionUpload(): Factory|View|Application
    {
        return view('fit-options.index');
    }

    public function parseFitOptionImport(
        CsvImportRequest $request,
    ): View|Factory|RedirectResponse|Application {
        if ($request->has('header')) {
            $headings = (new HeadingRowImport())->toArray(
                $request->file('csv_file'),
            );
            $data = Excel::toArray(
                new FitOptionImport(),
                $request->file('csv_file'),
            );
        } else {
            $data = array_map('str_getcsv', file('csv_file')->getRealPath());
        }

        if (count($data) > 0) {
            $csv_data = array_slice($data, 0, 2);

            $csv_data_file = CsvData::create([
                'csv_filename' => $request
                    ->file('csv_file')
                    ->getClientOriginalName(),
                'csv_header' => $request->has('header'),
                'csv_data' => json_encode($data),
            ]);
        } else {
            return redirect()->back();
        }

        return view('import_fields', [
            'headings' => $headings ?? null,
            'csv_data' => $csv_data,
            'csv_data_file' => $csv_data_file,
        ]);
    }

    public function processFitOptionImport(Request $request): RedirectResponse
    {
        $data = CsvData::find($request->csv_data_field_id);
        $csv_data = json_decode($data->csv_data, true);

        foreach ($csv_data[0] as $row) {
            $fitOption = new FitOption();
            foreach (config('app.db_fields') as $field) {
                $fitOption->$field = $row[$request->fields[$field]];
            }
            $fitOption->save();
            if ($fitOption->model) {
                $model = VehicleModel::where(
                    'name',
                    $fitOption->model,
                )->first();
                $fitOption->update([
                    'model' => $model->id,
                ]);
            }
        }
        return redirect()->route('meta.factoryfit.index');
    }
}
