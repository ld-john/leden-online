<?php

namespace App\Http\Controllers;

use App\Order;
use App\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function show(Vehicle $vehicle)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function edit(Vehicle $vehicle)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vehicle $vehicle)
    {
        //
    }

    public function buildNewVehicle()
    {

        $orders = Order::withTrashed()->get();

        foreach ( $orders as $order ) {

            $vehicle = new Vehicle();

            $vehicle->vehicle_status = $order->vehicle_status;
            $vehicle->reg = $order->vehicle_reg;
            $vehicle->model_year = $order->model_year;
            $vehicle->make = $order->vehicle_make;
            $vehicle->model = $order->vehicle_model;
            $vehicle->derivative = $order->vehicle_derivative;
            $vehicle->engine = $order->vehicle_engine;
            $vehicle->transmission = $order->vehicle_trans;
            $vehicle->fuel_type = $order->vehicle_fuel_type;
            $vehicle->doors = $order->vehicle_doors;
            $vehicle->colour = $order->vehicle_colour;
            $vehicle->body = $order->vehicle_body;
            $vehicle->trim = $order->vehicle_trim;
            $vehicle->chassis_prefix = $order->chassis_prefix;
            $vehicle->chassis = $order->chassis;
            $vehicle->type = $order->vehicle_type;
            $vehicle->metallic_paint = $order->metallic_paint;
            $vehicle->list_price = $order->list_price;
            $vehicle->first_reg_fee = $order->first_reg_fee;
            $vehicle->rfl_cost = $order->rfl_cost;
            $vehicle->onward_delivery = $order->onward_delivery;
            $vehicle->vehicle_registered_on = $order->vehicle_registered_on;
            $vehicle->hide_from_broker = $order->hide_from_broker;
            $vehicle->hide_from_dealer = $order->hide_from_dealer;
            $vehicle->show_in_ford_pipeline = $order->show_in_ford_pipeline;
            $vehicle->deleted_at = $order->deleted_at;

            $vehicle->save();

        }

        dd( 'Done' );

    }
}
