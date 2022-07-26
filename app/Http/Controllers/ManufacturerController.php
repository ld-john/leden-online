<?php

namespace App\Http\Controllers;

use App\Manufacturer;
use App\VehicleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ManufacturerController extends Controller
{
    public function buildManufacturerTable()
    {
        $data = json_decode(
            file_get_contents(asset('storage/manufacturer-data.json')),
        );

        foreach ($data as $make) {
            $man = new Manufacturer();

            $man->name = $make->name;
            $man->slug = Str::slug($make->name, '-');
            $man->models = json_encode($make->models);

            $man->save();
        }

        dd('done');
    }

    public function vehicle_model_clean_up()
    {
        $manufacturers = Manufacturer::all();
        foreach ($manufacturers as $manufacturer) {
            foreach (json_decode($manufacturer->models) as $model) {
                $new_model = new VehicleModel();
                $new_model->name = $model;
                $new_model->slug = Str::slug($model);
                $new_model->manufacturer_id = $manufacturer->id;
                $new_model->save();
                var_dump($model . ' Done');
            }
        }
    }
}
