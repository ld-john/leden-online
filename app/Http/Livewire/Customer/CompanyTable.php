<?php

namespace App\Http\Livewire\Customer;

use App\Company;
use Livewire\Component;
use Livewire\WithPagination;

class CompanyTable extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public function getQueryString(): array
    {
        return [];
    }

    public $paginate;
    public $searchName;
    public $searchAddress;
    public $searchEmail;
    public $searchPhone;
    public $searchType;

    public function render()
    {
        $data = Company::when($this->searchType, function ($query) {
            $query->where('company_type', $this->searchType);
        })
            ->when($this->searchName, function ($query) {
                $query->where(
                    'company_name',
                    'like',
                    '%' . $this->searchName . '%',
                );
            })
            ->when($this->searchAddress, function ($query) {
                $query
                    ->where(
                        'company_address1',
                        'like',
                        '%' . $this->searchAddress . '%',
                    )
                    ->orWhere(
                        'company_address2',
                        'like',
                        '%' . $this->searchAddress . '%',
                    )
                    ->orWhere(
                        'company_city',
                        'like',
                        '%' . $this->searchAddress . '%',
                    )
                    ->orWhere(
                        'company_county',
                        'like',
                        '%' . $this->searchAddress . '%',
                    )
                    ->orWhere(
                        'company_country',
                        'like',
                        '%' . $this->searchAddress . '%',
                    )
                    ->orWhere(
                        'company_postcode',
                        'like',
                        '%' . $this->searchAddress . '%',
                    );
            })
            ->when($this->searchEmail, function ($query) {
                $query->where(
                    'company_email',
                    'like',
                    '%' . $this->searchEmail . '%',
                );
            })
            ->when($this->searchPhone, function ($query) {
                $query->where(
                    'company_phone',
                    'like',
                    '%' . $this->searchPhone . '%',
                );
            })
            ->paginate($this->paginate);
        return view('livewire.customer.company-table', ['companies' => $data]);
    }
}
