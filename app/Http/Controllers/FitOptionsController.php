<?php

namespace App\Http\Controllers;

use App\FitOption;
use App\VehicleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FitOptionsController extends Controller
{
    public function factoryFitIndex()
    {
        return response()->view('dashboard.meta.fitoptions.factoryfit.index');
    }
    public function dealerFitIndex()
    {
        return response()->view('dashboard.meta.fitoptions.dealerfit.index');
    }
}
