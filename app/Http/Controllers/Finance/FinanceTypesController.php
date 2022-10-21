<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Finance\FinanceType;
use Illuminate\Http\Request;

class FinanceTypesController extends Controller
{
    public function index()
    {
        return view('finance.finance-type.index');
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
    }

    public function show(\App\Models\Finance\FinanceType $financeType)
    {
    }

    public function edit(\App\Models\Finance\FinanceType $financeType)
    {
    }

    public function update(Request $request, FinanceType $financeType)
    {
    }

    public function destroy(\App\Models\Finance\FinanceType $financeType)
    {
    }
}
