<?php

namespace App\Http\Controllers;

use App\Customer;
use App\OrderLegacy;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('customer.index', ['title' => 'Customers', 'active_page' => 'customers']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        //
    }

    public function buildNewCustomer()
    {

        $orders = OrderLegacy::withTrashed()->get();
        //$order = Order::where('id', $orderId)->withTrashed()->firstOrFail();

        foreach( $orders as $order ) {

            if (empty($order->customer_name) && empty($order->company_name)) {

            } else {

                $customer = new Customer();

                $customer->customer_name = $order->customer_name;
                $customer->company_name = $order->company_name;
                $customer->preferred_name = $order->preferred_name;

                $customer->save();
            }
        }

    }
    public function name_cleaner()
    {
        Customer::chunk(100, function($customers) {
            foreach ($customers as $customer) {
                if ($customer->preferred_name === 'company') {
                    $customer->customer_name = $customer->company_name;
                    $customer->save();
                }
                if (!$customer->customer_name) {
                    $customer->customer_name = $customer->company_name;
                    $customer->save();
                }
            }
            var_dump('done');
        });
    }
}
