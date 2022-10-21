<?php

namespace App\Http\Controllers\Finance;

use App\Finance\FinanceType;
use App\Http\Controllers\Controller;
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

    public function show(FinanceType $financeType)
    {
    }

    public function edit(FinanceType $financeType)
    {
    }

    public function update(Request $request, FinanceType $financeType)
    {
    }

    public function destroy(FinanceType $financeType)
    {
    }
}
