<?php

namespace App\Http\Controllers;

use App\Exports\DashboardExports;
use App\Exports\DeliveryBookedExport;
use App\Exports\EuropeVHCExports;
use App\Exports\FactoryOrderExports;
use App\Exports\InStockExports;
use App\Exports\ReadyForDeliveryExports;
use App\Exports\UKVHCExports;
use App\Invoice;
use App\Order;
use App\OrderLegacy;

use App\Vehicle;

use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Writer\Exception;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('vehicles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param Vehicle $vehicle
     * @return Application|Factory|View
     */
    public function show(Vehicle $vehicle)
    {
        return (view('vehicles.show', ['vehicle' => $vehicle]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Vehicle $vehicle
     * @return Application|Factory|View
     */
    public function edit(Vehicle $vehicle)
    {
        return view('vehicles.edit', ['vehicle' => $vehicle]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Vehicle $vehicle
     * @return void
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Vehicle $vehicle
     */
    public function destroy(Vehicle $vehicle)
    {
        Vehicle::destroy($vehicle->id);
        return redirect()->route('pipeline')->with('successMsg', 'Vehicle #' . $vehicle->id . ' deleted successfully - ' . $vehicle->niceName() );
    }

    public function buildNewVehicle()
    {

        $orders = OrderLegacy::withTrashed()->get();

        foreach ( $orders as $order ) {

            $vehicle = new Vehicle();

            $vehicle->vehicle_status = $order->vehicle_status;
            $vehicle->reg = $order->vehicle_reg;
            $vehicle->model_year = $order->model_year;
            $vehicle->make = $order->vehicle_make;
            $vehicle->model = $order->vehicle_model;
            $vehicle->derivative = $order->vehicle_derivative;
            $vehicle->engine = $order->vehicle_engine;
            $vehicle->transmission = $order->vehicle_trans;
            $vehicle->fuel_type = $order->vehicle_fuel_type;
            $vehicle->doors = $order->vehicle_doors;
            $vehicle->colour = $order->vehicle_colour;
            $vehicle->body = $order->vehicle_body;
            $vehicle->trim = $order->vehicle_trim;
            $vehicle->chassis_prefix = $order->chassis_prefix;
            $vehicle->chassis = $order->chassis;
            $vehicle->type = $order->vehicle_type;
            $vehicle->metallic_paint = $order->metallic_paint;
            $vehicle->list_price = $order->list_price;
            $vehicle->first_reg_fee = $order->first_reg_fee;
            $vehicle->rfl_cost = $order->rfl_cost;
            $vehicle->onward_delivery = $order->onward_delivery;
            $vehicle->vehicle_registered_on = $order->vehicle_registered_on;
            $vehicle->hide_from_broker = $order->hide_from_broker;
            $vehicle->hide_from_dealer = $order->hide_from_dealer;
            $vehicle->show_in_ford_pipeline = $order->show_in_ford_pipeline;
            $vehicle->deleted_at = $order->deleted_at;

            $vehicle->save();

        }

        dd( 'Done' );

    }


    public function showFordPipeline(Request $request)
    {

        $data = Vehicle::select('id', 'make', 'model', 'derivative', 'reg', 'engine', 'doors', 'colour', 'type', 'dealer_fit_options', 'factory_fit_options')
            ->with('manufacturer:id,name')
            ->where('show_in_ford_pipeline', true)->get();

        if (Auth::user()->role == 'dealer') {
            $data = $data->where('hide_from_dealer', false );
        }

        if (Auth::user()->role == 'broker') {
            $data = $data->where('hide_from_broker', false );
        }

        return view('vehicles.index', ['data' => $data, 'title' => 'Ford Pipeline', 'active_page'=> 'ford-pipeline']);
    }

    public function showLedenStock(Request $request)
    {
        $data = Vehicle::select('id', 'orbit_number', 'ford_order_number', 'make', 'model', 'derivative', 'reg', 'engine', 'vehicle_status', 'colour', 'type', 'dealer_fit_options', 'factory_fit_options')
            ->with('manufacturer:id,name')
            ->with('order:id,vehicle_id')
            ->where('show_in_ford_pipeline', false)->get();

        if (Auth::user()->role == 'dealer') {
            $data = $data->where('hide_from_dealer', false );
        }

        if (Auth::user()->role == 'broker') {
            $data = $data->where('hide_from_broker', false );
        }

        $stock = [];
        foreach ($data as $k => $vehicle) {
            if (!isset($vehicle->order)){
                $stock[$k] = $vehicle;
            }
        }

        return view('vehicles.index', ['data'=> $stock, 'title' => 'Leden Stock', 'active_page'=> 'pipeline']);
    }

    public function deleteSelected()
    {
        $ids = request()->input('ids');

        Vehicle::destroy($ids);
    }

    /**
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws Exception
     */
    public function factory_order_export(): BinaryFileResponse
    {
        $vehicles = Vehicle::where('vehicle_status', 4)
            ->with('manufacturer:id,name')
            ->get();

        $date = Carbon::now()->format('Y-m-d');

        return Excel::download(new DashboardExports($vehicles), 'factory-orders-' . $date . '.xlsx');
    }

    public function europe_vhc_export(): BinaryFileResponse
    {
        $vehicles = Vehicle::where('vehicle_status', 10)->with('manufacturer:id,name')->get();

        $date = Carbon::now()->format('Y-m-d');

        return Excel::download(new DashboardExports($vehicles), 'europe-vhc-orders-' . $date . '.xlsx');
    }
    public function uk_vhc_export(): BinaryFileResponse
    {
        $vehicles = Vehicle::where('vehicle_status', 11)->with('manufacturer:id,name')->get();

        $date = Carbon::now()->format('Y-m-d');

        return Excel::download(new DashboardExports($vehicles), 'uk-vhc-orders-' . $date . '.xlsx');
    }
    public function in_stock_export(): BinaryFileResponse
    {
        $vehicles = Vehicle::where('vehicle_status', 1)->with('manufacturer:id,name')->get();

        $date = Carbon::now()->format('Y-m-d');

        return Excel::download(new DashboardExports($vehicles), 'in-stock-orders-' . $date . '.xlsx');
    }

    public function ready_for_delivery_export(): BinaryFileResponse
    {
        $vehicles = Vehicle::where('vehicle_status', 3)->with('manufacturer:id,name')->get();

        $date = Carbon::now()->format('Y-m-d');

        return Excel::download(new DashboardExports($vehicles), 'ready-for-delivery-orders-' . $date . '.xlsx');
    }
    public function delivery_booked_export(): BinaryFileResponse
    {
        $vehicles = Vehicle::where('vehicle_status', 6)->with('manufacturer:id,name')->get();

        $date = Carbon::now()->format('Y-m-d');

        return Excel::download(new DashboardExports($vehicles), 'delivery-booked-orders-' . $date . '.xlsx');
    }

    public function completedDateCleanup()
    {
        $completedVehicles = Order::whereHas('vehicle', function ($q){
            $q->whereIn('vehicle_status', [7]);
        })->get();

        foreach($completedVehicles as $vehicle) {
            if ($vehicle->completed_date) {
                continue;
            }
            $vehicle->update(['completed_date' => $vehicle->vehicle->updated_at]);
        }

        dd('Done');
    }

    public function orderRefCleanup()
    {
        $orders = Order::all();

        foreach ($orders as $order) {
            $vehicle = $order->vehicle;
            if ($vehicle) {
                $vehicle->update([
                    'ford_order_number' => $order->order_ref
                ]);
            }
        }

        dd('done');
    }

    public function recycle()
    {
        $vehicles = Vehicle::onlyTrashed()->latest()->paginate(10);

        return view('vehicles.deleted', ['title' => 'Recycle Bin', 'active_page' => 'vehicle-recycle-bin','vehicles' => $vehicles]);
    }

    public function forceDelete($vehicle): RedirectResponse
    {
        Vehicle::withTrashed()->where('id', $vehicle)->forceDelete();
        return redirect()->route('vehicle.recycle_bin');
    }

    public function restore($vehicle): RedirectResponse
    {
        Vehicle::withTrashed()->where('id', $vehicle)->restore();
        return redirect()->route('vehicle.recycle_bin');
    }

    public function date_cleaner() {

        Vehicle::withTrashed()->chunk(100, function($vehicles) {

            foreach ($vehicles as $vehicle) {
                if ($vehicle->vehicle_registered_on === '0000-00-00 00:00:00') {
                    $vehicle->update(['vehicle_registered_on' => null]);
                    var_dump('Vehicles processed');
                }
            }
        });
        Order::withTrashed()->chunk(100, function ($orders) {
            foreach ($orders as $order) {
                if ($order->delivery_date === '0000-00-00 00:00:00'){
                    $order->update(['delivery_date' => null]);
                    var_dump('Delivery Date Nulled');
                }
                if ($order->due_date === '0000-00-00 00:00:00'){
                    $order->update(['due_date' => null]);
                    var_dump('Due Date Nulled');
                }
            }
        });
        Invoice::chunk(100, function($invoices) {
            foreach ($invoices as $invoice) {
                if ($invoice->finance_commission_pay_date === '0000-00-00 00:00:00') {
                    $invoice->update(['finance_commission_pay_date' => null]);
                    var_dump('Finance Commission Pay Date Nulled');
                }
                if ($invoice->broker_commission_pay_date === '0000-00-00 00:00:00') {
                    $invoice->update(['broker_commission_pay_date' => null]);
                    var_dump('Broker Commission Pay Date Nulled');
                }
                if ($invoice->broker_pay_date === '0000-00-00 00:00:00') {
                    $invoice->update(['broker_pay_date' => null]);
                    var_dump('Broker Pay Date Nulled');
                }
                if ($invoice->dealer_pay_date === '0000-00-00 00:00:00') {
                    $invoice->update(['dealer_pay_date' => null]);
                    var_dump('Dealer Pay Date Nulled');
                }
            }
        });


    }


}
