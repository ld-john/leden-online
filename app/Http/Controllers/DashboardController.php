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
	public function __construct() {
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
			return view('dashboard-dealer', [
				'in_stock' => $this->GetVehicleByStatus(1),
				'orders_placed' => $this->GetVehicleByStatus(2, 'dealer'),
				'ready_for_delivery' => $this->GetVehicleByStatus(3, 'dealer'),
				'completed_orders' => $this->GetVehicleByStatus(7, 'dealer'),
				'notifications' => Auth::user()->notifications->take(6),
			]);
		} else {
			if ($request->ajax()) {

				$data = Vehicle::select('id', 'make', 'model', 'reg', 'type')
					->where('vehicle_status', 1)
					->with('manufacturer')
					->get();
				return Datatables::of($data)
					->addColumn('action', function($row){
						$btn = '<a href="/orders/view/' . $row->id . '" class="btn btn-warning"><i class="far fa-eye"></i> View</a>';

						return $btn;
					})
					->rawColumns(['action'])
					->make(true);
			}



			return view('dashboard-broker', [
				'in_stock' => $this->GetVehicleByStatus(1),
				'orders_placed' => $this->GetVehicleByStatus(2),
				'ready_for_delivery' => $this->GetVehicleByStatus(3),
				'completed_orders' => $this->GetVehicleByStatus(7),
				'notifications' => Auth::user()->notifications->take(6),
			]);
		}
	}

	protected function adminDashboard(){
		$vehicles_registered = Vehicle::select(DB::raw('MONTHNAME(vehicle_registered_on) as month, COUNT(id) as orders'))
			->where('vehicle_status', 1)
			->where('vehicle_registered_on','>', Carbon::now()->subMonths(6))
			->get();

		/* $vehicles_registered = DB::table('orderLegacy')
			->select(DB::raw('MONTHNAME(vehicle_registered_on) as month, COUNT(id) as orders'))
			->where('vehicle_status', 1)
			->where("vehicle_registered_on",">", Carbon::now()->subMonths(6))
			->groupByRaw("DATE_FORMAT(vehicle_registered_on, '%Y-%m')")
			->get(); */
		$values = [];
		$max = 0;


		foreach ($vehicles_registered as $order) {
			$values = [];
			array_push($values, $order->orders);
		}

		if($values){
			$max = (ceil(max($values) / 10) * 10) + 10;
		}


		return view('dashboard', [
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

	public static function GetVehicleByStatus($vehicle_status, $role = null) {


		$vehicles = Vehicle::where('vehicle_status', $vehicle_status);

		if ($vehicle_status != 1 && $role == 'dealer') {
			$vehicles->where('dealership', Auth::user()->company_id);
		}
		if ($vehicle_status != 1 && $role == 'broker') {
			$vehicles->where('broker', Auth::user()->company_id);
		}

		return $vehicles->count();
	}

	/* Show all notifications page */
	public function showNotifications() {
		$all_notifications = Auth::user()->notifications()->get();

		return view('notifications', [
			'all_notifications' => $all_notifications
		]);
	}

	/* Delete all user notifications */
	public function executeDeleteNotifications() {
		Auth::user()->notifications()->delete();

		return redirect()->back();
	}
}
