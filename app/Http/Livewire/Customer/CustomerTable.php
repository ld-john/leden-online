<?php

namespace App\Http\Livewire\Customer;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Livewire\Component;
use Livewire\WithPagination;

class CustomerTable extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public function getQueryString(): array
    {
        return [];
    }

    public $paginate;
    public $checked = [];
    public $modalShow = false;
    public $searchCustomerName;

    public function render(): View|Application|Factory|\Illuminate\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $customers = Customer::orderBy('customer_name')
            ->withCount('orders')
            ->with('orders')
            ->when($this->searchCustomerName, function ($query) {
                $query->where(
                    'customer_name',
                    'like',
                    '%' . $this->searchCustomerName . '%',
                );
            })
            ->paginate($this->paginate);

        $duplicates = Customer::get()
            ->toBase()
            ->duplicates('customer_name')
            ->unique();

        return view('livewire.customer.customer-table', [
            'customers' => $customers,
            'duplicates' => $duplicates,
        ]);
    }

    public function updatingSearchCustomerName(): void
    {
        $this->resetPage();
    }

    public function mergeSelected(): void
    {
        $merge = $this->checked;
        $keep = array_shift($merge);

        $orders = Order::whereIn('customer_id', $merge)->get();
        foreach ($orders as $order) {
            $order->update(['customer_id' => $keep]);
        }

        Customer::destroy($merge);

        $this->checked = [];
    }
}
