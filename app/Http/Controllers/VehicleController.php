<?php

namespace App\Http\Controllers;

use App\Enums\VehicleStatus;
use App\Exports\BrokersStockDownload;
use App\Exports\DashboardExports;
use App\Mail\RequestOTR;
use App\Models\Company;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Exception;
use Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class VehicleController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return Factory|View
     */
    public function create()
    {
        return view('vehicles.create');
    }

    /**
     * Display the specified resource.
     *
     * @param Vehicle $vehicle
     * @return Factory|View
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
     * @return Factory|View
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
     * @return Factory|View
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
     * @return Factory|View
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
     * @return Factory|View
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

    public function status_export(VehicleStatus $status) : BinaryFileResponse
    {
        $vehicles = Vehicle::where('vehicle_status', $status->value)
            ->with([
                'manufacturer:id,name',
                'order',
                'order.customer',
                'order.broker',
                'broker'
            ])->get();

        $date = Carbon::now()->format('Y-m-d');

        $slug = $status->slug();

        return Excel::download(
            new DashboardExports($vehicles),
            "{$slug}-export-{$date}.xlsx"
        );
    }

    /**
     * Export a list of vehicles available for ordering by specific brokers
     * @param Company $broker
     * @return BinaryFileResponse
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
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
     * @return Factory|View
     */
    public function recycle()
    {
        return view('vehicles.deleted', [
            'title' => 'Recycle Bin',
            'active_page' => 'vehicle-recycle-bin'
        ]);
    }

    /**
     * Returns Universal Vehicle search page
     * @return Factory|View
     */
    public function searchVehicles()
    {
        return view('vehicles.search');
    }

    public function request_otr(Vehicle $vehicle): RedirectResponse
    {
        Mail::to(config('leden.otr_requests.address'), config('leden.otr_requests.name'))
            ->queue(new RequestOTR(Auth::user(), $vehicle));

        notify()->success(
            'A request has been sent to Leden for an OTR. Someone will be in touch with you shortly.',
            'OTR Requested',
        );

        return to_route('vehicle.show', $vehicle);
    }
}
