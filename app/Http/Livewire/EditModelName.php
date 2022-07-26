<?php

namespace App\Http\Livewire;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Str;
use Livewire\Component;

class EditModelName extends Component
{
    public $model;
    public $modelName;

    public function mount()
    {
        $this->modelName = $this->model->name;
    }

    public function render(): Factory|View|Application
    {
        return view('livewire.edit-model-name');
    }

    public function saveModel()
    {
        $this->model->update([
            'name' => $this->modelName,
            'slug' => Str::slug($this->modelName),
        ]);
        session()->flash('message', 'Model Updated Successfully');
        return redirect(route('meta.make.index'));
    }

    public function deleteModel()
    {
        $this->model->delete();
        session()->flash('message', 'Model Deleted Successfully');
        return redirect(route('meta.make.index'));
    }
}
