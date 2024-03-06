<?php

namespace App\Models;

use App\Models\Finance\FinanceType;
use App\Models\Finance\InitialPayment;
use App\Models\Finance\Maintenance;
use App\Models\Finance\Mileage;
use App\Models\Finance\Term;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use LaravelIdea\Helper\App\Models\_IH_FitOption_QB;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Comments;

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
 * @property int $finance_broker_id
 * @property bool $finance_broker_toggle
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
 * @property mixed $order_ref
 * @property Company $broker
 * @property Company $finance_broker
 * @property mixed $delivery
 * @property mixed $delivery_id
 * @property mixed $contract_confirmation
 * @property mixed $exception
 */
class Order extends Model
{
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

    public function finance_broker(): HasOne
    {
        return $this->hasOne(Company::class, 'id', 'finance_broker_id');
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

    public function delivery(): HasOne
    {
        return $this->hasOne(Delivery::class, 'id', 'delivery_id');
    }

    public function FinanceType(): HasOne
    {
        return $this->hasOne(FinanceType::class, 'id', 'finance_type');
    }

    public function Maintenance(): HasOne
    {
        return $this->hasOne(Maintenance::class, 'id', 'maintenance');
    }

    public function Term(): HasOne
    {
        return $this->hasOne(Term::class, 'id', 'term');
    }

    public function InitialPayment(): HasOne
    {
        return $this->hasOne(InitialPayment::class, 'id', 'initial_payment');
    }

    public function Mileage(): HasOne
    {
        return $this->hasOne(Mileage::class, 'id', 'mileage');
    }

    public function basicDealerDiscount(): float|int
    {
        return ($this->vehicle->list_price / 100) *
            $this->invoice->dealer_discount;
    }

    public function basicManufacturerSupport(): float|int
    {
        return ($this->vehicle->list_price / 100) *
            $this->invoice->manufacturer_discount;
    }

    public function metallicPaintDiscount(): float|int
    {
        $metallic_paint_discount = DealerDiscount::where(
            'model_id',
            VehicleModel::where('name', '=', $this->vehicle->model)->first()
                ->id,
        )
            ->where('dealer_id', $this->dealer->id)
            ->first()->paint_discount;

        $metallic_paint_value = $this->vehicle->metallic_paint;

        $metallic_paint_manufacturer_discount =
            ($metallic_paint_value / 100) *
            $this->invoice->manufacturer_discount;

        if ($metallic_paint_discount !== 0) {
            $metallic_paint_discount =
                ($metallic_paint_value / 100) * $metallic_paint_discount;

            $metallicDiscountedTotal =
                $metallic_paint_value -
                $metallic_paint_manufacturer_discount -
                $metallic_paint_discount -
                $this->invoice->leden_discount;
        } else {
            $metallic_paint_discount =
                ($metallic_paint_value / 100) * $this->invoice->dealer_discount;
            $metallicDiscountedTotal =
                $metallic_paint_value -
                $metallic_paint_discount -
                $metallic_paint_manufacturer_discount -
                $this->invoice->leden_discount;
        }

        return $metallicDiscountedTotal;
    }

    public function basicDiscountedTotal(): float|int
    {
        return $this->vehicle->list_price -
            $this->basicDealerDiscount() -
            $this->basicManufacturerSupport() -
            $this->invoice->leden_discount;
    }

    public function totalDiscount()
    {
        return $this->invoice->dealer_discount +
            $this->invoice->manufacturer_discount;
    }

    public function factoryOptions(): Collection|BelongsToMany|_IH_FitOption_QB
    {
        return $this->vehicle->factoryFitOptions();
    }
    public function dealerOptions(): Collection|BelongsToMany|_IH_FitOption_QB
    {
        return $this->vehicle->dealerFitOptions();
    }

    public function factoryOptionsSubTotal()
    {
        return $this->factoryOptions()->sum('option_price');
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
            $this->metallicPaintDiscount() +
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

    public function comments(): MorphMany
    {
        return $this->morphMany(Comments::class, 'commentable');
    }
}
