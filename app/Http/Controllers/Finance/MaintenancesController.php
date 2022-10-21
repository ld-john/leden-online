<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Finance\Maintenance;
use Illuminate\Http\Request;

class MaintenancesController extends Controller
{
    public function index()
    {
        return view('finance.maintenance.index');
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
    }

    public function show(Maintenance $maintenance)
    {
    }

    public function edit(\App\Models\Finance\Maintenance $maintenance)
    {
    }

    public function update(Request $request, Maintenance $maintenance)
    {
    }

    public function destroy(Maintenance $maintenance)
    {
    }
}
