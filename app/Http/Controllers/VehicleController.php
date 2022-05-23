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

use App\Reservation;
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
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('vehicles.create');
    }

    /**
     * Display the specified resource.
     *
     * @param Vehicle $vehicle
     * @return Application|Factory|View
     */
    public function show(Vehicle $vehicle)
    {
        $user = Auth::user();
        $reservation_allowed = $user->reservation_allowed;
        if ($reservation_allowed) {
            $old_reservations = Reservation::where('customer_id', $user->id)->where('vehicle_id', $vehicle->id)->withTrashed()->get();
            if( $old_reservations->count() > 0 ) {
                $reservation_allowed = 0;
            }
        }

        return (view('vehicles.show', ['vehicle' => $vehicle, 'reservation_allowed' => $reservation_allowed]));
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

    public function showFordPipeline()
    {
        return view('vehicles.index', ['ringfenced' => false, 'fordpipeline' =>true, 'title' => 'Ford Pipeline', 'active_page'=> 'ford-pipeline']);
    }

    public function showLedenStock()
    {        return view('vehicles.index', ['ringfenced' => false, 'fordpipeline' => false, 'title' => 'Leden Stock', 'active_page'=> 'pipeline']);
    }

    public function showRingFencedStock()
    {
        return view('vehicles.index', ['ringfenced'=> true, 'fordpipeline' => false, 'title' => 'Ring Fenced Stock', 'active_page'=> 'ring_fenced_stock']);
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
    public function awaiting_ship_export()
    {
        $vehicles = Vehicle::where('vehicle_status', 13)->with('manufacturer:id,name')->get();

        $date = Carbon::now()->format('Y-m_d');

        return Excel::download(new DashboardExports($vehicles), 'awaiting-ship-orders-' . $date . '.xlsx');
    }
    public function at_converter_export()
    {
        $vehicles = Vehicle::where('vehicle_status', 12)->with('manufacturer:id,name')->get();

        $date = Carbon::now()->format('Y-m_d');

        return Excel::download(new DashboardExports($vehicles), 'awaiting-ship-orders-' . $date . '.xlsx');
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

    public function fitOptionsCleanUp()
    {

        Vehicle::withTrashed()->chunk(100, function($vehicles) {
           foreach ($vehicles as $vehicle) {
               $factory = json_decode($vehicle->factory_fit_options) ?? [];
               while (gettype($factory) === 'string') {
                   $factory = json_decode($factory);
               }
               $dealer = json_decode($vehicle->dealer_fit_options) ?? [];
               while (gettype($dealer) === 'string') {
                   $dealer = json_decode($dealer);
               }
               $fitOptions = array_merge($dealer, $factory);

               $vehicle->fitOptions()->sync($fitOptions);
               var_dump('Done');
           }
        });
    }


}
