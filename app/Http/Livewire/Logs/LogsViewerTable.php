<?php

namespace App\Http\Livewire\Logs;

use Livewire\Component;

class LogsViewerTable extends Component
{
    public $standardFormat;
    public $logs;

    public function mount($standardFormat, $logs) {
        $this->standardFormat = $standardFormat;
        $this->logs = $logs;
    }
    public function render()
    {
        return view('livewire.logs.logs-viewer-table');
    }
}
