<?php

namespace App\Http\Controllers;

use App\Mail\LoginRequest;
use App\Models\Order;
use App\Models\Updates;
use App\Models\User;
use App\Models\Vehicle;
use Faker\Provider\Address;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Mail;
use ReflectionException;

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
    public function index(): Renderable
    {
        $factory_order = $this->GetVehicleByStatus(4, Auth::user()->role);
        $euro_vhc = $this->GetVehicleByStatus(10, Auth::user()->role);
        $uk_vhc = $this->GetVehicleByStatus(11, Auth::user()->role);
        $in_stock = $this->GetVehicleByStatus([1, 15, 17], Auth::user()->role);
        $ready_for_delivery = $this->GetVehicleByStatus(3, Auth::user()->role);
        $delivery_booked = $this->GetVehicleByStatus(6, Auth::user()->role);
        $completed = $this->GetVehicleByStatus(7, Auth::user()->role);
        $awaiting_delivery_confirmation = $this->GetVehicleByStatus(
            5,
            Auth::user()->role,
        );
        $live_orders =
            $factory_order->count() +
            $euro_vhc->count() +
            $uk_vhc->count() +
            $in_stock->count() +
            $ready_for_delivery->count() +
            $delivery_booked->count() +
            $awaiting_delivery_confirmation->count();

        if (Auth::user()->role == 'admin') {
            return $this->adminDashboard();
        } elseif (Auth::user()->role === 'dealer') {
            return view('dashboard.dashboard-dealer', [
                'live_orders' => $live_orders,
                'in_stock' => $in_stock->count(),
                'completed_orders' => $completed->count(),
            ]);
        } else {
            $data = Vehicle::where('vehicle_status', 1)
                ->select(['id', 'make', 'model', 'reg', 'type'])
                ->with('manufacturer:id,name')
                ->where('show_offer', true)
                ->get();

            $updates = Updates::where('dashboard', '=', 'broker')
                ->where('update_type', '=', 'update')
                ->get();

            $banners = Updates::where('dashboard', '=', 'broker')
                ->where('update_type', '=', 'promo')
                ->get();

            return view('dashboard.dashboard-broker', [
                'updates' => $updates,
                'banners' => $banners,
                'data' => $data,
                'live_orders' => $live_orders,
                'in_stock' => $in_stock->count(),
                'completed_orders' => $completed->count(),
            ]);
        }
    }

    protected function adminDashboard(): Factory|View|Application
    {
        $vehicles = collect(Vehicle::all());

        $vehicle_statuses = $vehicles->groupBy('vehicle_status')->all();

        $statuses = collect(Vehicle::statusList())
            ->mapWithKeys(function ($item, $key) {
                return [$item => 0];
            })
            ->toArray();

        foreach ($vehicle_statuses as $key => $status) {
            $statuses[Vehicle::statusMatch($key)] = $status->count();
        }

        $factory_order = $this->GetVehicleByStatus(4);
        $euro_vhc = $this->GetVehicleByStatus(10);
        $uk_vhc = $this->GetVehicleByStatus(11);
        $in_stock = $this->GetVehicleByStatus(1);
        $ready_for_delivery = $this->GetVehicleByStatus(3);
        $delivery_booked = $this->GetVehicleByStatus(6);
        $awaiting_ship = $this->GetVehicleByStatus(13);
        $converter = $this->GetVehicleByStatus(12);
        $damaged = $this->getVehicleByStatus(16);
        $dealer_transfer = $this->getVehicleByStatus(18);

        $completed = $this->GetVehicleByStatus(7);
        $live_orders =
            $statuses['Factory Order'] +
            $statuses['Europe VHC'] +
            $statuses['UK VHC'] +
            $statuses['In Stock'] +
            $statuses['In Stock (Registered)'] +
            $statuses['In Stock (Awaiting Dealer Options)'] +
            $statuses['Ready for Delivery'] +
            $statuses['Delivery Booked'] +
            $statuses['Awaiting Ship'] +
            $statuses['At Converter'] +
            $statuses['Dealer Transfer'];

        $pie_labels = collect($statuses)
            ->except(['Completed Orders'])
            ->map(function ($item, $key) {
                return $key . ' - ' . $item;
            });

        return view('dashboard.dashboard-admin', [
            'vehicle_statuses' => $statuses,
            'pie_labels' => $pie_labels,
            'in_stock' => $in_stock->count(),
            'ready_for_delivery' => $ready_for_delivery->count(),
            'factory_order' => $factory_order->count(),
            'completed_orders' => $completed->count(),
            'europe_vhc' => $euro_vhc->count(),
            'uk_vhc' => $uk_vhc->count(),
            'delivery_booked' => $delivery_booked->count(),
            'live_orders' => $live_orders,
            'awaiting_ship' => $awaiting_ship->count(),
            'at_converter' => $converter->count(),
            'damaged' => $damaged->count(),
            'dealer_transfer' => $dealer_transfer->count(),
        ]);
    }

    public static function GetVehicleByStatus($vehicle_status, $role = null)
    {
        $vehicle_status = Arr::wrap($vehicle_status);
        $vehicles = Vehicle::whereIn('vehicle_status', $vehicle_status);

        if ($role === 'dealer') {
            $vehicles->where('dealer_id', Auth::user()->company_id);
        }
        if ($role === 'broker') {
            $vehicles->where('broker_id', Auth::user()->company_id);
        }

        return $vehicles->get();
    }

    public static function GetOrdersByVehicleStatus($status)
    {
        if (Auth::user()->role != 'admin') {
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
    public function showNotifications()
    {
        $user = Auth::user();

        $all_notifications = $user->notifications()->paginate(15);

        return view('notifications.index', [
            'all_notifications' => $all_notifications,
        ]);
    }

    /* Delete all user notifications */
    public function executeDeleteNotifications(): RedirectResponse
    {
        Auth::user()
            ->notifications()
            ->delete();

        return redirect()->back();
    }

    public function readAllNotifications(): RedirectResponse
    {
        $user = Auth::user();
        $user->unreadNotifications->markAsRead();
        return redirect()->back();
    }

    public function readNotifications(
        DatabaseNotification $notification,
    ): RedirectResponse {
        $notification->markAsRead();
        return redirect()->back();
    }
    public function unreadNotifications(
        DatabaseNotification $notification,
    ): RedirectResponse {
        $notification->read_at = null;
        $notification->save();

        return redirect()->back();
    }

    public function requestLogin()
    {
        return view('dashboard.request-login');
    }

    /**
     * @throws ReflectionException
     */
    public function sendRequest(Request $request)
    {
        $user = (new User())->forceFill([
            'name' => 'Availabilities',
            'email' => 'availabilities@leden.co.uk ',
        ]);
        Mail::to($user)->send(new LoginRequest($request));
        notify()->success('Login Request Submitted');
        return redirect('/');
    }
}
