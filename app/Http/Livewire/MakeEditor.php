<?php

namespace App\Http\Livewire;

use App\Manufacturer;
use App\VehicleModel;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Component;

class MakeEditor extends Component
{
    public $makes;
    public $newMakeName;
    public $newModelMake;
    public $newModelName;

    public function mount()
    {
        $this->makes = Manufacturer::all();
    }

    public function newMake()
    {
        Manufacturer::create([
            'name' => $this->newMakeName,
            'slug' => Str::slug($this->newMakeName),
        ]);
        notify()->success('Make added successfully', 'Make Added');
        $this->resetInput();
        return redirect(route('meta.make.index'));
    }

    public function newModel()
    {
        $vehicle_model = new VehicleModel();
        $vehicle_model->name = $this->newModelName;
        $vehicle_model->slug = Str::slug($this->newModelName);
        $vehicle_model->manufacturer_id = $this->newModelMake;
        $vehicle_model->save();
        notify()->success('Model Added Successfully', 'Model Added');
        $this->resetInput();
        return redirect(route('meta.make.index'));
    }

    public function render(): Factory|View|Application
    {
        return view('livewire.make-editor');
    }

    public function resetInput()
    {
        $this->newModelName = '';
        $this->newModelMake = '';
        $this->newMakeName = '';
    }
}
