<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Finance\InitialPayment;
use Illuminate\Http\Request;

class InitialPaymentsController extends Controller
{
    public function index()
    {
        return view('finance.initial-payments.index');
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
    }

    public function show(\App\Models\Finance\InitialPayment $initialPayment)
    {
    }

    public function edit(\App\Models\Finance\InitialPayment $initialPayment)
    {
    }

    public function update(Request $request, \App\Models\Finance\InitialPayment $initialPayment)
    {
    }

    public function destroy(InitialPayment $initialPayment)
    {
    }
}
