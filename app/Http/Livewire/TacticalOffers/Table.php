<?php

namespace App\Http\Livewire\TacticalOffers;

use App\Models\TacticalOffer;
use Livewire\Component;
use Livewire\WithPagination;

class Table extends Component
{

    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public function getQueryString(): array
    {
        return [];
    }

    public $paginate;
    public $searchOfferName;

    public function mount(): void
    {
        $this->paginate = 10;
    }

    public function updatingSearchOfferName(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $offers = TacticalOffer::orderBy('created_at', 'desc')->when($this->searchOfferName, function ($query) {
            $query->where('name', 'like', '%'.$this->searchOfferName.'%');
        })->paginate($this->paginate);
        return view('livewire.tactical-offers.table', [
            'offers' => $offers
        ]);
    }
}
