<?php

namespace App\Models;

use App\InvoiceCompany;
use App\RegistrationCompany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * App\Order
 *
 * @property int $id
 * @property string|null $customer_name
 * @property string|null $company_name
 * @property string $preferred_name
 * @property string $vehicle_make
 * @property string $vehicle_model
 * @property string $vehicle_type
 * @property string|null $vehicle_reg
 * @property string $vehicle_derivative
 * @property string $vehicle_engine
 * @property string $vehicle_trans
 * @property string $vehicle_fuel_type
 * @property string $vehicle_doors
 * @property string $vehicle_colour
 * @property string $vehicle_body
 * @property string $vehicle_trim
 * @property int|null $broker
 * @property string|null $broker_order_ref
 * @property string|null $order_ref
 * @property string|null $chassis_prefix
 * @property string|null $chassis
 * @property int $vehicle_status
 * @property string|null $due_date
 * @property string|null $delivery_date
 * @property string|null $model_year
 * @property string|null $vehicle_registered_on
 * @property int|null $dealership
 * @property string|null $invoice_to
 * @property string|null $invoice_to_address
 * @property string|null $register_to
 * @property string|null $register_to_address
 * @property float|null $list_price
 * @property float|null $metallic_paint
 * @property float|null $dealer_discount
 * @property float|null $manufacturer_discount
 * @property float|null $total_discount
 * @property float|null $manufacturer_delivery_cost
 * @property float|null $first_reg_fee
 * @property float|null $rfl_cost
 * @property float|null $onward_delivery
 * @property float|null $invoice_funder_for
 * @property float|null $invoice_value
 * @property int $show_discount
 * @property int $show_offer
 * @property int $hide_from_broker
 * @property int $hide_from_dealer
 * @property int $show_in_ford_pipeline
 * @property float|null $invoice_finance
 * @property string|null $invoice_finance_number
 * @property string|null $finance_commission_paid
 * @property float|null $invoice_broker
 * @property string|null $invoice_broker_number
 * @property string|null $invoice_broker_paid
 * @property float|null $commission_broker
 * @property string|null $commission_broker_number
 * @property string|null $commission_broker_paid
 * @property string|null $delivery_address_1
 * @property string|null $delivery_address_2
 * @property string|null $delivery_town
 * @property string|null $delivery_city
 * @property string|null $delivery_county
 * @property string|null $delivery_postcode
 * @property string|null $comments
 * @property string|null $reserved_on
 * @property int $admin_accepted
 * @property int $dealer_accepted
 * @property int $broker_accepted
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int|null $registration_company
 * @property int|null $invoice_company
 * @method static Builder|\App\OrderLegacy newModelQuery()
 * @method static Builder|\App\OrderLegacy newQuery()
 * @method static Builder|\App\OrderLegacy query()
 * @method static Builder|\App\OrderLegacy whereAdminAccepted($value)
 * @method static Builder|\App\OrderLegacy whereBroker($value)
 * @method static Builder|\App\OrderLegacy whereBrokerAccepted($value)
 * @method static Builder|\App\OrderLegacy whereBrokerOrderRef($value)
 * @method static Builder|\App\OrderLegacy whereChassis($value)
 * @method static Builder|\App\OrderLegacy whereChassisPrefix($value)
 * @method static Builder|\App\OrderLegacy whereComments($value)
 * @method static Builder|\App\OrderLegacy whereCommissionBroker($value)
 * @method static Builder|\App\OrderLegacy whereCommissionBrokerNumber($value)
 * @method static Builder|\App\OrderLegacy whereCommissionBrokerPaid($value)
 * @method static Builder|\App\OrderLegacy whereCompanyName($value)
 * @method static Builder|\App\OrderLegacy whereCreatedAt($value)
 * @method static Builder|\App\OrderLegacy whereCustomerName($value)
 * @method static Builder|\App\OrderLegacy whereDealerAccepted($value)
 * @method static Builder|\App\OrderLegacy whereDealerDiscount($value)
 * @method static Builder|\App\OrderLegacy whereDealership($value)
 * @method static Builder|\App\OrderLegacy whereDeliveryAddress1($value)
 * @method static Builder|\App\OrderLegacy whereDeliveryAddress2($value)
 * @method static Builder|\App\OrderLegacy whereDeliveryCity($value)
 * @method static Builder|\App\OrderLegacy whereDeliveryCounty($value)
 * @method static Builder|\App\OrderLegacy whereDeliveryDate($value)
 * @method static Builder|\App\OrderLegacy whereDeliveryPostcode($value)
 * @method static Builder|\App\OrderLegacy whereDeliveryTown($value)
 * @method static Builder|\App\OrderLegacy whereDueDate($value)
 * @method static Builder|\App\OrderLegacy whereFinanceCommissionPaid($value)
 * @method static Builder|\App\OrderLegacy whereFirstRegFee($value)
 * @method static Builder|\App\OrderLegacy whereHideFromBroker($value)
 * @method static Builder|\App\OrderLegacy whereHideFromDealer($value)
 * @method static Builder|\App\OrderLegacy whereId($value)
 * @method static Builder|\App\OrderLegacy whereInvoiceBroker($value)
 * @method static Builder|\App\OrderLegacy whereInvoiceBrokerNumber($value)
 * @method static Builder|\App\OrderLegacy whereInvoiceBrokerPaid($value)
 * @method static Builder|\App\OrderLegacy whereInvoiceCompany($value)
 * @method static Builder|\App\OrderLegacy whereInvoiceFinance($value)
 * @method static Builder|\App\OrderLegacy whereInvoiceFinanceNumber($value)
 * @method static Builder|\App\OrderLegacy whereInvoiceFunderFor($value)
 * @method static Builder|\App\OrderLegacy whereInvoiceTo($value)
 * @method static Builder|\App\OrderLegacy whereInvoiceToAddress($value)
 * @method static Builder|\App\OrderLegacy whereInvoiceValue($value)
 * @method static Builder|\App\OrderLegacy whereListPrice($value)
 * @method static Builder|\App\OrderLegacy whereManufacturerDeliveryCost($value)
 * @method static Builder|\App\OrderLegacy whereManufacturerDiscount($value)
 * @method static Builder|\App\OrderLegacy whereMetallicPaint($value)
 * @method static Builder|\App\OrderLegacy whereModelYear($value)
 * @method static Builder|\App\OrderLegacy whereOnwardDelivery($value)
 * @method static Builder|\App\OrderLegacy whereOrderRef($value)
 * @method static Builder|\App\OrderLegacy wherePreferredName($value)
 * @method static Builder|\App\OrderLegacy whereRegisterTo($value)
 * @method static Builder|\App\OrderLegacy whereRegisterToAddress($value)
 * @method static Builder|\App\OrderLegacy whereRegistrationCompany($value)
 * @method static Builder|\App\OrderLegacy whereReservedOn($value)
 * @method static Builder|\App\OrderLegacy whereRflCost($value)
 * @method static Builder|\App\OrderLegacy whereShowDiscount($value)
 * @method static Builder|\App\OrderLegacy whereShowInFordPipeline($value)
 * @method static Builder|\App\OrderLegacy whereShowOffer($value)
 * @method static Builder|\App\OrderLegacy whereTotalDiscount($value)
 * @method static Builder|\App\OrderLegacy whereUpdatedAt($value)
 * @method static Builder|\App\OrderLegacy whereVehicleBody($value)
 * @method static Builder|\App\OrderLegacy whereVehicleColour($value)
 * @method static Builder|\App\OrderLegacy whereVehicleDerivative($value)
 * @method static Builder|\App\OrderLegacy whereVehicleDoors($value)
 * @method static Builder|\App\OrderLegacy whereVehicleEngine($value)
 * @method static Builder|\App\OrderLegacy whereVehicleFuelType($value)
 * @method static Builder|\App\OrderLegacy whereVehicleMake($value)
 * @method static Builder|\App\OrderLegacy whereVehicleModel($value)
 * @method static Builder|\App\OrderLegacy whereVehicleReg($value)
 * @method static Builder|\App\OrderLegacy whereVehicleRegisteredOn($value)
 * @method static Builder|\App\OrderLegacy whereVehicleStatus($value)
 * @method static Builder|\App\OrderLegacy whereVehicleTrans($value)
 * @method static Builder|\App\OrderLegacy whereVehicleTrim($value)
 * @method static Builder|\App\OrderLegacy whereVehicleType($value)
 * @mixin \Eloquent
 * @property string|null $customer_phone
 * @property string|null $holding_code
 * @property Carbon|null $deleted_at
 * @property-read Collection|FitOption[] $fit_options
 * @property-read int|null $fit_options_count
 * @method static \Illuminate\Database\Query\Builder|\App\OrderLegacy onlyTrashed()
 * @method static Builder|\App\OrderLegacy whereCustomerPhone($value)
 * @method static Builder|\App\OrderLegacy whereDeletedAt($value)
 * @method static Builder|\App\OrderLegacy whereHoldingCode($value)
 * @method static \Illuminate\Database\Query\Builder|\App\OrderLegacy withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\OrderLegacy withoutTrashed()
 */
class OrderLegacy extends Model
{
    use SoftDeletes;

    protected $table = 'orderLegacy';

    protected $dates = ['due_date', 'delivery_date', 'vehicle_registered_on'];

    protected $fillable = [
        'customer_name',
        'company_name',
        'preferred_name',
        'vehicle_make',
        'vehicle_model',
        'vehicle_type',
        'vehicle_reg',
        'vehicle_derivative',
        'vehicle_engine',
        'vehicle_trans',
        'vehicle_fuel_type',
        'vehicle_doors',
        'vehicle_colour',
        'vehicle_body',
        'vehicle_trim',
        'broker',
        'broker_order_ref',
        'order_ref',
        'chassis_prefix',
        'chassis',
        'vehicle_status',
        'due_date',
        'delivery_date',
        'model_year',
        'vehicle_registered_on',
        'dealership',
        'invoice_to',
        'invoice_to_address',
        'register_to',
        'register_to_address',
        'list_price',
        'metallic_paint',
        'dealer_discount',
        'manufacturer_discount',
        'total_discount',
        'manufacturer_delivery_cost',
        'first_reg_fee',
        'rfl_cost',
        'onward_delivery',
        'invoice_funder_for',
        'invoice_value',
        'show_discount',
        'show_offer',
        'hide_from_broker',
        'hide_from_dealer',
        'show_in_ford_pipeline',
        'invoice_finance',
        'invoice_finance_number',
        'finance_commission_paid',
        'invoice_broker',
        'invoice_broker_number',
        'invoice_broker_paid',
        'commission_broker',
        'commission_broker_number',
        'commission_broker_paid',
        'delivery_address_1',
        'delivery_address_2',
        'delivery_town',
        'delivery_city',
        'delivery_county',
        'delivery_postcode',
        'comments',
        'reserved_on',
        'admin_accepted',
        'dealer_accepted',
        'broker_accepted',
        'registration_company',
        'invoice_company',
        'customer_phone',
        'holding_code',
    ];

    public function basicCost()
    {
        return $this->vehicle->list_price;
    }

    public function basicSubTotal()
    {
        return $this->basicCost() + $this->metallic_paint;
    }

    public function basicDealerDiscount()
    {
        return ($this->basicSubTotal() / 100) * $this->dealer_discount;
    }

    public function basicManufacturerSupport()
    {
        return ($this->basicSubTotal() / 100) * $this->manufacturer_discount;
    }

    public function basicDiscountedTotal()
    {
        return $this->basicSubTotal() -
            $this->basicDealerDiscount() -
            $this->basicManufacturerSupport();
    }

    public function totalDiscount()
    {
        return $this->dealer_discount + $this->manufacturer_discount;
    }

    public function factoryOptionsSubTotal()
    {
        return $this->factoryOptions()->sum('option_price');
    }

    public function factoryOptionsDiscount()
    {
        return ($this->factoryOptionsSubTotal() / 100) * $this->totalDiscount();
    }

    public function factoryOptionsTotal()
    {
        return $this->factoryOptionsSubTotal() -
            $this->factoryOptionsDiscount();
    }

    public function dealerOptionsTotal()
    {
        return $this->dealerOptions()->sum('option_price');
    }

    public function invoiceSubTotal()
    {
        return $this->basicDiscountedTotal() +
            $this->factoryOptionsTotal() +
            $this->dealerOptionsTotal() +
            $this->manufacturer_delivery_cost +
            $this->onward_delivery;
    }

    public function invoiceVat()
    {
        return ($this->invoiceSubTotal() / 100) * 20;
    }

    public function invoiceTotal()
    {
        return $this->invoiceSubTotal() + $this->invoiceVat();
    }

    public function invoiceValue()
    {
        return $this->invoiceTotal() + $this->first_reg_fee + $this->rfl_cost;
    }

    public function invoiceDifferenceIncVat()
    {
        return $this->invoiceValue() - $this->invoice_funder_for;
    }

    public function invoiceDifferenceExVat()
    {
        return $this->invoiceDifferenceIncVat() / 1.2;
    }

    public function factoryOptions()
    {
        return $this->fit_options()->where('option_type', 'factory');
    }
    public function dealerOptions()
    {
        return $this->fit_options()->where('option_type', 'dealer');
    }

    public function fit_options()
    {
        return $this->belongsToMany(
            FitOption::class,
            'fit_options_connector',
            'order_id',
            'fit_option_id',
        );
    }

    public function invoice_company_details()
    {
        return $this->belongsTo(InvoiceCompany::class, 'invoice_company');
    }
    public function registration_company_details()
    {
        return $this->belongsTo(
            RegistrationCompany::class,
            'registration_company',
        );
    }
}
