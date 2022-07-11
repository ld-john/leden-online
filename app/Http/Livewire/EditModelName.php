<?php

namespace App\Http\Livewire;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Routing\Redirector;
use Livewire\Component;

class EditModelName extends Component
{
    public $model;
    public $make;
    public $loop;

    public function render(): Factory|View|Application
    {
        return view('livewire.edit-model-name');
    }

    public function saveModel()
    {
        $models = json_decode($this->make->models);
        $models[$this->loop] = $this->model;
        $this->make->models = json_encode($models);
        $this->make->save();
        session()->flash('message', 'Model Updated Successfully');
        return redirect(route('meta.make.index'));
    }

    public function deleteModel()
    {
        $models = json_decode($this->make->models);
        array_splice($models, $this->loop, 1);
        $this->make->models = json_encode($models);
        $this->make->save();
        session()->flash('message', 'Model Deleted Successfully');
        return redirect(route('meta.make.index'));
    }
}
