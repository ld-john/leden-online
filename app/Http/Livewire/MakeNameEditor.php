<?php

namespace App\Http\Livewire;

use App\Manufacturer;
use App\VehicleModel;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class MakeNameEditor extends Component
{
    public $make;
    public $makeName;
    public $vehicle_models;
    public $deleteModalShow = false;
    public $editModalShow = false;

    public function mount()
    {
        $this->makeName = $this->make->name;
        $this->vehicle_models = VehicleModel::where(
            'manufacturer_id',
            $this->make->id,
        )
            ->orderBy('name')
            ->get();
    }

    public function render(): Factory|View|Application
    {
        return view('livewire.make-name-editor');
    }
    public function saveMakeModal()
    {
        $this->editModalShow = true;
    }
    public function saveMake()
    {
        $this->make->name = $this->makeName;
        $this->make->save();
        session()->flash('message', 'Make Updated Successfully');
        return redirect(route('meta.make.index'));
    }
    public function deleteMake()
    {
        $this->deleteModalShow = true;
    }
    public function toggleDeleteModal()
    {
        $this->deleteModalShow = !$this->deleteModalShow;
    }
    public function toggleEditModal()
    {
        $this->editModalShow = !$this->editModalShow;
    }
    public function destroyMake()
    {
        Manufacturer::destroy($this->make->id);
        session()->flash('message', 'Make Removed Successful');
        return redirect(route('meta.make.index'));
    }
}
