<?php

namespace App\Http\Controllers\Vehicle;

use App\Helpers\Helper;
use App\VehicleMeta\Body;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class BodyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->view('dashboard.meta.body.index');
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
     * @param  \App\Body  $body
     * @return \Illuminate\Http\Response
     */
    public function show(Body $body)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Body  $body
     * @return \Illuminate\Http\Response
     */
    public function edit(Body $body)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Body  $body
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Body $body)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Body  $body
     *
     */
    public function destroy(Body $body)
    {
        $targetName = $body->name;

        $body->delete();

        return redirect()->route('meta.body.index')->with('successMsg', 'Body Type "' . $targetName . '" Deleted');


    }
}
