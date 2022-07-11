<?php

namespace App\Http\Controllers\Vehicle;

use App\Http\Controllers\Controller;

class MakeController extends Controller
{
    public function index()
    {
        return response()->view('dashboard.meta.make.index');
    }
}
