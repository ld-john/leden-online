<?php

namespace App\Http\Livewire;

use App\Models\VehicleMeta;
use App\Models\VehicleModel;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class MetaEditor extends Component
{
    public $metatype;
    public $universal;
    public $metadata;
    public $new_name;
    public $editModel = false;
    public $edit_meta_name;
    public $edit_meta_models;
    public $models;
    public $edit_meta_id = null;

    public function mount()
    {
        $this->metadata = VehicleMeta::where('type', $this->metatype)
            ->with('vehicle_model')
            ->get();
        $this->models = VehicleModel::orderBy('name')->get();
    }

    public function newVehicleMeta()
    {
        $meta = new VehicleMeta();
        $meta->name = $this->new_name;
        $meta->type = $this->metatype;
        $meta->save();
        return redirect()->route('meta.' . $this->metatype . '.index');
    }

    public function showEditMetaModal(VehicleMeta $meta)
    {
        $this->edit_meta_name = $meta->name;
        $this->edit_meta_models = $meta->vehicle_model->pluck('id')->toArray();
        $this->edit_meta_id = $meta->id;
        $this->editModel = true;
    }

    public function hideModal()
    {
        $this->editModel = false;
    }

    public function saveMeta(VehicleMeta $meta)
    {
        $meta->name = $this->edit_meta_name;
        $meta->vehicle_model()->sync($this->edit_meta_models);
        notify()->success(ucfirst($this->metatype) . ' Edited Successfully');
        $this->hideModal();
        return redirect()->route('meta.' . $this->metatype . '.index');
    }

    public function delete(VehicleMeta $meta)
    {
        $meta->delete();
        notify()->success(
            ucfirst($this->metatype) . ' Deleted Successfully',
            ucfirst($this->metatype) . ' Deleted',
        );
        return redirect()->route('meta.' . $this->metatype . '.index');
    }

    public function render(): Factory|View|Application
    {
        return view('livewire.meta-editor');
    }
}
