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
        return view('customer.index', [
            'title' => 'Customers',
            'active_page' => 'customers',
        ]);
    }
}
