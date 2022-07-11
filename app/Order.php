<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property mixed $id
 * @property mixed $invoice_id
 * @property mixed $invoice_company_id
 * @property mixed $registration_company_id
 * @property mixed $delivery_date
 * @property mixed $due_date
 * @property mixed $broker_ref
 * @property mixed $comments
 * @property mixed $dealer_id
 * @property mixed $broker_id
 * @property mixed $customer_id
 * @property mixed $vehicle_id
 * @property mixed $dealer_accepted
 * @property mixed $broker_accepted
 * @property int|mixed $admin_accepted
 * @property mixed $customer
 * @property mixed $invoice_company
 * @property mixed $registration_company
 * @property mixed $dealer
 * @property mixed $created_at
 * @property mixed $dealeraccepted
 * @property mixed $vehicle
 * @property mixed $updated_at
 * @property mixed $fin_number
 * @property mixed $deal_number
 */
class Order extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    public function vehicle(): HasOne
    {
        return $this->hasOne(Vehicle::class, 'id', 'vehicle_id');
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class, 'id', 'invoice_id');
    }

    public function customer(): HasOne
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }

    public function broker(): HasOne
    {
        return $this->hasOne(Company::class, 'id', 'broker_id');
    }

    public function invoice_company(): HasOne
    {
        return $this->hasOne(Company::class, 'id', 'invoice_company_id');
    }

    public function registration_company(): HasOne
    {
        return $this->hasOne(Company::class, 'id', 'registration_company_id');
    }

    public function dealer(): HasOne
    {
        return $this->hasOne(Company::class, 'id', 'dealer_id');
    }

    public function basicCost()
    {
        return $this->vehicle->list_price;
    }

    public function basicSubTotal()
    {
        return $this->basicCost() + $this->vehicle->metallic_paint;
    }

    public function basicDealerDiscount(): float|int
    {
        return ($this->basicSubTotal() / 100) * $this->invoice->dealer_discount;
    }

    public function basicManufacturerSupport(): float|int
    {
        return ($this->basicSubTotal() / 100) *
            $this->invoice->manufacturer_discount;
    }

    public function basicDiscountedTotal(): float|int
    {
        return $this->basicSubTotal() -
            $this->basicDealerDiscount() -
            $this->basicManufacturerSupport();
    }

    public function totalDiscount()
    {
        return $this->invoice->dealer_discount +
            $this->invoice->manufacturer_discount;
    }

    public function factoryOptions()
    {
        return $this->vehicle->factoryFitOptions();
    }
    public function dealerOptions()
    {
        return $this->vehicle->dealerFitOptions();
    }

    public function factoryOptionsSubTotal()
    {
        return $this->vehicle->factoryFitOptions()->sum('option_price');
    }

    public function factoryOptionsDiscount(): float|int
    {
        return ($this->factoryOptionsSubTotal() / 100) * $this->totalDiscount();
    }

    public function factoryOptionsTotal(): float|int
    {
        return $this->factoryOptionsSubTotal() -
            $this->factoryOptionsDiscount();
    }

    public function dealerOptionsTotal()
    {
        return $this->vehicle->dealerFitOptions()->sum('option_price');
    }

    public function invoiceSubTotal(): float|int
    {
        return $this->basicDiscountedTotal() +
            $this->factoryOptionsTotal() +
            $this->dealerOptionsTotal() +
            $this->invoice->manufacturer_delivery_cost +
            $this->vehicle->onward_delivery;
    }

    public function invoiceVat(): float|int
    {
        return ($this->invoiceSubTotal() / 100) * 20;
    }

    public function invoiceTotal(): float|int
    {
        return $this->invoiceSubTotal() + $this->invoiceVat();
    }

    public function invoiceValue(): float|int
    {
        return $this->invoiceTotal() +
            $this->vehicle->first_reg_fee +
            $this->vehicle->rfl_cost;
    }

    public function invoiceDifferenceIncVat(): float|int
    {
        return $this->invoice->invoice_funder_for - $this->invoiceValue();
    }

    public function invoiceDifferenceExVat(): float
    {
        return $this->invoiceDifferenceIncVat() / 1.2;
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comments::class, 'order_id', 'id');
    }
}
