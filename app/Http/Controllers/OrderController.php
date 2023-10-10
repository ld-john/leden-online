<?php

namespace App\Http\Controllers;

use App\Exports\BrokersOrderDownload;
use App\Models\Comment;
use App\Models\Company;
use App\Models\Finance\FinanceType;
use App\Models\Finance\InitialPayment;
use App\Models\Finance\Maintenance;
use App\Models\Finance\Mileage;
use App\Models\Finance\Term;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Vehicle;
use Carbon\Carbon;
use DateTime;
use Excel;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class OrderController extends Controller
{
    public static function setProvisionalRegDate(Vehicle $vehicle)
    {
        if (
            empty($vehicle->vehicle_registered_on) ||
            $vehicle->vehicle_registered_on === '0000-00-00 00:00:00'
        ) {
            if (
                !empty($vehicle->order?->delivery_date) ||
                $vehicle->order?->delivery_date !== '0000-00-00'
            ) {
                $vehicle->update([
                    'vehicle_reg_date' => $vehicle->order?->delivery_date,
                ]);
            }
        } else {
            $vehicle->update([
                'vehicle_reg_date' => $vehicle->vehicle_registered_on,
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create()
    {
        return view('order.create');
    }

    /**
     * Display the specified resource.
     *
     * @param Order $order
     * @return Application|Factory|View
     */
    public function show(Order $order)
    {
        $finance_type = FinanceType::where('id', $order->finance_type)->first()
            ?->option;
        $maintenance = Maintenance::where('id', $order->maintenance)->first()
            ?->option;
        $term = Term::where('id', $order->term)->first()?->option;
        $initial_payment = InitialPayment::where(
            'id',
            $order->initial_payment,
        )->first()?->option;
        $mileage = Mileage::where('id', $order->mileage)->first()?->option;

        return view('order.show', [
            'order' => $order,
            'finance_type' => $finance_type,
            'maintenance' => $maintenance,
            'term' => $term,
            'initial_payment' => $initial_payment,
            'mileage' => $mileage,
        ]);
    }

    public function recycle()
    {
        $orders = Order::onlyTrashed()
            ->latest()
            ->with('vehicle')
            ->paginate(10);

        return view('order.deleted', [
            'title' => 'Recycle Bin',
            'active_page' => 'order-recycle-bin',
            'orders' => $orders,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Order $order
     * @return Application|Factory|View
     */
    public function edit(Order $order)
    {
        return view('order.edit', ['order' => $order]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Order $order
     * @return RedirectResponse
     */
    public function destroy(Order $order): RedirectResponse
    {
        Order::destroy($order->id);
        notify()->success(
            'Order #' .
                $order->id .
                ' deleted successfully - ' .
                $order->vehicle->niceName(),
            'Order Deleted',
        );
        return redirect()->route('order_bank');
    }

    /**
     * Duplicate the specified resource in storage.
     *
     * @param Request $request
     * @param Order $order
     * @return RedirectResponse
     */

    public function duplicate(Request $request, Order $order): RedirectResponse
    {
        $vehicle = Vehicle::where('id', $order->vehicle_id)->first();
        $invoice = Invoice::where('id', $order->invoice_id)->first();

        for ($i = 1; $i <= $request->duplicateQty; $i++) {
            $newCar = $vehicle->replicate();
            $newCar->chassis = null;
            $newCar->reg = null;
            $newCar->orbit_number = null;
            $newCar->ford_order_number =
                $vehicle->ford_order_number . '-copy-' . $i;
            $newCar->save();
            $newInvoice = $invoice->replicate();
            $newInvoice->save();
            $newOrder = $order->replicate();
            $newOrder->vehicle_id = $newCar->id;
            $newOrder->invoice_id = $newInvoice->id;
            $newOrder->broker_ref = null;
            $newOrder->save();
        }
        notify()->success('Order successfully duplicated', 'Order Duplicated');
        return redirect()->route('order_bank');
    }

    public function showReserveOrder(Vehicle $vehicle): Factory|View|Application
    {
        $order = Order::where('vehicle_id', '=', $vehicle->id)->first();
        return view('order.reserve', [
            'vehicle' => $vehicle,
            'order' => $order,
        ]);
    }

    public function showOrderBank(): Factory|View|Application
    {
        return view('order.index', [
            'title' => 'Order Bank',
            'active_page' => 'order-bank',
            'route' => 'order_bank',
            'status' => [1, 4, 10, 11, 12, 13, 14, 15, 16, 17],
        ]);
    }

    public function completedOrders()
    {
        return view('order.index', [
            'title' => 'Completed Orders',
            'active_page' => 'completed-orders',
            'route' => 'completed_orders',
            'status' => [7],
        ]);
    }

    public function showManageDeliveries()
    {
        return view('order.deliveries', ['status' => [3, 5, 6]]);
    }

    /**
     * @throws Exception
     */
    public function downloadPDF(Order $order)
    {
        // Get Fit options lists
        $factory_fit_options = $order->factoryOptions();
        $dealer_fit_options = $order->dealerOptions();

        // Define variables
        $subtotal = $order->invoiceSubTotal();
        $vat = $order->invoiceVat();
        $total = $order->invoiceValue();

        $deliveryAddress = array_filter([
            $order->customer->customer_name,
            $order->customer->address_1,
            $order->customer->address_2,
            $order->customer->town,
            $order->customer->city,
            $order->customer->county,
            $order->customer->postcode,
        ]);

        if ($order->invoice_company) {
            $invoiceAddress = array_filter([
                $order->invoice_company->company_name,
                $order->invoice_company->company_address1,
                $order->invoice_company->company_address2,
                $order->invoice_company->company_city,
                $order->invoice_company->company_county,
                $order->invoice_company->company_postcode,
            ]);
        } else {
            $invoiceAddress = [];
        }

        if ($order->registration_company) {
            $registrationAddress = array_filter([
                $order->registration_company->company_name,
                $order->registration_company->company_address1,
                $order->registration_company->company_address2,
                $order->registration_company->company_city,
                $order->registration_company->company_county,
                $order->registration_company->company_postcode,
            ]);
        } else {
            $registrationAddress = [];
        }

        if ($order->dealer) {
            $dealerAddress = array_filter([
                $order->dealer->company_name,
                $order->dealer->company_address1,
                $order->dealer->company_address2,
                $order->dealer->company_city,
                $order->dealer->company_county,
                $order->dealer->company_postcode,
            ]);
        } else {
            $dealerAddress = [];
        }

        $created = new DateTime($order->created_at);
        $amended = new DateTime($order->updated_at);
        if ($order->delivery_date) {
            $deliveryDate = new DateTime($order->delivery_date);
            $deliveryDate = $deliveryDate->format('d/m/Y');
        } else {
            $deliveryDate = 'TBC';
        }

        $brokerName = $order->broker->company_name ?? '--';

        if ($order->finance_broker) {
            $brokerName .= ' | ' . $order->finance_broker->company_name;
        }

        $vehicleDetails = [
            [
                'Manufacturer Order Ref' => $order->vehicle->ford_order_number,
                'Order Date' => $created->format('d/m/Y'),
                'Delivery Date' => $deliveryDate,
            ],
            [
                'Vehicle Make' => $order->vehicle->manufacturer->name ?? '--',
                'Vehicle Model' => $order->vehicle->model ?? '--',
                'Vehicle Type' => $order->vehicle->type ?? '--',
            ],
            [
                'Vehicle Derivative' => $order->vehicle->derivative ?? '--',
                'Vehicle Engine' => $order->vehicle->engine ?? '--',
                'Vehicle Transmission' => $order->vehicle->transmission ?? '--',
            ],
            [
                'Vehicle Fuel Type' => $order->vehicle->fuel_type ?? '--',
                'Vehicle Colour' => $order->vehicle->colour ?? '--',
                'Amended Order Date' => $amended->format('d/m/Y') ?? '--',
            ],
            [
                'Leden Order Ref' => $order->id,
                'Broker' => $brokerName,
                'Broker Ref No.' => $order->broker_ref ?? '',
            ],
            [
                'Vehicle Chassis Prefix' =>
                    $order->vehicle->chassis_prefix ?? '--',
                'Vehicle Chassis' => $order->vehicle->chassis ?? '--',
                'Vehicle Reg' => $order->vehicle->reg ?? '--',
            ],
        ];
        $vehicleDetailsHtml = '';

        foreach ($vehicleDetails as $section) {
            $vehicleDetailsHtml .= '<tr>';

            foreach ($section as $name => $item) {
                $vehicleDetailsHtml .=
                    '<td><strong>' .
                    ($name ? $name . ':' : '') .
                    ' </strong> ' .
                    $item .
                    '</td>';
            }

            $vehicleDetailsHtml .= '</tr>';
        }

        $comments = Comment::where('commentable_id', $order->id)
            ->where('commentable_type', 'order')
            ->where('dealer_comment', true)
            ->orderBy('created_at')
            ->get();
        $comments_table =
            '<table cellspacing="0" cellpadding="0" border="0" width="100%" class="contents" style="margin-top: 10px"><tr><th colspan="2">Comments</th></tr>';
        if ($comments) {
            foreach ($comments as $comment) {
                $comments_table .=
                    '<tr><td>' .
                    $comment->user->firstname .
                    ' ' .
                    $comment->user->lastname .
                    '</td><td>' .
                    strip_tags($comment->content) .
                    '</td></tr>';
            }
        }
        $comments_table .= '</table>';

        $data = [
            'order' => $order,
            'deliveryAddress' => $deliveryAddress,
            'invoiceAddress' => $invoiceAddress,
            'registrationAddress' => $registrationAddress,
            'dealerAddress' => $dealerAddress,
            'vehicleDetailsHtml' => $vehicleDetailsHtml,
            'factory_fit_options' => $factory_fit_options,
            'dealer_fit_options' => $dealer_fit_options,
            'subtotal' => $subtotal,
            'vat' => $vat,
            'total' => $total,
            'comments_table' => $comments_table,
        ];

        $pdf = app('dompdf.wrapper');

        $pdf->loadView('pdf', $data);

        //return $pdf->stream();
        return $pdf->download('leden-order-' . $order->id . '.pdf');
    }

    function date_cleanup()
    {
        Order::chunk('50', function ($orders) {
            foreach ($orders as $order) {
                if ($order->delivery_date_OLD) {
                    $order->update([
                        'delivery_date' => $order->delivery_date_OLD,
                    ]);
                    var_dump('delivery date moved');
                }
                if ($order->due_date_OLD) {
                    $order->update([
                        'due_date' => $order->due_date_OLD,
                    ]);
                    var_dump('due date moved');
                }
                if ($order->completed_date_OLD) {
                    $order->update([
                        'completed_date' => $order->completed_date_OLD,
                    ]);
                    var_dump('completed date moved');
                }
            }
        });
    }

    /**
     * Forcibly remove a vehicle from the database, completely removing it rather than soft deleting.
     * @param $order
     * @return RedirectResponse
     */
    public function forceDelete($order): RedirectResponse
    {
        Order::withTrashed()
            ->where('id', $order)
            ->forceDelete();

        notify()->success($order->id . ' successfully deleted');

        return redirect()->route('order.recycle-bin');
    }

    /**
     * Export the Orders for Central Contracts with the required fields for their CSV
     * @return BinaryFileResponse
     */
    function broker_orders_export(Company $broker)
    {
        $orders = Order::whereHas('vehicle', function ($q) {
            $q->where('vehicle_status', '!=', '7');
        })
            ->where('broker_id', '=', $broker->id)
            ->with('vehicle')
            ->with('customer')
            ->get();

        $date = Carbon::now()->format('Y_m_d');

        $company_name = Str::snake($broker->company_name);

        return Excel::download(
            new BrokersOrderDownload($orders),
            $company_name . '_orders_' . $date . '.csv',
        );
    }
}
