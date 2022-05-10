<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property mixed $ford_bonus_pay_date
 * @property mixed $ford_bonus
 * @property mixed $finance_company_bonus_pay_date
 * @property mixed $finance_company_bonus_invoice_number
 * @property mixed $finance_company_bonus_invoice
 * @property mixed $fleet_procure_pay_date
 * @property mixed $fleet_procure_invoice_number
 * @property mixed $fleet_procure_invoice
 * @property mixed $dealer_invoice_number
 * @property mixed $dealer_pay_date
 * @property mixed $broker_pay_date
 * @property mixed $broker_commission_pay_date
 * @property mixed $finance_commission_pay_date
 * @property mixed $commission_to_finance_company
 * @property mixed $commission_to_broker
 * @property mixed $invoice_value_to_broker
 * @property mixed $invoice_value
 * @property mixed $invoice_funder_for
 * @property mixed $onward_delivery
 * @property mixed $manufacturer_delivery_cost
 * @property mixed $manufacturer_discount
 * @property mixed $dealer_discount
 * @property mixed $broker_commission_invoice_number
 * @property mixed $broker_invoice_number
 * @property mixed $finance_commission_invoice_number
 * @property mixed $id
 */
class Invoice extends Model
{
    use SoftDeletes;

    protected $guarded = [];
    use HasFactory;

    public function order(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Order::class, 'invoice_id', 'id');
    }
}
