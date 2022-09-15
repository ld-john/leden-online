<?php

namespace App\Http\Controllers;

class VehicleMetaController extends Controller
{
    public function colourIndex()
    {
        return view('dashboard.meta.colour.index');
    }
    public function derivativeIndex()
    {
        return view('dashboard.meta.derivative.index');
    }
    public function engineIndex()
    {
        return view('dashboard.meta.engine.index');
    }
    public function fuelIndex()
    {
        return view('dashboard.meta.fuel.index');
    }
    public function transmissionIndex()
    {
        return view('dashboard.meta.transmission.index');
    }
    public function trimIndex()
    {
        return view('dashboard.meta.trim.index');
    }
    public function typeIndex()
    {
        return view('dashboard.meta.type.index');
    }
    public function makeIndex()
    {
        return view('dashboard.meta.make.index');
    }
}
