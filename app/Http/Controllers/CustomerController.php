<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Cassandra\Custom;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('customer.index', [
            'title' => 'Customers',
            'active_page' => 'customers',
        ]);
    }

    public function recycle()
    {
        $customers = Customer::onlyTrashed()
            ->latest()
            ->paginate('10');
        return view('customer.recycleBin', [
            'title' => 'Recycle Bin',
            'active_page' => 'customer-recycle-bin',
            'customers' => $customers,
        ]);
    }

    public function forceDelete(Customer $customer)
    {
        $customer->forceDelete();

        notify()->success(
            'Customer removed from successfully',
            'Customer Deleted',
        );
        return to_route('customer.recycle-bin');
    }

    public function restore(Customer $customer)
    {
        $customer->restore();

        notify()->success(
            'Customer restored successfully',
            'Customer Restored',
        );
        return to_route('customer.recycle-bin');
    }
}
