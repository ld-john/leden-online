<?php

namespace App\Http\Livewire;

use App\VehicleMeta\Colour;
use Livewire\Component;

class MetaEditor extends Component
{
//    public $metatype;
//    public $metadata;
//
//    protected $rules = [
//        'metadata.*.name' => 'required|string|min:6',
//    ];
//
//
//    public function mount(){
//        //$namespace = '\\App\\VehicleMeta\\';
//
//        //$items = $namespace . $this->metatype;
//
//        $this->metadata = Body::all();
//    }



    public $posts;
    public function mount()
    {
        $this->posts = Colour::all();
    }
    protected $rules = [
        'posts.*.name' => 'required|string|min:6',
    ];
    public function render()
    {
        return view('livewire.meta-editor');
    }

}
