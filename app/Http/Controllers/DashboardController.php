<?php

namespace App\Http\Controllers;

use App\Enums\VehicleStatus;
use App\Mail\LoginRequest;
use App\Models\Order;
use App\Models\Updates;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
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
        $role = Auth::user()->role;
        $statuses = $this->GetAllVehiclesByStatus($role);
        $live_orders = collect(VehicleStatus::orderBankStatuses())->sum(fn($status) => $statuses[$status->label() ?? 0]);

        return match ($role) {
            'admin' => $this->renderAdminDashboard($statuses, $live_orders),
            'dealer' => view('dashboard.dashboard-dealer', compact('live_orders', 'statuses')),
            default => $this->renderBrokerDashboard($statuses, $live_orders)
        };

    }

    private function renderAdminDashboard($statuses, $live_orders)
    {
        $pie_labels = collect($statuses)->except([VehicleStatus::COMPLETED_ORDERS->label()])->map(fn($item, $key) => ['label' => "$key - $item", 'value' => $item]);

        return view('dashboard.dashboard-admin', [
            'vehicle_statuses' => $statuses,
            'pie_labels' => $pie_labels,
            'live_orders' => $live_orders
        ]);
    }

    private function renderBrokerDashboard($statuses, $live_orders)
    {
        $data = Vehicle::query()
            ->where('vehicle_status', VehicleStatus::IN_STOCK)
            ->where('show_offer', true)
            ->with('manufacturer.name')
            ->select(['id', 'make', 'model', 'reg', 'type'])
            ->get();

        $updates = Updates::where('dashboard', 'broker')->get()->groupBy('update_type');

        return view('dashboard.dashboard-broker', [
            'updates' => $updates->get('update', collect()),
            'banners' => $updates->get('promo', collect()),
            'data' => $data,
            'live_orders' => $live_orders,
            'vehicle_statuses' => $statuses,
        ]);

    }

    public function GetAllVehiclesByStatus($role = 'admin')
    {
        $query = Vehicle::query();

        $query->when($role === 'broker', fn($q) => $q->where('broker_id', Auth::user()->company_id))
            ->when($role === 'dealer', fn($q) => $q->where('dealer_id', Auth::user()->company_id));

        $counts = $query->selectRaw('vehicle_status, count(*) as total')->groupBy('vehicle_status')->pluck('total', 'vehicle_status');

        return collect(VehicleStatus::cases())->mapWithKeys(function($status) use ($counts) {
            return [
                $status->label() => $counts->get($status->value, 0)
            ];
        })->toArray();

    }

    public static function GetOrdersByVehicleStatus($status)
    {
        $user = Auth::user();

        // Ensure we are working with an Enum instance even if an ID was passed
        $statusEnum = $status instanceof VehicleStatus
            ? $status
            : VehicleStatus::tryFrom($status);

        return Order::query()
            // 1. Filter by Vehicle Status via Relationship
            ->whereHas('vehicle', fn($q) => $q->where('vehicle_status', $statusEnum))

            // 2. Apply Role-based scoping (Brokers/Dealers see only their own)
            ->when($user->role !== 'admin', function ($query) use ($user) {
                $foreignKey = "{$user->role}_id"; // e.g., broker_id or dealer_id
                return $query->where($foreignKey, $user->company_id);
            })

            ->count();
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
