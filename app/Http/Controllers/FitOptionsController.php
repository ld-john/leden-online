<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
