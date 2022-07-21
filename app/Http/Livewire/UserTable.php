<?php

namespace App\Http\Livewire;

use App\User;
use Livewire\Component;
use Livewire\WithPagination;

class UserTable extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public function getQueryString(): array
    {
        return [];
    }

    public $paginate;
    public $searchID;
    public $searchFirstName;
    public $searchLastName;
    public $searchEmail;
    public $searchCompany;
    public $searchRole;

    public function render()
    {
        $data = User::select(
            'id',
            'firstname',
            'lastname',
            'email',
            'company_id',
            'role',
            'reservation_allowed',
            'is_deleted',
        )
            ->with('company:id,company_name')
            ->when($this->searchID, function ($query) {
                $query->where('id', 'like', '%' . $this->searchID . '%');
            })
            ->when($this->searchFirstName, function ($query) {
                $query->where(
                    'firstname',
                    'like',
                    '%' . $this->searchFirstName . '%',
                );
            })
            ->when($this->searchLastName, function ($query) {
                $query->where(
                    'lastname',
                    'like',
                    '%' . $this->searchLastName . '%',
                );
            })
            ->when($this->searchEmail, function ($query) {
                $query->where('email', 'like', '%' . $this->searchEmail . '%');
            })
            ->when($this->searchCompany, function ($query) {
                $query->whereHas('company', function ($query) {
                    $query->where(
                        'company_name',
                        'like',
                        '%' . $this->searchCompany . '%',
                    );
                });
            })
            ->when($this->searchRole, function ($query) {
                $query->where('role', $this->searchRole);
            })
            ->paginate($this->paginate);

        return view('livewire.user-table', ['users' => $data]);
    }
}
