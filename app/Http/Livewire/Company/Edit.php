<?php

namespace App\Http\Livewire\Company;

use App\Livewire\Forms\CompanyForm;
use App\Models\Company;
use Livewire\Component;

class Edit extends Component
{
    public CompanyForm $form;

    public function mount (Company $company) {
        $this->form->setCompany($company);
    }

    public function save() {
        $this->form->store();

        return redirect(route('company_manager'));
    }
    public function render()
    {
        return view('livewire.company.company-form', [
            'companyTypes' => [
                ['id' => 'broker',       'name' => 'Broker'],
                ['id' => 'dealer',       'name' => 'Dealer'],
                ['id' => 'registration', 'name' => 'Registration'],
                ['id' => 'invoice',      'name' => 'Invoice'],
                ['id' => 'delivery',     'name' => 'Delivery Agent'],
            ]
        ]);
    }
}
