<?php

namespace App\Http\Livewire\Finance;

use App\Models\Finance\Maintenance;
use Livewire\Component;

class MaintenanceEditor extends Component
{
    public $metadata;
    public $new_name;
    public $edit;
    public $edit_name;

    public function mount()
    {
        $this->metadata = Maintenance::all();
    }

    public function newMaintenance()
    {
        $financeType = new Maintenance();
        $financeType->option = $this->new_name;
        $financeType->save();
        Notify()->success('Maintenance added successfully');
        return redirect(route('finance.maintenance.index'));
    }

    public function showEditModal(Maintenance $type)
    {
        $this->edit = $type;
        $this->edit_name = $type->option;
    }

    public function delete(\App\Models\Finance\Maintenance $type)
    {
        $type->delete();
        Notify()->success('Maintenance deleted successfully');
        return redirect(route('finance.maintenance.index'));
    }

    public function saveMaintenance()
    {
        $maintenance = $this->edit;
        $maintenance->update([
            'option' => $this->edit_name,
        ]);
        Notify()->success('Maintenance Edited Successfully');
        return redirect(route('finance.maintenance.index'));
    }
    public function render()
    {
        return view('livewire.finance.maintenance-editor');
    }
}
