<?php

namespace App\Livewire\Forms;

use App\Models\Company;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Validate;
use Livewire\Form;

class CompanyForm extends Form
{
    public ?Company $company = null;

    #[Validate(['required'])]
    public string $company_name = '';

    #[Validate(['required'])]
    public string $company_address1 = '';

    #[Validate(['nullable'])]
    public ?string $company_address2 = '';

    #[Validate(['required'])]
    public string $company_city = '';

    #[Validate(['nullable'])]
    public ?string $company_county = '';

    #[Validate(['nullable'])]
    public ?string $company_country = '';

    #[Validate(['required'])]
    public string $company_postcode = '';

    #[Validate(['nullable', 'email', 'max:254'])]
    public ?string $company_email = '';

    #[Validate(['nullable'])]
    public ?string $company_phone = '';

    #[Validate(['required'])]
    public string $company_type = '';

    #[Validate(['boolean'])]
    public string $fleet_procure_member = '';

    public function setCompany(Company $company): void
    {
        $this->company = $company;
        $this->company_name = $company->company_name;
        $this->company_address1 = $company->company_address1;
        $this->company_address2 = $company->company_address2;
        $this->company_city = $company->company_city;
        $this->company_county = $company->company_county;
        $this->company_country = $company->company_country;
        $this->company_postcode = $company->company_postcode;
        $this->company_email = $company->company_email;
        $this->company_phone = $company->company_phone;
        $this->company_type = $company->company_type;
        $this->fleet_procure_member = $company->fleet_procure_member;
    }

    /**
     * @throws ValidationException
     */
    public function store(): void
    {
        $this->validate();

        Company::updateOrCreate(['id' => $this->company?->id],
            $this->only([
            'company_name',
            'company_type',
            'company_address1',
            'company_address2',
            'company_city',
            'company_county',
            'company_country',
            'company_postcode',
            'company_email',
            'company_phone',
            'fleet_procure_member'
        ]));
    }
}
