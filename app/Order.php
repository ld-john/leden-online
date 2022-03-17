<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];
    /**
     * @var mixed
     */

    public function vehicle(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
    	return $this->hasOne(Vehicle::class, 'id', 'vehicle_id');
    }

    public function invoice(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
    	return $this->hasOne(Invoice::class, 'id', 'invoice_id');
    }

    public function customer(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
    	return $this->hasOne(Customer::class, 'id', 'customer_id');
    }

    public function broker(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
    	return $this->hasOne(Company::class, 'id', 'broker_id');
    }

    public function invoice_company(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
    	return $this->hasOne(Company::class, 'id', 'invoice_company_id');
    }

    public function registration_company(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
    	return $this->hasOne(Company::class, 'id', 'registration_company_id');
    }

    public function dealer(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
    	return $this->hasOne(Company::class, 'id', 'dealer_id');
    }

	public function basicCost(){
		return $this->vehicle->list_price;
	}

	public function basicSubTotal(){
		return $this->basicCost() + $this->vehicle->metallic_paint;
	}

	public function basicDealerDiscount(){
		return ($this->basicSubTotal() / 100) * $this->invoice->dealer_discount;
	}

	public function basicManufacturerSupport(){
		return ($this->basicSubTotal() / 100) * $this->invoice->manufacturer_discount;
	}

	public function basicDiscountedTotal(){
		return $this->basicSubTotal() - $this->basicDealerDiscount() - $this->basicManufacturerSupport();
	}

	public function totalDiscount()
	{
		return $this->invoice->dealer_discount + $this->invoice->manufacturer_discount;
	}

	public function factoryOptions()
	{
		return $this->vehicle->getFitOptions('factory');
	}
	public function dealerOptions()
	{
		return $this->vehicle->getFitOptions('dealer');
	}

	public function factoryOptionsSubTotal(){
    	if (!isset($this->vehicle->factory_fit_options))
	    {
	    	return 0;
	    }
		return $this->vehicle->getFitOptions('factory')->sum('option_price');
	}

	public function factoryOptionsDiscount(){
		return ($this->factoryOptionsSubTotal() / 100) * $this->totalDiscount() ;
	}

	public function factoryOptionsTotal(){
		return $this->factoryOptionsSubTotal() - $this->factoryOptionsDiscount();
	}

	public function dealerOptionsTotal(){
		if (!isset($this->vehicle->dealer_fit_options))
		{
			return 0;
		}
		return $this->vehicle->getFitOptions('dealer')->sum('option_price');
	}

	public function invoiceSubTotal(){
		return
			$this->basicDiscountedTotal() +
			$this->factoryOptionsTotal() +
			$this->dealerOptionsTotal() +
			$this->invoice->manufacturer_delivery_cost +
			$this->vehicle->onward_delivery;
	}

	public function invoiceVat(){
		return ($this->invoiceSubTotal() / 100) * 20;
	}

	public function invoiceTotal(){
		return $this->invoiceSubTotal() + $this->invoiceVat();
	}

	public function invoiceValue(){
		return $this->invoiceTotal() + $this->vehicle->first_reg_fee + $this->vehicle->rfl_cost;
	}

	public function invoiceDifferenceIncVat(){
		return  $this->invoice->invoice_funder_for - $this->invoiceValue();
	}

	public function invoiceDifferenceExVat(): float
    {
		return $this->invoiceDifferenceIncVat() / 1.2;
	}

	public function comments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany( Comments::class , 'order_id', 'id' );
    }

}
