<?php

namespace App\Http\Controllers;

use App\Order;
use App\OrderLegacy;
use App\Vehicle;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function index(Request $request)
    {
        if (Auth::user()->role == 'admin') {
            return $this->adminDashboard();
        } elseif (Auth::user()->role == 'dealer') {
            return view('dashboard.dashboard-dealer', [
                'in_stock' => $this->GetVehicleByStatus(1, 'dealer'),
                'orders_placed' => $this->GetOrdersByVehicleStatus(2),
                'ready_for_delivery' => $this->GetOrdersByVehicleStatus(3),
                'completed_orders' => $this->GetOrdersByVehicleStatus(7),
                'notifications' => Auth::user()->notifications->take(6),
            ]);
        } else {
	        $data = Vehicle::select('id', 'make', 'model', 'reg', 'type')
		        ->where('vehicle_status', 1)
		        ->with('manufacturer:id,name')
		        ->where('show_offer', true)
		        ->get();
/*            if ($request->ajax()) {

                $data = Vehicle::select('id', 'make', 'model', 'reg', 'type')
                    ->where('vehicle_status', 1)
                    ->with('manufacturer')
                    ->where('show_offer', true)
                    ->get();
                return Datatables::of($data)
                    ->addColumn('action', function ($row) {
                        $btn = '<a href="/orders/view/' . $row->id . '" class="btn btn-warning"><i class="far fa-eye"></i> View</a>';

                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }*/


            return view('dashboard.dashboard-broker', [
            	'data' => $data,
                'in_stock' => $this->GetVehicleByStatus(1),
                'orders_placed' => $this->GetOrdersByVehicleStatus(2),
                'ready_for_delivery' => $this->GetOrdersByVehicleStatus(3),
                'completed_orders' => $this->GetOrdersByVehicleStatus(7),
                'notifications' => Auth::user()->notifications->take(6),
            ]);
        }
    }

    protected function adminDashboard()
    {


        $vehicles_registered = Vehicle::select(
            DB::raw('count(id) as `data`'),
            DB::raw("DATE_FORMAT(vehicle_registered_on, '%M') month_label"),
            DB::raw('YEAR(vehicle_registered_on) year, MONTH(vehicle_registered_on) month'))
            ->groupby('year', 'month')
            ->where('vehicle_registered_on', '>', Carbon::now()->subMonths(6))
            ->get();

        foreach ($vehicles_registered as $vehicle) {
            $max_count[] = $vehicle->data;
        }


        if (isset ($max_count)) {
            $max = max($max_count);
        } else {
            $max = 5;
        }


        return view('dashboard.dashboard-admin', [
            'in_stock' => $this->GetVehicleByStatus(1),
            'orders_placed' => $this->GetVehicleByStatus(2),
            'ready_for_delivery' => $this->GetVehicleByStatus(3),
            'factory_order' => $this->GetVehicleByStatus(4),
            'delivered' => $this->GetVehicleByStatus(6),
            'completed_orders' => $this->GetVehicleByStatus(7),
            'europe_vhc' => $this->GetVehicleByStatus(10),
            'uk_vhc' => $this->GetVehicleByStatus(11),
            'vehicles_registered' => $vehicles_registered,
            'max' => $max,
            'notifications' => Auth::user()->notifications->take(6),
        ]);
    }

    public static function GetVehicleByStatus($vehicle_status, $role = null)
    {


        $vehicles = Vehicle::where('vehicle_status', $vehicle_status);

        if ($role == 'dealer') {
            $vehicles->where('dealer_id', Auth::user()->company_id);
        }
        if ($vehicle_status != 1 && $role == 'broker') {
            $vehicles->where('broker', Auth::user()->company_id);
        }

        return $vehicles->count();
    }

    public static function GetOrdersByVehicleStatus($status)
    {
        if ( Auth::user()->role != 'admin') {

            $searchField = Auth::user()->role . '_id';

            $orders = Order::whereHas('vehicle', function ($q) use ($status) {
                $q->where('vehicle_status', $status);
            })->where($searchField, Auth::user()->company_id);

        } else {

	        $orders = Order::whereHas('vehicle', function ($q) use ($status) {
		        $q->where('vehicle_status', $status);
	        });

        }
	    return $orders->count();

    }

	/* Show all notifications page */
	public function showNotifications() {
		$all_notifications = Auth::user()->notifications()->get();

		return view('notifications.index', [
			'all_notifications' => $all_notifications
		]);
	}

	/* Delete all user notifications */
	public function executeDeleteNotifications() {
		Auth::user()->notifications()->delete();

		return redirect()->back();
	}
}
