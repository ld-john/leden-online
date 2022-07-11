<?php

namespace App\Http\Livewire;

use App\Manufacturer;
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
        session()->flash('message', 'Make Added Successfully');
        $this->resetInput();
        return redirect(route('meta.make.index'));
    }

    public function newModel()
    {
        $make = Manufacturer::where('id', $this->newModelMake)->first();
        if ($make->models) {
            $models = json_decode($make->models);
            $models[] = $this->newModelName;
            $make->models = json_encode($models);
        } else {
            $make->models = json_encode([$this->newModelName]);
        }
        $make->save();
        session()->flash('message', 'Model Added Successfully');
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
