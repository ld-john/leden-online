<?php

namespace App\Http\Controllers\Vehicle;

use App\Transmission;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class TransmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return response()->view('dashboard.meta.transmission.index');
    }
}
