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

    public function fitOptionsClean()
    {
        $fitOptions = FitOption::all();
        foreach ($fitOptions as $fitOption) {
            if ($fitOption->model) {
                $model = VehicleModel::where(
                    'name',
                    $fitOption->model,
                )->first();
                if (!$model) {
                    $model = new VehicleModel();
                    $model->name = $fitOption->model;
                    $model->slug = Str::slug($fitOption->model);
                    $model->save();
                }
                $fitOption->update([
                    'model' => $model->id,
                ]);
                var_dump('Done');
            }
        }
    }
}
