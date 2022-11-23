<?php

namespace App\Http\Livewire\Customer;

use App\Models\Customer;
use App\Models\Order;
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

    public function render()
    {
        $customers = Customer::orderBy('customer_name', 'asc')
            ->with('orders')
            ->paginate($this->paginate);

        return view('livewire.customer.customer-table', [
            'customers' => $customers,
        ]);
    }

    public function mergeSelected()
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
