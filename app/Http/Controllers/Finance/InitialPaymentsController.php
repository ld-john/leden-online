<?php

namespace App\Http\Controllers\Finance;

use App\Finance\InitialPayment;
use App\Http\Controllers\Controller;
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

    public function show(InitialPayment $initialPayment)
    {
    }

    public function edit(InitialPayment $initialPayment)
    {
    }

    public function update(Request $request, InitialPayment $initialPayment)
    {
    }

    public function destroy(InitialPayment $initialPayment)
    {
    }
}
