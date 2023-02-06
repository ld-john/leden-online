<?php

namespace App\Http\Controllers;

use App\Models\Invoice;

/**
 * Controller for the Invoices
 */
class InvoiceController extends Controller
{
    /**
     * A temporary function to clean up the various dates from Date/Time to Date Format
     * @return void
     */
    public function cleanDates()
    {
        set_time_limit(300);
        Invoice::chunk('50', function ($invoices) {
            foreach ($invoices as $invoice) {
                if ($invoice->finance_commission_pay_date_OLD) {
                    $invoice->update([
                        'finance_commission_pay_date' =>
                            $invoice->finance_commission_pay_date_OLD,
                    ]);
                    var_dump('Finance Commission Pay Date Updated');
                }
                if ($invoice->broker_commission_pay_date_OLD) {
                    $invoice->update([
                        'broker_commission_pay_date' =>
                            $invoice->broker_commission_pay_date_OLD,
                    ]);
                    var_dump('broker Commission Pay Date Updated');
                }
                if ($invoice->broker_pay_date_OLD) {
                    $invoice->update([
                        'broker_pay_date' => $invoice->broker_pay_date_OLD,
                    ]);
                    var_dump('broker Pay Date Updated');
                }
                if ($invoice->dealer_pay_date_OLD) {
                    $invoice->update([
                        'dealer_pay_date' => $invoice->dealer_pay_date_OLD,
                    ]);
                    var_dump('dealer Pay Date Updated');
                }
                if ($invoice->fleet_procure_pay_date_OLD) {
                    $invoice->update([
                        'fleet_procure_pay_date' =>
                            $invoice->fleet_procure_pay_date_OLD,
                    ]);
                    var_dump('fleet_procure Pay Date Updated');
                }
                if ($invoice->finance_company_bonus_pay_date_OLD) {
                    $invoice->update([
                        'finance_company_bonus_pay_date' =>
                            $invoice->finance_company_bonus_pay_date_OLD,
                    ]);
                    var_dump('finance_company Pay Date Updated');
                }
                if ($invoice->ford_bonus_pay_date_OLD) {
                    $invoice->update([
                        'ford_bonus_pay_date' =>
                            $invoice->ford_bonus_pay_date_OLD,
                    ]);
                    var_dump('ford_bonus Pay Date Updated');
                }
            }
        });
        var_dump('Update Completed');
    }
}
