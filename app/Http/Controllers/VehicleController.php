<?php

namespace App\Http\Controllers;

use App\Exports\BrokersStockDownload;
use App\Exports\DashboardExports;
use App\Mail\LoginRequest;
use App\Mail\RequestOTR;
use App\Models\Company;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Str;
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

        \Log::info(
            $user->firstname .
                ' ' .
                $user->lastname .
                ' has looked at the vehicle details for Vehicle #' .
                $vehicle->id,
        );

        if ($reservation_allowed) {
            $old_reservations = Reservation::where('customer_id', $user->id)
                ->where('vehicle_id', $vehicle->id)
                ->withTrashed()
                ->get();
            if ($old_reservations->count() > 0) {
                $reservation_allowed = 0;
            }
        }

        return view('vehicles.show', [
            'vehicle' => $vehicle,
            'reservation_allowed' => $reservation_allowed,
        ]);
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
        notify()->success(
            'Vehicle #' .
                $vehicle->id .
                ' deleted successfully - ' .
                $vehicle->niceName(),
            'Vehicle Deleted',
        );
        return redirect()->route('pipeline');
    }

    /**
     * Show the vehicles in the Ford Pipeline (Not Available for Ordering)
     * @return Application|Factory|View
     */
    public function showFordPipeline()
    {
        $user = Auth::user();
        \Log::info(
            $user->firstname .
                ' ' .
                $user->lastname .
                ' has looked at the Ford Pipeline',
        );

        return view('vehicles.index', [
            'ringfenced' => false,
            'fordpipeline' => true,
            'title' => 'Ford Pipeline',
            'active_page' => 'ford-pipeline',
        ]);
    }

    /**
     * Show the vehicles in Leden Stock which are not on order
     * @return Application|Factory|View
     */
    public function showLedenStock()
    {
        $user = Auth::user();
        \Log::info(
            $user->firstname .
                ' ' .
                $user->lastname .
                ' has looked at the Leden Stocklist',
        );

        return view('vehicles.index', [
            'ringfenced' => false,
            'fordpipeline' => false,
            'title' => 'Leden Stock',
            'active_page' => 'pipeline',
        ]);
    }

    /**
     * Show the items that have been ring-fenced for a specific broker
     * @return Application|Factory|View
     */
    public function showRingFencedStock()
    {
        $user = Auth::user();
        \Log::info(
            $user->firstname .
                ' ' .
                $user->lastname .
                ' from ' .
                $user->company?->company_name .
                ' has looked at their Ring-fenced Stock',
        );
        return view('vehicles.index', [
            'ringfenced' => true,
            'fordpipeline' => false,
            'title' => 'Ring Fenced Stock',
            'active_page' => 'ring_fenced_stock',
        ]);
    }

    /**
     * Download an Excel file of the Vehicles in the Factory Order
     * @return BinaryFileResponse
     */
    public function factory_order_export(): BinaryFileResponse
    {
        $vehicles = Vehicle::where('vehicle_status', 4)
            ->with('manufacturer:id,name')
            ->with('order')
            ->with('order.customer')
            ->with('order.broker')
            ->with('broker')
            ->get();

        $date = Carbon::now()->format('Y-m-d');

        return Excel::download(
            new DashboardExports($vehicles),
            'factory-orders-' . $date . '.xlsx',
        );
    }

    /**
     * Download an Excel file of the Vehicles in the Europe VHC status
     * @return BinaryFileResponse
     */
    public function europe_vhc_export(): BinaryFileResponse
    {
        $vehicles = Vehicle::where('vehicle_status', 10)
            ->with('manufacturer:id,name')
            ->with('order')
            ->with('order.customer')
            ->with('order.broker')
            ->with('broker')
            ->get();

        $date = Carbon::now()->format('Y-m-d');

        return Excel::download(
            new DashboardExports($vehicles),
            'europe-vhc-orders-' . $date . '.xlsx',
        );
    }

    /**
     * Download an Excel file of the Vehicles in the UK VHC status
     * @return BinaryFileResponse
     */
    public function uk_vhc_export(): BinaryFileResponse
    {
        $vehicles = Vehicle::where('vehicle_status', 11)
            ->with('manufacturer:id,name')
            ->with('order')
            ->with('order.customer')
            ->with('order.broker')
            ->with('broker')
            ->get();

        $date = Carbon::now()->format('Y-m-d');

        return Excel::download(
            new DashboardExports($vehicles),
            'uk-vhc-orders-' . $date . '.xlsx',
        );
    }

    /**
     * Download an Excel file of the Vehicles in the In Stock
     * @return BinaryFileResponse
     */
    public function in_stock_export(): BinaryFileResponse
    {
        $vehicles = Vehicle::where('vehicle_status', 1)
            ->with('manufacturer:id,name')
            ->with('order')
            ->with('order.customer')
            ->with('order.broker')
            ->with('broker')
            ->get();

        $date = Carbon::now()->format('Y-m-d');

        return Excel::download(
            new DashboardExports($vehicles),
            'in-stock-orders-' . $date . '.xlsx',
        );
    }

    /**
     * Download an Excel file of the Vehicles in the In Stock
     * @return BinaryFileResponse
     */
    public function in_stock_registered_export(): BinaryFileResponse
    {
        $vehicles = Vehicle::where('vehicle_status', 15)
            ->with('manufacturer:id,name')
            ->with('order')
            ->with('order.customer')
            ->with('order.broker')
            ->with('broker')
            ->get();

        $date = Carbon::now()->format('Y-m-d');

        return Excel::download(
            new DashboardExports($vehicles),
            'in-stock-registered-orders-' . $date . '.xlsx',
        );
    }

    /**
     * Download an Excel file of the Vehicles in the In Stock
     * @return BinaryFileResponse
     */
    public function in_stock_dealer_export(): BinaryFileResponse
    {
        $vehicles = Vehicle::where('vehicle_status', 17)
            ->with('manufacturer:id,name')
            ->with('order')
            ->with('order.customer')
            ->with('order.broker')
            ->with('broker')
            ->get();

        $date = Carbon::now()->format('Y-m-d');

        return Excel::download(
            new DashboardExports($vehicles),
            'in-stock-awaiting-dealer-orders-' . $date . '.xlsx',
        );
    }

    /**
     * Download an Excel file of the Vehicles in the Ready for Delivery status
     * @return BinaryFileResponse
     */
    public function ready_for_delivery_export(): BinaryFileResponse
    {
        $vehicles = Vehicle::where('vehicle_status', 3)
            ->with('order')
            ->with('order.customer')
            ->with('order.broker')
            ->with('broker')
            ->with('manufacturer:id,name')
            ->get();

        $date = Carbon::now()->format('Y-m-d');

        return Excel::download(
            new DashboardExports($vehicles),
            'ready-for-delivery-orders-' . $date . '.xlsx',
        );
    }

    /**
     * Download an Excel file of the Vehicles in the Delivery Booked status
     * @return BinaryFileResponse
     */
    public function delivery_booked_export(): BinaryFileResponse
    {
        $vehicles = Vehicle::where('vehicle_status', 6)
            ->with('order')
            ->with('order.customer')
            ->with('order.broker')
            ->with('manufacturer:id,name')
            ->with('broker')
            ->get();

        $date = Carbon::now()->format('Y-m-d');

        return Excel::download(
            new DashboardExports($vehicles),
            'delivery-booked-orders-' . $date . '.xlsx',
        );
    }

    /**
     * Download an Excel file of the Vehicles in the Awaiting Ship status
     * @return BinaryFileResponse
     */
    public function awaiting_ship_export(): BinaryFileResponse
    {
        $vehicles = Vehicle::where('vehicle_status', 13)
            ->with('order')
            ->with('order.customer')
            ->with('order.broker')
            ->with('manufacturer:id,name')
            ->with('broker')
            ->get();

        $date = Carbon::now()->format('Y-m_d');

        return Excel::download(
            new DashboardExports($vehicles),
            'awaiting-ship-orders-' . $date . '.xlsx',
        );
    }

    /**
     * Download an Excel file of the Vehicles in the At Converter status
     * @return BinaryFileResponse
     */
    public function at_converter_export(): BinaryFileResponse
    {
        $vehicles = Vehicle::where('vehicle_status', 12)
            ->with('order')
            ->with('order.customer')
            ->with('order.broker')
            ->with('manufacturer:id,name')
            ->with('broker')
            ->get();

        $date = Carbon::now()->format('Y-m_d');

        return Excel::download(
            new DashboardExports($vehicles),
            'awaiting-ship-orders-' . $date . '.xlsx',
        );
    }

    public function damaged_export()
    {
        $vehicles = Vehicle::where('vehicle_status', 16)
            ->with('order')
            ->with('order.customer')
            ->with('order.broker')
            ->with('manufacturer:id,name')
            ->with('broker')
            ->get();
        $date = Carbon::now()->format('Y-m-d');

        return Excel::download(
            new DashboardExports($vehicles),
            'damaged-' . $date . '.xlsx',
        );
    }
    public function dealer_transfer_export()
    {
        $vehicles = Vehicle::where('vehicle_status', 18)
            ->with('order')
            ->with('order.customer')
            ->with('order.broker')
            ->with('manufacturer:id,name')
            ->with('broker')
            ->get();
        $date = Carbon::now()->format('Y-m-d');

        return Excel::download(
            new DashboardExports($vehicles),
            'dealer-transfer-' . $date . '.xlsx',
        );
    }

    /**
     * Export a list of vehicles available for ordering by specific brokers
     * @return BinaryFileResponse
     */
    public function brokers_stock_export(Company $broker)
    {
        $notRingFenced = Vehicle::has('order', '=', 0)
            ->where('ring_fenced_stock', '=', 0)
            ->with('order')
            ->with('manufacturer:id,name')
            ->with('broker')
            ->get();

        $RingFenced = Vehicle::has('order', '=', 0)
            ->where('ring_fenced_stock', '=', 1)
            ->where('broker_id', '=', $broker->id)
            ->with('order')
            ->with('manufacturer:id,name')
            ->with('broker')
            ->get();

        $vehicles = $notRingFenced->merge($RingFenced);

        $broker = Str::kebab($broker->company_name);

        $date = Carbon::now()->format('Y-m-d');

        return Excel::download(
            new BrokersStockDownload($vehicles),
            $broker . '-stock-' . $date . '.csv',
        );
    }

    /**
     * Show the Vehicle Recycle bin, allowing the user to either restore or permanently remove deleted vehicles
     * @return Application|Factory|View
     */
    public function recycle()
    {
        $vehicles = Vehicle::onlyTrashed()
            ->latest()
            ->with('manufacturer')
            ->paginate(10);

        return view('vehicles.deleted', [
            'title' => 'Recycle Bin',
            'active_page' => 'vehicle-recycle-bin',
            'vehicles' => $vehicles,
        ]);
    }

    /**
     * Forcibly remove a vehicle from the database, completely removing it rather than soft deleting.
     * @param $vehicle
     * @return RedirectResponse
     */
    public function forceDelete($vehicle): RedirectResponse
    {
        Vehicle::withTrashed()
            ->where('id', $vehicle)
            ->forceDelete();
        return redirect()->route('vehicle.recycle_bin');
    }

    /**
     * Restore a vehicle which has been soft-deleted to the Leden Stock List.
     * @param $vehicle
     * @return RedirectResponse
     */
    public function restore($vehicle): RedirectResponse
    {
        Vehicle::withTrashed()
            ->where('id', $vehicle)
            ->restore();
        return redirect()->route('vehicle.recycle_bin');
    }

    /**
     * Returns Universal Vehicle search page
     * @return Application|Factory|View
     */
    public function searchVehicles()
    {
        return view('vehicles.search');
    }

    /**
     * A temporary clean-up function to switch date/time fields to date fields.
     * @return void
     */
    public function cleanDates()
    {
        set_time_limit(300);
        Vehicle::chunk('50', function ($vehicles) {
            foreach ($vehicles as $vehicle) {
                if ($vehicle->vehicle_registered_on_OLD) {
                    $vehicle->update([
                        'vehicle_registered_on' =>
                            $vehicle->vehicle_registered_on_OLD,
                    ]);
                    var_dump('registration date moved');
                }
                if ($vehicle->due_date_OLD) {
                    $vehicle->update([
                        'due_date' => $vehicle->due_date_OLD,
                    ]);
                    var_dump('Due date moved');
                }
                if ($vehicle->build_date_OLD) {
                    $vehicle->update([
                        'build_date' => $vehicle->build_date_OLD,
                    ]);
                    var_dump('build date moved');
                }
                if ($vehicle->vehicle_reg_date_OLD) {
                    $vehicle->update([
                        'vehicle_reg_date' => $vehicle->vehicle_reg_date_OLD,
                    ]);
                    var_dump('provisional registration date moved');
                }
                if ($vehicle->order?->exists()) {
                    OrderController::setProvisionalRegDate($vehicle);
                    var_dump(
                        'provisional registration date checked against records',
                    );
                }
            }
        });
    }

    /**
     * A temporary clean-up function to ensure that all vehicles have a provisional registration date, regardless of being on order.
     * @return void
     */

    public function checkProvisionalDates()
    {
        set_time_limit(300);
        Vehicle::chunk('50', function ($vehicles) {
            foreach ($vehicles as $vehicle) {
                OrderController::setProvisionalRegDate($vehicle);
                var_dump($vehicle->id . ' checked');
            }
        });
        var_dump('Check Completed');
    }

    public function request_otr(Vehicle $vehicle)
    {
        $user = Auth::user();

        $mailUser = (new User())->forceFill([
            'name' => 'Availabilities',
            'email' => 'availabilities@leden.co.uk ',
        ]);
        Mail::to($mailUser)->send(new RequestOTR($user, $vehicle));
        notify()->success(
            'A request has been sent to Leden for an OTR. Someone will be in touch with you shortly.',
            'OTR Requested',
        );
        return redirect(route('vehicle.show', $vehicle->id));
    }
}
