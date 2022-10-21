<?php

namespace App\Http\Controllers;

use App\Exports\DashboardExports;
use App\Models\Reservation;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
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

    public function showFordPipeline()
    {
        return view('vehicles.index', [
            'ringfenced' => false,
            'fordpipeline' => true,
            'title' => 'Ford Pipeline',
            'active_page' => 'ford-pipeline',
        ]);
    }

    public function showLedenStock()
    {
        return view('vehicles.index', [
            'ringfenced' => false,
            'fordpipeline' => false,
            'title' => 'Leden Stock',
            'active_page' => 'pipeline',
        ]);
    }

    public function showRingFencedStock()
    {
        return view('vehicles.index', [
            'ringfenced' => true,
            'fordpipeline' => false,
            'title' => 'Ring Fenced Stock',
            'active_page' => 'ring_fenced_stock',
        ]);
    }

    /**
     */
    public function factory_order_export(): BinaryFileResponse
    {
        $vehicles = Vehicle::where('vehicle_status', 4)
            ->with('manufacturer:id,name')
            ->get();

        $date = Carbon::now()->format('Y-m-d');

        return Excel::download(
            new DashboardExports($vehicles),
            'factory-orders-' . $date . '.xlsx',
        );
    }

    public function europe_vhc_export(): BinaryFileResponse
    {
        $vehicles = Vehicle::where('vehicle_status', 10)
            ->with('manufacturer:id,name')
            ->get();

        $date = Carbon::now()->format('Y-m-d');

        return Excel::download(
            new DashboardExports($vehicles),
            'europe-vhc-orders-' . $date . '.xlsx',
        );
    }
    public function uk_vhc_export(): BinaryFileResponse
    {
        $vehicles = Vehicle::where('vehicle_status', 11)
            ->with('manufacturer:id,name')
            ->get();

        $date = Carbon::now()->format('Y-m-d');

        return Excel::download(
            new DashboardExports($vehicles),
            'uk-vhc-orders-' . $date . '.xlsx',
        );
    }
    public function in_stock_export(): BinaryFileResponse
    {
        $vehicles = Vehicle::where('vehicle_status', 1)
            ->with('manufacturer:id,name')
            ->get();

        $date = Carbon::now()->format('Y-m-d');

        return Excel::download(
            new DashboardExports($vehicles),
            'in-stock-orders-' . $date . '.xlsx',
        );
    }

    public function ready_for_delivery_export(): BinaryFileResponse
    {
        $vehicles = Vehicle::where('vehicle_status', 3)
            ->with('manufacturer:id,name')
            ->get();

        $date = Carbon::now()->format('Y-m-d');

        return Excel::download(
            new DashboardExports($vehicles),
            'ready-for-delivery-orders-' . $date . '.xlsx',
        );
    }
    public function delivery_booked_export(): BinaryFileResponse
    {
        $vehicles = Vehicle::where('vehicle_status', 6)
            ->with('manufacturer:id,name')
            ->get();

        $date = Carbon::now()->format('Y-m-d');

        return Excel::download(
            new DashboardExports($vehicles),
            'delivery-booked-orders-' . $date . '.xlsx',
        );
    }
    public function awaiting_ship_export(): BinaryFileResponse
    {
        $vehicles = Vehicle::where('vehicle_status', 13)
            ->with('manufacturer:id,name')
            ->get();

        $date = Carbon::now()->format('Y-m_d');

        return Excel::download(
            new DashboardExports($vehicles),
            'awaiting-ship-orders-' . $date . '.xlsx',
        );
    }
    public function at_converter_export(): BinaryFileResponse
    {
        $vehicles = Vehicle::where('vehicle_status', 12)
            ->with('manufacturer:id,name')
            ->get();

        $date = Carbon::now()->format('Y-m_d');

        return Excel::download(
            new DashboardExports($vehicles),
            'awaiting-ship-orders-' . $date . '.xlsx',
        );
    }

    public function in_stock_registered_export(): BinaryFileResponse
    {
        $vehicles = Vehicle::where('vehicle_status', 15)
            ->with('manufacturer:id,name')
            ->get();

        $date = Carbon::now()->format('Y-m_d');

        return Excel::download(
            new DashboardExports($vehicles),
            'in-stock-registered-' . $date . '.xlsx',
        );
    }

    public function recycle()
    {
        $vehicles = Vehicle::onlyTrashed()
            ->latest()
            ->paginate(10);

        return view('vehicles.deleted', [
            'title' => 'Recycle Bin',
            'active_page' => 'vehicle-recycle-bin',
            'vehicles' => $vehicles,
        ]);
    }

    public function forceDelete($vehicle): RedirectResponse
    {
        Vehicle::withTrashed()
            ->where('id', $vehicle)
            ->forceDelete();
        return redirect()->route('vehicle.recycle_bin');
    }

    public function restore($vehicle): RedirectResponse
    {
        Vehicle::withTrashed()
            ->where('id', $vehicle)
            ->restore();
        return redirect()->route('vehicle.recycle_bin');
    }

    public function searchVehicles()
    {
        return view('vehicles.search');
    }
}
