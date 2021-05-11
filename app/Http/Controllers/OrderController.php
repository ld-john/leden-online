<?php

namespace App\Http\Controllers;

use App\Company;
use App\Order;
use App\OrderLegacy;
use App\User;
use App\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
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
		//
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
	 * @param  \App\Order  $order
	 */
	public function show(Order $order)
	{
		return view('order.show', ['order' => $order]);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\Order  $order
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Order $order)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Order  $order
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Order $order)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Order  $order
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Order $order)
	{
		//
	}

	public function duplicate( Request $request, Order $order ){
        //
    }

	public function showOrderBank(Request $request)
	{
		if ($request->ajax()) {
			$data = Order::whereHas('vehicle', function($q){
				$q->whereIn('vehicle_status', [1,2,4,10,11]);
			});
			$data->select(
				'id',
				'vehicle_id',
				'order_ref',
				'due_date',
				'customer_id',
				'broker_ref',
				'broker_id',
				'dealer_id'
			)
				->with([
					'vehicle',
					'customer',
					'broker',
					'dealer']);
			if (Auth::user()->role == 'dealer') {
				$data->where('dealer_id', Auth::user()->company_id);
			} elseif (Auth::user()->role == 'broker') {
				$data->where('broker_id', Auth::user()->company_id);
			}
			$data->get();
			return Datatables::of($data)
				->addColumn('action', function ($row) {
					$btn = '<a href="/orders/view/' . $row->id . '" class="btn btn-sm btn-primary"><i class="far fa-eye fa-fw"></i></a>';

                    if (Auth::user()->role == 'admin') {
                        $btn .= '<a href="/orders/duplicate/' . $row->id . '" class="btn btn-sm btn-primary ml-2"><i class="far fa-copy fa-fw"></i></a>';
                    }
					return $btn;
				})
				->rawColumns(['vehicle_due_date', 'customer', 'broker_name', 'dealer_name', 'action'])
				->make(true);
		}
		return view('order.index', ['title' => 'Order Bank', 'active_page' => 'order-bank', 'route' => 'order_bank']);
	}

	public function completedOrders(Request $request)
	{
		if ($request->ajax()) {

			$data = Order::whereHas('vehicle', function($q){
				$q->whereIn('vehicle_status', [7]);
			});
			$data->select(
				'id',
				'vehicle_id',
				'order_ref',
				'due_date',
				'customer_id',
				'broker_ref',
				'broker_id',
				'dealer_id'
			)
				->with([
					'vehicle',
					'customer',
					'broker',
					'dealer']);
			if (Auth::user()->role == 'dealer') {
				$data->where('dealer_id', Auth::user()->company_id);
			} elseif (Auth::user()->role == 'broker') {
				$data->where('broker_id', Auth::user()->company_id);
			}
			$data->get();

			return Datatables::of($data)
				->addColumn('action', function ($row) {
					$btn = '<a href="/orders/view/' . $row->id . '" class="btn btn-primary"><i class="far fa-eye"></i> View</a>';

					return $btn;
				})
				->make(true);
		}

		return view('order.index', ['title' => 'Completed Orders', 'active_page' => 'completed-orders', 'route' => 'completed_orders']);
	}

	public function showManageDeliveries(Request $request)
	{
		if ($request->ajax()) {

			$data = Order::whereHas('vehicle', function($q){
				$q->whereIn('vehicle_status', [3,4,6]);
			});

			$data->select('id', 'vehicle_id', 'order_ref','delivery_date','customer_id', 'broker_ref', 'broker_id', 'dealer_id')->with([
				'vehicle',
				'customer',
				'broker',
				'dealer']);

			if (Auth::user()->role == 'dealer') {
				$data->where('dealer_id', Auth::user()->company_id);
			} elseif (Auth::user()->role == 'broker') {
				$data->where('broker_id', Auth::user()->company_id);
			}

			$data->get();

			return Datatables::of($data)
				->editColumn('vehicle_status', function ($row) {

					switch ($row->vehicle_status) {
						case 3:
							return 'Ready for delivery';
						case 4:
							return 'Factory Order';
						case 6:
							return 'Delivery Booked';
					}
				})
				->addColumn('action', function ($row) {
					$btn = '<a href="/orders/view/' . $row->id . '" class="btn btn-sm btn-primary"><i class="far fa-eye"></i> View</a>';

					return $btn;
				})
				->rawColumns(['vehicle_due_date', 'customer', 'broker_name', 'dealer_name', 'action'])
				->make(true);
		}

		return view('order.deliveries');
	}


	public function dateAccept(Order $order)
	{
		if (Auth::user()->role == 'admin') {
			$order->update([
				'admin_accepted' => 1
			]);
		} elseif (Auth::user()->role == 'dealer') {
			$order->update([
				'dealer_accepted' => 1
			]);
		} else {
			$order->update([
				'broker_accepted' => 1
			]);
		}

		if ($order->admin_accepted == 1 && $order->broker_accepted == 1 && $order->dealer_accepted == 0) {
			// email to dealer to accept delivery time
			$this->DateAcceptEmail($order->dealer_id, $order);
		} elseif ($order->admin_accepted == 1 && $order->broker_accepted == 0 && $order->dealer_accepted == 1) {
			// Email to Broker to accept delivery time
			$this->DateAcceptEmail($order-broker_id, $order);
		} elseif ($order->admin_accepted == 1 && $order->broker_accepted == 1 && $order->dealer_accepted == 1) {
			// Email to all parties to say delivery date is confirmed
			$users = User::where('company_id', 1)
				->orWhere('company_id', $order->dealer_id)
				->orWhere('company_id', $order->broker_id)
				->get();

			foreach ($users as $user) {
				$mail_data = [
					'name' => $user->firstname,
					'content' => 'Order #' . $order->id . ' all parties have agreed to the proposed delivery date.',
					'url' => ENV('APP_URL') . '/orders/view/' . $order->id,
					'order_id' => $order->id,
					'email' => $user->email,
					'name' => $user->firstname . ' ' . $user->lastname,
				];

				Mail::send('mail', $mail_data, function ($message) use ($mail_data) {
					$message->to($mail_data['email'], $mail_data['name'])
						->subject('Order #' . $mail_data['order_id'] . ' \'s delivery date has been approved');
					$message->from(ENV('MAIL_FROM_ADDRESS'), ENV('MAIL_FROM_NAME'));
				});
			}
		}



		return redirect()->route('order.show', $order->id)->with('successMsg', 'You have accepted the proposed delivery date');
	}

	public function showDateChange(Order $order)
	{
		return view('order.date-change', [
			'order' => $order
		]);
	}

	public function storeDateChange(Request $request, Order $order)
	{
		$this->validate($request, [
			'delivery_date' => 'required'
		]);

		$old = $order;

		$order->delivery_date = Carbon::createFromFormat('d/m/Y', $request->delivery_date);;

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

		if ($order->admin_accepted == 0 && $order->broker_accepted == 1 && $order->dealer_accepted == 0) {
			//email to admin to accept delivery time
			$this->DateChangeEmail(1, $order);
			if ($old->dealer_accepted == 1) {
				$this->DateChangeEmail($order->dealer_id, $order);
			}

		} elseif ($order->admin_accepted == 1 && $order->broker_accepted == 0 && $order->dealer_accepted == 0) {
			if ($old->dealer_accepted == 1) {
				$this->DateChangeEmail($order->dealer_id, $order);

			} elseif ($old->broker_accepted == 1) {
				$this->DateChangeEmail($order->broker_id, $order);
			}
		} elseif ($order->admin_accepted == 0 && $order->broker_accepted == 0 && $order->dealer_accepted == 1) {
			$this->DateChangeEmail(1, $order);
			if ($old->broker_accepted == 1) {
				$this->DateChangeEmail($order->broker_id, $order);
			}
		}

		return redirect()->route('order.show', $order->id)->with('successMsg', 'You have successfully change the proposed delivery date');

	}

	public function DateChangeEmail($company_id, Order $order)
	{
		$users = User::where('company_id', $company_id)->get();

		foreach ($users as $user) {
			$mail_data = [
				'content' => 'Order #' . $order->id . ' has had the proposed delivery date changed. Please view this order and accept the delivery date.',
				'url' => ENV('APP_URL') . '/orders/view/' . $order->id,
				'order_id' => $order->id,
				'email' => $user->email,
				'name' => $user->firstname . ' ' . $user->lastname,
			];

			Mail::send('mail', $mail_data, function ($message) use ($mail_data) {
				$message->to($mail_data['email'], $mail_data['name'])
					->subject('The proposed delivery date for Order #' . $mail_data['order_id'] . ' has been changed');
				$message->from(ENV('MAIL_FROM_ADDRESS'), ENV('MAIL_FROM_NAME'));
			});
		}
	}

	public function DateAcceptEmail($company_id, Order $order)
	{
		$users = User::where('company_id', $company_id)->get();

		foreach ($users as $user) {
			$mail_data = [
				'content' => 'Order #' . $order->id . ' has been reserved and a delivery date has been added. Please view this order and accept the delivery date.',
				'url' => ENV('APP_URL') . '/orders/view/' . $order->id,
				'order_id' => $order->id,
				'email' => $user->email,
				'name' => $user->firstname . ' ' . $user->lastname,
			];

			Mail::send('mail', $mail_data, function ($message) use ($mail_data) {
				$message->to($mail_data['email'], $mail_data['name'])
					->subject('Order #' . $mail_data['order_id'] . ' has been reserved');
				$message->from(ENV('MAIL_FROM_ADDRESS'), ENV('MAIL_FROM_NAME'));
			});
		}
	}

}
