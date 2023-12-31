<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\MessageGroup;
use App\Models\Order;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function showMessages()
    {
        $data = Message::select(
            'message.message_group_id',
            'message_group.subject',
            'message_group.last_message_sent',
            DB::raw("CONCAT(lo_s.firstname, ' ', lo_s.lastname) AS sender"),
            DB::raw("CONCAT(lo_r.firstname, ' ', lo_r.lastname) AS recipient"),
        )
            ->leftJoin(
                'message_group',
                'message_group.id',
                'message.message_group_id',
            )
            ->leftJoin('users as s', 's.id', 'message.sender_id')
            ->rightJoin('users as r', 'r.id', 'message.recipient_id')
            ->where(function ($q) {
                $q->where('message.sender_id', Auth::user()->id)->orWhere(
                    'message.recipient_id',
                    Auth::user()->id,
                );
            })
            ->where(
                'message_group.last_message_sent',
                '>=',
                Carbon::now()->subMonths(6),
            )
            ->orderBy('message_group.last_message_sent', 'desc')
            ->groupBy('message.message_group_id')
            ->get();

        return view('messages.index', ['data' => $data]);
    }

    public function showNewMessage()
    {
        $users = User::select('id', 'firstname', 'lastname')->where(
            'id',
            '!=',
            Auth::user()->id,
        );

        if (Auth::user()->role != 'admin') {
            $users->where('role', 'admin');
        }

        $all_users = $users->get();

        $orders = Order::select('id', 'vehicle_id')->with(
            'vehicle:id,make,model,chassis,reg',
            'vehicle.manufacturer:id,name',
        );

        if (Auth::user()->role == 'dealer') {
            $orders
                ->where('dealer_id', Auth::user()->company_id)
                ->orWhere('dealer_id', null);
        } elseif (Auth::user()->role == 'broker') {
            $orders
                ->where('broker_id', Auth::user()->company_id)
                ->orWhere('broker_id', null);
        }

        $all_orders = $orders->get();

        return view('messages.create', [
            'users' => $all_users,
            'orders' => $all_orders,
        ]);
    }

    public function executeNewMessage(Request $request)
    {
        $message_group = [
            'subject' => $request->input('subject'),
            'last_message_sent' => Carbon::now(),
            'order_id' => $request->input('order_id'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];

        $message_group_id = MessageGroup::insertGetId($message_group);

        Message::insert([
            'message' => $request->input('message'),
            'sender_id' => Auth::user()->id,
            'recipient_id' => $request->input('recipient_id'),
            'message_group_id' => $message_group_id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        return redirect()
            ->route('messages')
            ->with('successMsg', 'Your message has been sent successfully');
    }

    public function showMessage(Request $request)
    {
        $recipient_id = Message::select('recipient_id')
            ->where('message_group_id', $request->route('message_group_id'))
            ->where('recipient_read_at', null)
            ->orderBy('created_at', 'desc')
            ->first();

        if (!empty($recipient_id)) {
            if ($recipient_id->recipient_id == Auth::user()->id) {
                Message::where('recipient_read_at', null)
                    ->where('recipient_id', Auth::user()->id)
                    ->where(
                        'message_group_id',
                        $request->route('message_group_id'),
                    )
                    ->update([
                        'recipient_read_at' => Carbon::now(),
                    ]);
            }
        }

        $messages = Message::select(
            'message.id',
            'message.message',
            'message.sender_id',
            'message.recipient_id',
            'users.firstname',
            'users.lastname',
            'users.role',
            'message.created_at',
        )
            ->leftJoin('users', 'users.id', 'message.sender_id')
            ->where('message_group_id', $request->route('message_group_id'))
            ->orderBy('message.created_at', 'desc')
            ->get();

        $message_info = MessageGroup::select(
            'message_group.id as mg_id',
            'message_group.subject',
            'message_group.order_id',
        )
            ->leftJoin('orders', 'orders.id', 'message_group.order_id')
            ->where('message_group.id', $request->route('message_group_id'))
            ->first();

        $recipients = Message::select('recipient_id', 'sender_id')
            ->where('message_group_id', $request->route('message_group_id'))
            ->orderBy('created_at', 'asc')
            ->first();

        return view('messages.show', [
            'message_info' => $message_info,
            'messages' => $messages,
            'recipients' => $recipients,
        ]);
    }
}
