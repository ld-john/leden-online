<?php

namespace App\Http\Controllers;

use App\Exports\DeliveryBookedExport;
use App\Exports\EuropeVHCExports;
use App\Exports\InStockExports;
use App\Exports\ReadyForDeliveryExports;
use App\Exports\UKVHCExports;
use App\OrderLegacy;

use App\Vehicle;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Writer\Exception;

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
     * @return RedirectResponse
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
		$data = Vehicle::select('id', 'make', 'model', 'derivative', 'reg', 'engine', 'doors', 'colour', 'type', 'dealer_fit_options', 'factory_fit_options')
			->with('manufacturer:id,name')
			->where('show_in_ford_pipeline', false)
            ->whereIn('vehicle_status', [0])->get();

		if (Auth::user()->role == 'dealer') {
			$data = $data->where('hide_from_dealer', false );
		}

		if (Auth::user()->role == 'broker') {
			$data = $data->where('hide_from_broker', false );
		}

		return view('vehicles.index', ['data'=> $data, 'title' => 'Leden Stock', 'active_page'=> 'pipeline']);
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
    public function factory_order_export()
    {
        return Excel::download(new InStockExports, 'factoryOrders.xlsx');
    }

    public function europe_vhc_export()
    {
        return Excel::download(new EuropeVHCExports(), 'europeVHC.xlsx');
    }
    public function uk_vhc_export()
    {
        return Excel::download(new UKVHCExports(), 'UKVHC.xlsx');
    }
    public function in_stock_export()
    {
        return Excel::download(new InStockExports, 'inStock.xlsx');
    }

    public function ready_for_delivery_export()
    {
        return Excel::download(new ReadyForDeliveryExports(), 'ReadyForDelivery.xlsx');
    }
    public function delivery_booked_export()
    {
        return Excel::download(new DeliveryBookedExport(), 'DeliveryBooked.xlsx');
    }


}
