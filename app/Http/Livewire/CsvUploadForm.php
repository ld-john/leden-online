<?php

namespace App\Http\Livewire;

use App\Company;
use App\Manufacturer;
use App\Vehicle;
use Carbon\Carbon;
use Illuminate\Support\LazyCollection;
use Livewire\Component;
use Livewire\WithFileUploads;

class CsvUploadForm extends Component
{
    use WithFileUploads;

    public $upload_type;
    public $upload;
    public $showInFordPipeline = 0;
    public $broker;
    public $rules = [
        'upload' => 'required|mimes:csv|size:1024'
    ];


    public function render()
    {
        return view('livewire.csv-upload-form', ['brokers' => Company::orderBy('company_name', 'asc')->where('company_type', 'broker')->get()]);
    }

    public function executeCsvUpload()
    {
        $this->validate();
        $file = $this->upload;

        dd('Hello');


    }
}
