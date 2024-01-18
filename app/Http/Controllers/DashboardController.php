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
        $statuses = $this->GetAllVehiclesByStatus(Auth::user()->role);
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

        if (Auth::user()->role == 'admin') {
            return $this->adminDashboard();
        } elseif (Auth::user()->role === 'dealer') {
            return view('dashboard.dashboard-dealer', [
                'live_orders' => $live_orders,
                'vehicle_statuses' => $statuses,
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
                'vehicle_statuses' => $statuses,
            ]);
        }
    }

    protected function adminDashboard(): Factory|View|Application
    {
        $statuses = $this->GetAllVehiclesByStatus();

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
                return ['label' => $key . ' - ' . $item, 'value' => $item];
            });

        return view('dashboard.dashboard-admin', [
            'vehicle_statuses' => $statuses,
            'pie_labels' => $pie_labels,
            'live_orders' => $live_orders,
        ]);
    }

    public function GetAllVehiclesByStatus($role = 'admin')
    {
        $vehicles = collect([]);
        if ($role === 'admin') {
            $vehicles = collect(Vehicle::all());
        } elseif ($role === 'broker') {
            $vehicles = collect(
                Vehicle::where('broker_id', Auth::user()->company_id)->get(),
            );
        } elseif ($role === 'dealer') {
            $vehicles = collect(
                Vehicle::where('dealer_id', Auth::user()->company_id)->get(),
            );
        }

        $vehicle_statuses = $vehicles->groupBy('vehicle_status')->all();

        $statuses = collect(Vehicle::statusList())
            ->mapWithKeys(function ($item, $key) {
                return [$item => 0];
            })
            ->toArray();

        foreach ($vehicle_statuses as $key => $status) {
            $statuses[Vehicle::statusMatch($key)] = $status->count();
        }

        return $statuses;
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

    /* Show all notification page */
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
