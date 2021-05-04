<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Notifications\notifications;
use App\OrderLegacy;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DataTables;
use Illuminate\Support\Facades\Response;
use Mail;
use Auth;
use DB;

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
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {

        if (Helper::roleCheck(Auth::user()->id)->role == 'admin') {
            return $this->adminDashboard();
        } elseif (Helper::roleCheck(Auth::user()->id)->role == 'dealer') {
            return view('dashboard-dealer', [
                'in_stock' => $this->GetOrdersById(1),
                'orders_placed' => $this->GetOrdersById(2, 'dealer'),
                'ready_for_delivery' => $this->GetOrdersById(3, 'dealer'),
                'completed_orders' => $this->GetOrdersById(7, 'dealer'),
                'notifications' => Auth::user()->notifications->take(6),
            ]);
        } else {
            if ($request->ajax()) {

                $data = DB::table('order')
                ->select('id', 'vehicle_make', 'vehicle_model', 'vehicle_reg', 'vehicle_type')
                ->where('vehicle_status', 1)
                ->where('broker', null)
                ->where('show_offer', true)
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
                'in_stock' => $this->GetOrdersById(1),
                'orders_placed' => $this->GetOrdersById(2),
                'ready_for_delivery' => $this->GetOrdersById(3),
                'completed_orders' => $this->GetOrdersById(7),
                'notifications' => Auth::user()->notifications->take(6),
            ]);
        }
    }

    protected function adminDashboard(){
        $vehicles_registered = DB::table('orderLegacy')
            ->select(DB::raw('MONTHNAME(vehicle_registered_on) as month, COUNT(id) as orders'))
            ->where('vehicle_status', 1)
            ->where("vehicle_registered_on",">", Carbon::now()->subMonths(6))
            ->groupByRaw("DATE_FORMAT(vehicle_registered_on, '%Y-%m')")
            ->get();
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
            'in_stock' => $this->GetOrdersById(1),
            'orders_placed' => $this->GetOrdersById(2),
            'ready_for_delivery' => $this->GetOrdersById(3),
            'factory_order' => $this->GetOrdersById(4),
            'delivered' => $this->GetOrdersById(6),
            'completed_orders' => $this->GetOrdersById(7),
            'europe_vhc' => $this->GetOrdersById(10),
            'uk_vhc' => $this->GetOrdersById(11),
            'vehicles_registered' => $vehicles_registered,
            'max' => $max,
            'notifications' => Auth::user()->notifications->take(6),
        ]);
    }

    public static function GetOrdersById($vehicle_status, $role = null) {


            $vehicles = OrderLegacy::where('vehicle_status', $vehicle_status);

            if($vehicle_status == 1){
                $vehicles->where(function($query){
                    $query->where('customer_name', '!=', null)
                        ->orWhere('company_name', '!=', null);
                });
            }

            if ($vehicle_status != 1 && $role == 'dealer') {
                $vehicles->where('dealership', Auth::user()->company_id);
            }
            if ($vehicle_status != 1 && $role == 'broker') {
                $vehicles->where('broker', Auth::user()->company_id);
            }

        //$total = $vehicles->get();

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
