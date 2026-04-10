<?php

namespace App\Http\Livewire\Company;

use App\Livewire\Forms\CompanyForm;
use App\Models\Company;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class CompanyCreate extends Component
{
    public CompanyForm $form;

    /**
     * @throws ValidationException
     */
    public function save() {
        $this->form->store();

        return redirect(route('company_manager'));
    }
    public function render(): Factory|View|\Illuminate\View\View
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
