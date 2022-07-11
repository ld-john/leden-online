<?php

namespace App\Http\Livewire;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Str;
use Livewire\Component;

class MetaEditor extends Component
{
    public $metatype;
    public $metadata;
    public $newname;

    protected $namespace = '\\App\\VehicleMeta\\';

    protected $rules = [
        'metadata.*.name' => 'required',
    ];

    public function mount()
    {
        $items = $this->namespace . $this->metatype;
        $this->metadata = $items::all();
    }

    public function new(): Redirector|Application|RedirectResponse
    {
        $model = $this->namespace . $this->metatype;

        $meta = new $model();

        $meta->name = $this->newname;
        $meta->save();

        session()->flash(
            'successMsg',
            Str::plural($this->metatype) .
                ' entry : "' .
                $meta->name .
                '" [id: ' .
                $meta->id .
                '] added',
        );
        return redirect(
            route('meta.' . strtolower($this->metatype) . '.index'),
        );
    }

    public function removeEntry(
        $id,
        $name,
    ): Redirector|Application|RedirectResponse {
        $model = $this->namespace . $this->metatype;

        $model::destroy($id);

        session()->flash(
            'successMsg',
            Str::plural($this->metatype) .
                ' entry : "' .
                $name .
                '" [id: ' .
                $id .
                '] deleted',
        );
        return redirect(
            route('meta.' . strtolower($this->metatype) . '.index'),
        );
    }

    public function save(): Redirector|Application|RedirectResponse
    {
        $this->validate();

        foreach ($this->metadata as $meta) {
            $meta->update();
        }

        session()->flash(
            'successMsg',
            Str::plural($this->metatype) . ' updated!',
        );
        return redirect(
            route('meta.' . strtolower($this->metatype) . '.index'),
        );
    }

    public function render(): Factory|View|Application
    {
        return view('livewire.meta-editor');
    }
}
