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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        //
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
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param Order $order
     */
    public function show(Order $order)
    {
        return view('order.show', ['order' => $order]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Order $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        return view('order.edit', ['order' => $order]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param Order $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
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
     * @param  \Illuminate\Http\Request  $request
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
        return view('order.deliveries', ['status' => [3, 6]]);
    }

    public function dateAccept(Order $order)
    {
        if (Auth::user()->role == 'admin') {
            $order->update([
                'admin_accepted' => 1,
            ]);
        } elseif (Auth::user()->role == 'dealer') {
            $order->update([
                'dealer_accepted' => 1,
            ]);
        } else {
            $order->update([
                'broker_accepted' => 1,
            ]);
        }

        $dealers = User::where('role', 'dealer')
            ->where('company_id', $order->dealer_id)
            ->get();
        $brokers = User::where('role', 'broker')
            ->where('company_id', $order->dealer_id)
            ->get();
        $users = User::where('company_id', 1)
            ->orWhere('company_id', $order->dealer_id)
            ->orWhere('company_id', $order->broker_id)
            ->get();

        if (
            $order->admin_accepted == 1 &&
            $order->broker_accepted == 1 &&
            $order->dealer_accepted == 0
        ) {
            // email to dealer to accept delivery time
            $this->DateAcceptEmail($order->dealer_id, $order);

            $message =
                'A proposed delivery date for Order #' .
                $order->id .
                ' needs your attention';
            $type = 'vehicle';

            Notification::send(
                $dealers,
                new notifications($message, $order->id, $type),
            );
        } elseif (
            $order->admin_accepted == 1 &&
            $order->broker_accepted == 0 &&
            $order->dealer_accepted == 1
        ) {
            // Email to Broker to accept delivery time
            $this->DateAcceptEmail($order->broker_id, $order);

            $this->DateAcceptEmail($order->dealer_id, $order);

            $message =
                'A proposed delivery date for Order #' .
                $order->id .
                ' needs your attention';
            $type = 'vehicle';

            Notification::send(
                $brokers,
                new notifications($message, $order->id, $type),
            );
        } elseif (
            $order->admin_accepted == 1 &&
            $order->broker_accepted == 1 &&
            $order->dealer_accepted == 1
        ) {
            // Email to all parties to say delivery date is confirmed

            $message =
                'A proposed delivery date for Order #' .
                $order->id .
                ' has been accepted by all parties';
            $type = 'vehicle';

            Notification::send(
                $users,
                new notifications($message, $order->id, $type),
            );

            if (env('MAIL_ENABLE')) {
                foreach ($users as $user) {
                    $mail_data = [
                        'name' => $user->firstname,
                        'content' =>
                            'Order #' .
                            $order->id .
                            ' all parties have agreed to the proposed delivery date.',
                        'url' => ENV('APP_URL') . '/orders/view/' . $order->id,
                        'order_id' => $order->id,
                        'email' => $user->email,
                        'name' => $user->firstname . ' ' . $user->lastname,
                    ];

                    Mail::send('mail', $mail_data, function ($message) use (
                        $mail_data,
                    ) {
                        $message
                            ->to($mail_data['email'], $mail_data['name'])
                            ->subject(
                                'Order #' .
                                    $mail_data['order_id'] .
                                    ' \'s delivery date has been approved',
                            );
                        $message->from(
                            ENV('MAIL_FROM_ADDRESS'),
                            ENV('MAIL_FROM_NAME'),
                        );
                    });
                }
            }
        }

        return redirect()
            ->route('order.show', $order->id)
            ->with(
                'successMsg',
                'You have accepted the proposed delivery date',
            );
    }

    public function showDateChange(Order $order)
    {
        return view('order.date-change', [
            'order' => $order,
        ]);
    }

    public function storeDateChange(
        Request $request,
        Order $order,
    ): RedirectResponse {
        $this->validate($request, [
            'delivery_date' => 'required',
        ]);

        $oldDealer = $order->dealer_accepted;
        $oldBroker = $order->broker_accepted;

        $order->delivery_date = Carbon::createFromFormat(
            'd/m/Y',
            $request->delivery_date,
        );

        if (Auth::user()->role == 'admin') {
            $order->admin_accepted = 1;
            $order->dealer_accepted = 0;
            $order->broker_accepted = 0;
        } elseif (Auth::user()->role == 'dealer') {
            $order->admin_accepted = 0;
            $order->dealer_accepted = 1;
            $order->broker_accepted = 0;
        } else {
            $order->admin_accepted = 0;
            $order->dealer_accepted = 0;
            $order->broker_accepted = 1;
        }
        $order->save();

        $admin = User::where('company_id', '1');
        $dealers = User::where('role', 'dealer')
            ->where('company_id', $order->dealer_id)
            ->get();
        $brokers = User::where('role', 'broker')
            ->where('company_id', $order->dealer_id)
            ->get();
        $message =
            'A new delivery date has been proposed for Order #' . $order->id;
        $type = 'vehicle';

        if (
            $order->admin_accepted == 0 &&
            $order->broker_accepted == 1 &&
            $order->dealer_accepted == 0
        ) {
            //email to admin to accept delivery time
            $this->DateChangeEmail(1, $order);
            foreach ($admin as $user) {
                $user->notify(new deliveryDateChanged($order));
            }
            //			Notification::send($admin, new notifications($message, $order->id, $type));

            if ($oldDealer == 1) {
                $this->DateChangeEmail($order->dealer_id, $order);
                foreach ($dealers as $dealer) {
                    $dealer->notify(new deliveryDateChanged($order));
                }
                //				Notification::send($dealers, new notifications($message, $order->id, $type));
            }
        } elseif (
            $order->admin_accepted == 1 &&
            $order->broker_accepted == 0 &&
            $order->dealer_accepted == 0
        ) {
            if ($oldDealer == 1) {
                $this->DateChangeEmail($order->dealer_id, $order);
                foreach ($dealers as $dealer) {
                    $dealer->notify(new deliveryDateChanged($order));
                }
                //				Notification::send($dealers, new notifications($message, $order->id, $type));
            } elseif ($oldBroker == 1) {
                $this->DateChangeEmail($order->broker_id, $order);
                foreach ($brokers as $broker) {
                    $broker->notify(new deliveryDateChanged($order));
                }
                //				Notification::send($brokers, new notifications($message, $order->id, $type));
            }
        } elseif (
            $order->admin_accepted == 0 &&
            $order->broker_accepted == 0 &&
            $order->dealer_accepted == 1
        ) {
            $this->DateChangeEmail(1, $order);
            foreach ($admin as $user) {
                $user->notify(new deliveryDateChanged($order));
            }
            //			Notification::send($admin, new notifications($message, $order->id, $type));
            if ($oldBroker == 1) {
                $this->DateChangeEmail($order->broker_id, $order);
                foreach ($dealers as $dealer) {
                    $dealer->notify(new deliveryDateChanged($order));
                }
                //				Notification::send($dealers, new notifications($message, $order->id, $type));
            }
        }

        return redirect()
            ->route('order.show', $order->id)
            ->with(
                'successMsg',
                'You have successfully change the proposed delivery date',
            );
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

    public function DateChangeEmail($company_id, Order $order)
    {
        $users = User::where('company_id', $company_id)->get();

        if (env('MAIL_ENABLE')) {
            foreach ($users as $user) {
                $mail_data = [
                    'content' =>
                        'Order #' .
                        $order->id .
                        ' has had the proposed delivery date changed. Please view this order and accept the delivery date.',
                    'url' => ENV('APP_URL') . '/orders/view/' . $order->id,
                    'order_id' => $order->id,
                    'email' => $user->email,
                    'name' => $user->firstname . ' ' . $user->lastname,
                ];

                Mail::send('mail', $mail_data, function ($message) use (
                    $mail_data,
                ) {
                    $message
                        ->to($mail_data['email'], $mail_data['name'])
                        ->subject(
                            'The proposed delivery date for Order #' .
                                $mail_data['order_id'] .
                                ' has been changed',
                        );
                    $message->from(
                        ENV('MAIL_FROM_ADDRESS'),
                        ENV('MAIL_FROM_NAME'),
                    );
                });
            }
        }
    }

    public function DateAcceptEmail($company_id, Order $order)
    {
        $users = User::where('company_id', $company_id)->get();

        if (env('MAIL_ENABLE')) {
            foreach ($users as $user) {
                $mail_data = [
                    'content' =>
                        'Order #' .
                        $order->id .
                        ' has been reserved and a delivery date has been added. Please view this order and accept the delivery date.',
                    'url' => ENV('APP_URL') . '/orders/view/' . $order->id,
                    'order_id' => $order->id,
                    'email' => $user->email,
                    'name' => $user->firstname . ' ' . $user->lastname,
                ];

                Mail::send('mail', $mail_data, function ($message) use (
                    $mail_data,
                ) {
                    $message
                        ->to($mail_data['email'], $mail_data['name'])
                        ->subject(
                            'Order #' .
                                $mail_data['order_id'] .
                                ' has been reserved',
                        );
                    $message->from(
                        ENV('MAIL_FROM_ADDRESS'),
                        ENV('MAIL_FROM_NAME'),
                    );
                });
            }
        }
    }
}
