<?php

namespace App\Http\Controllers;

use App\Vehicle;
use App\VehicleMeta;
use App\VehicleModel;
use Illuminate\Http\Request;

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

    public function clean()
    {
        $types = VehicleMeta\Type::all();
        foreach ($types as $type) {
            $meta = new VehicleMeta();
            $meta->name = $type->name;
            $meta->type = 'type';
            $meta->save();
            var_dump($type->name);
        }
        $colours = VehicleMeta\Colour::all();
        foreach ($colours as $colour) {
            $meta = new VehicleMeta();
            $meta->name = $colour->name;
            $meta->type = 'colour';
            $meta->save();
            var_dump($colour->name);
        }
        $derivatives = VehicleMeta\Derivative::all();
        foreach ($derivatives as $derivative) {
            $meta = new VehicleMeta();
            $meta->name = $derivative->name;
            $meta->type = 'derivative';
            $meta->save();
            var_dump($derivative->name);
        }

        $engines = VehicleMeta\Engine::all();
        foreach ($engines as $engine) {
            $meta = new VehicleMeta();
            $meta->name = $engine->name;
            $meta->type = 'engine';
            $meta->save();
            var_dump($engine->name);
        }
        $fuels = VehicleMeta\Fuel::all();
        foreach ($fuels as $fuel) {
            $meta = new VehicleMeta();
            $meta->name = $fuel->name;
            $meta->type = 'fuel';
            $meta->save();
            var_dump($fuel->name);
        }
        $transmissions = VehicleMeta\Transmission::all();
        foreach ($transmissions as $transmission) {
            $meta = new VehicleMeta();
            $meta->name = $transmission->name;
            $meta->type = 'transmission';
            $meta->save();
            var_dump($transmission->name);
        }
        $trims = VehicleMeta\Trim::all();
        foreach ($trims as $trim) {
            $meta = new VehicleMeta();
            $meta->name = $trim->name;
            $meta->type = 'trim';
            $meta->save();
            var_dump($trim->name);
        }
    }
    public function addon()
    {
        set_time_limit(300);
        foreach (Vehicle::lazy() as $vehicle) {
            $model = VehicleModel::where('name', $vehicle->model)->first();
            $type = VehicleMeta::where('name', $vehicle->type)->first();
            $colour = VehicleMeta::where('name', $vehicle->colour)->first();
            $derivative = VehicleMeta::where(
                'name',
                $vehicle->derivative,
            )->first();
            $engines = VehicleMeta::where('name', $vehicle->engine)->first();
            $fuel = VehicleMeta::where('name', $vehicle->fuel_type)->first();
            $transmission = VehicleMeta::where(
                'name',
                $vehicle->transmission,
            )->first();
            $trim = VehicleMeta::where('name', $vehicle->trim)->first();
            if ($model) {
                if ($type) {
                    $type->vehicle_model()->syncWithoutDetaching($model->id);
                    var_dump('done');
                } else {
                    var_dump('Type not found');
                }
                if ($colour) {
                    $colour->vehicle_model()->syncWithoutDetaching($model->id);
                    var_dump('done');
                } else {
                    var_dump('Colour not found');
                }
                if ($derivative) {
                    $derivative
                        ->vehicle_model()
                        ->syncWithoutDetaching($model->id);
                    var_dump('done');
                } else {
                    var_dump('Derivative not found');
                }
                if ($engines) {
                    $engines->vehicle_model()->syncWithoutDetaching($model->id);
                    var_dump('done');
                } else {
                    var_dump('Engine not found');
                }
                if ($fuel) {
                    $fuel->vehicle_model()->syncWithoutDetaching($model->id);
                    var_dump('done');
                } else {
                    var_dump('Fuel Type not found');
                }
                if ($transmission) {
                    $transmission
                        ->vehicle_model()
                        ->syncWithoutDetaching($model->id);
                    var_dump('done');
                } else {
                    var_dump('Tranmission not found');
                }
                if ($trim) {
                    $trim->vehicle_model()->syncWithoutDetaching($model->id);
                    var_dump('done');
                } else {
                    var_dump('Trim not found');
                }
            } else {
                var_dump('Something weird happened');
            }
        }
    }
}
