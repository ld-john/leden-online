<?php

namespace App\Http\Controllers;

use App\Company;
use App\Invoice;
use App\Notifications\deliveryDateChanged;
use App\Notifications\notifications;
use App\Order;
use App\OrderLegacy;
use App\User;
use App\Vehicle;
use Barryvdh\DomPDF\PDF;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
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
        return view('order.show', ['order' => $order]);
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
        return redirect()
            ->route('order_bank')
            ->with(
                'successMsg',
                'Order #' .
                    $order->id .
                    ' deleted successfully - ' .
                    $order->vehicle->niceName(),
            );
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
        return redirect()
            ->route('order_bank')
            ->with('successMsg', 'Order successfully duplicated');
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
            'status' => [1, 4, 10, 11, 12, 13, 14, 15],
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
        $deliveryDate = new DateTime($order->delivery_date);
        if (
            $order->broker_accepted &&
            $order->dealeraccepted &&
            $order->admin_accepted
        ) {
            $dateConfirmed = $deliveryDate->format('d/m/Y');
        } else {
            $dateConfirmed = 'TBC';
        }

        $vehicleDetails = [
            [
                'Manufacturer Order Ref' => $order->vehicle->ford_order_number,
                'Order Date' => $created->format('d/m/Y'),
                'Delivery Date' => $dateConfirmed,
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
                'Broker' => $order->broker->company_name ?? '--',
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
        ];

        $pdf = app('dompdf.wrapper');

        $pdf->loadView('pdf', $data);

        //return $pdf->stream();
        return $pdf->download('leden-order-' . $order->id . '.pdf');
    }
}
