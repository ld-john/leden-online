<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationsController extends Controller
{
    public function index()
    {
    }

    public function create()
    {
        return view('locations.create');
    }

    public function store(Request $request)
    {
    }

    public function show(Location $location)
    {
    }

    public function edit(Location $location)
    {
    }

    public function update(Request $request, Location $location)
    {
    }

    public function destroy(Location $location)
    {
    }
}
