<?php

namespace App\Http\Controllers;

use App\Order;
use App\Vehicle;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;

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
        $factory_order = $this->GetVehicleByStatus(4, Auth::user()->role);
        $euro_vhc = $this->GetVehicleByStatus(10, Auth::user()->role);
        $uk_vhc = $this->GetVehicleByStatus(11, Auth::user()->role);
        $in_stock = $this->GetVehicleByStatus(1, Auth::user()->role);
        $ready_for_delivery = $this->GetVehicleByStatus(3, Auth::user()->role);
        $delivery_booked = $this->GetVehicleByStatus(6, Auth::user()->role);
        $completed = $this->GetVehicleByStatus(7, Auth::user()->role);
        $live_orders = $factory_order->count() + $euro_vhc->count() + $uk_vhc->count() + $in_stock->count() + $ready_for_delivery->count() + $delivery_booked->count();

        if (Auth::user()->role == 'admin') {
            return $this->adminDashboard();
        } elseif (Auth::user()->role === 'dealer') {

            return view('dashboard.dashboard-dealer', [
                'live_orders' => $live_orders,
                'in_stock' => $in_stock->count(),
                'completed_orders' => $completed->count(),
                'notifications' => Auth::user()->notifications->take(6),
            ]);
        } else {
            $data = Vehicle::select('id', 'make', 'model', 'reg', 'type')
                ->where('vehicle_status', 1)
                ->with('manufacturer:id,name')
                ->where('show_offer', true)
                ->get();

            return view('dashboard.dashboard-broker', [

                'data' => $data,
                'live_orders' => $live_orders,
                'in_stock' => $in_stock->count(),
                'completed_orders' => $completed->count(),
                'notifications' => Auth::user()->notifications->take(6),
            ]);
        }
    }

    protected function adminDashboard()
    {
        $factory_order = $this->GetVehicleByStatus(4);
        $euro_vhc = $this->GetVehicleByStatus(10);
        $uk_vhc = $this->GetVehicleByStatus(11);
        $in_stock = $this->GetVehicleByStatus(1);
        $ready_for_delivery = $this->GetVehicleByStatus(3);
        $delivery_booked = $this->GetVehicleByStatus(6);
        $awaiting_ship = $this->GetVehicleByStatus(13);
        $converter = $this->GetVehicleByStatus(12);

        $completed = $this->GetVehicleByStatus(7);
        $live_orders = $factory_order->count() + $euro_vhc->count() + $uk_vhc->count() + $in_stock->count() + $ready_for_delivery->count() + $delivery_booked->count();



        return view('dashboard.dashboard-admin', [
            'in_stock' => $in_stock->count(),
            'ready_for_delivery' => $ready_for_delivery->count(),
            'factory_order' => $factory_order->count(),
            'completed_orders' => $completed->count(),
            'europe_vhc' => $euro_vhc->count(),
            'uk_vhc' => $uk_vhc->count(),
            'delivery_booked' => $delivery_booked->count(),
            'live_orders' => $live_orders,
            'notifications' => Auth::user()->notifications->take(6),
            'awaiting_ship' => $awaiting_ship->count(),
            'at_converter' => $converter->count(),
        ]);
    }

    public static function GetVehicleByStatus($vehicle_status, $role = null)
    {


        $vehicles = Vehicle::where('vehicle_status', $vehicle_status);

        if ( $role === 'dealer' ) {
            $vehicles->where('dealer_id', Auth::user()->company_id);
        }
        if ( $role === 'broker' ) {
            $vehicles->where('broker_id', Auth::user()->company_id);
        }

        return $vehicles->get();
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
        $user = Auth::user();

        $all_notifications = $user->notifications()->paginate(15);

        return view('notifications.index', [
            'all_notifications' => $all_notifications
        ]);
    }

    /* Delete all user notifications */
    public function executeDeleteNotifications(): RedirectResponse
    {
        Auth::user()->notifications()->delete();

        return redirect()->back();
    }

    public function readAllNotifications(): RedirectResponse
    {
        $user = Auth::user();
        $user->unreadNotifications->markAsRead();
        return redirect()->back();
    }

    public function readNotifications(DatabaseNotification $notification): RedirectResponse
    {
        $notification->markAsRead();
        return redirect()->back();
    }
    public function unreadNotifications(DatabaseNotification $notification): RedirectResponse
    {
        $notification->read_at = null;
        $notification->save();

        return redirect()->back();
    }
}
