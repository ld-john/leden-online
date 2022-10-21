<?php

namespace App\Http\Controllers\Finance;

use App\Finance\Maintenance;
use App\Http\Controllers\Controller;
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

    public function edit(Maintenance $maintenance)
    {
    }

    public function update(Request $request, Maintenance $maintenance)
    {
    }

    public function destroy(Maintenance $maintenance)
    {
    }
}
