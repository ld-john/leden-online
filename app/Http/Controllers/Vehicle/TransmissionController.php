<?php

namespace App\Http\Controllers\Vehicle;

use App\Transmission;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->view('dashboard.meta.transmission.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Transmission  $transmission
     * @return \Illuminate\Http\Response
     */
    public function show(Transmission $transmission)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Transmission  $transmission
     * @return \Illuminate\Http\Response
     */
    public function edit(Transmission $transmission)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Transmission  $transmission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transmission $transmission)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Transmission  $transmission
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transmission $transmission)
    {
        //
    }
}
