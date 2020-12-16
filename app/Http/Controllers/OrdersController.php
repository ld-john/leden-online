<?php

namespace App\Http\Controllers;

use App\FitOption;
use App\InvoiceCompany;
use App\Notifications\notifications;
use App\Order;
use App\OrderUpload;
use App\RegistrationCompany;
use App\User;
use Auth;
use Carbon\Carbon;
use DB;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Mail;
use PDF;
use Validator;
use Yajra\DataTables\Facades\DataTables;

class OrdersController extends Controller
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

    public static function getFieldValues($field_name)
    {
        $values = DB::table('order')
            ->select($field_name . ' as value')
            ->groupBy('value')
            ->get();

        $viable_values = [];
        foreach ($values as $record) {
            $check = DB::table('order_option_excludes')
                ->select('field_name', 'field_value')
                ->where('field_name', $field_name)
                ->where('field_value', $record->value)
                ->first();

            if ($check == null && !is_null($record->value) && !empty($record->value)) {
                array_push($viable_values, $record->value);
            }
        }

        return $viable_values;
    }

    /* Show Create Order */

    public static function getCompanyInfo($type)
    {
        $company_info = DB::table('company')
            ->select('id', 'company_name')
            ->where('company_type', $type)
            ->get();

        return $company_info;
    }

    /* Get previous field values for each of input */

    public function showCreateOrder()
    {
        if (Helper::roleCheck(Auth::user()->id)->role != 'admin') {
            return view('unauthorised');
        } else {
            return view('edit-order', $this->addEditVars());
        }
    }

    /* Get Company details */

    protected function addEditVars($id = null)
    {
        $order = new Order();
        $editMode = false;
        $route = 'add_order';
        $dealer_fit_options = [];
        $factory_fit_options = [];
        $attachments = [];
        $title = 'Create Order';
        $activePage = 'create-order';

        if ($id) {
            $order = Order::find($id);
            $route = 'order.update';
            $editMode = true;
            $dealer_fit_options = $this->getOrderFitOptions($order->id, 'factory');
            $factory_fit_options = $this->getOrderFitOptions($order->id, 'dealer');
            $attachments = $this->getAttachments($order->id);
            $title = 'Edit Order';
            $activePage = 'edit-order';
        }

        return [
            'title' => $title,
            'activePage' => $activePage,
            'edit_mode' => $editMode,
            'post_route' => $route,
            'order_details' => $order,
            'attachments' => $attachments,
            'factory_fit_options' => $dealer_fit_options,
            'dealer_fit_options' => $factory_fit_options,
            'invoice_companies' => InvoiceCompany::orderBy('name')->get(),
            'registration_companies' => RegistrationCompany::orderBy('name')->get(),
            'factory_options' => $this->getFitOptions('factory', $id),
            'dealer_options' => $this->getFitOptions('dealer', $id)
        ];
    }

    /* Get fit options from database */

    public function getOrderFitOptions($order_id, $type)
    {
        $fit_options = DB::table('fit_options_connector')
            ->select('fit_options_connector.fit_option_id')
            ->join('fit_options', 'fit_options.id', 'fit_options_connector.fit_option_id')
            ->where('order_id', $order_id)
            ->where('option_type', $type)
            ->get();

        $options = [];
        foreach ($fit_options as $value) {
            array_push($options, $value->fit_option_id);
        }

        return $options;
    }

    /* Add field_value to exclude list */

    public function getAttachments($order_id)
    {
        return OrderUpload::where('order_id', $order_id)->get();
    }

    /* Check if value is on exclude list */


    /* Add Order to the system */

    public function getFitOptions($type, $order_id = null)
    {
        $options = FitOption::select('fit_options.*')->leftJoin('fit_options_connector', 'fit_options.id', '=', 'fit_options_connector.fit_option_id')->
        where('option_type', $type);

        if($order_id){
            $options->where(function($q) use($order_id){
                $q->where('fit_options.created_at', '>=', Carbon::now()->subMonths(6))
                    ->orWhere('fit_options_connector.order_id', $order_id);
            });
        }else{
            $options->where('fit_options.created_at', '>=', Carbon::now()->subMonths(6));
        }

        return $options
            ->orderBy('option_name')
            ->groupBy('fit_options.id')
            ->get();
    }

    /* Show Completed Orders */

    public function executeAddExcludeField(Request $request)
    {
        $field_name = $request->get('fieldName');
        $field_value = $request->get('fieldValue');

        DB::table('order_option_excludes')->insert([
            'field_name' => $field_name,
            'field_value' => $field_value,
            'created_at' => Carbon::now()
        ]);

        return response('success', 200);
    }

    /* Show Leden Stock and Pipeline */

    public function executeAddOrder(Request $request)
    {

        // Place all standard inputs into their own array
        foreach ($request->post() as $field => $value) {
            if (!is_array($value) && $field != "_token" && $field != "file") {
                $order[$field] = $value;
            }
        }

        // Convert dates for DB
        is_null($order['due_date']) ? $order['due_date'] = null : $order['due_date'] = Carbon::createFromFormat('d/m/Y',
            $order['due_date']);
        is_null($order['delivery_date']) ? $order['delivery_date'] = null : $order['delivery_date'] = Carbon::createFromFormat('d/m/Y',
            $order['delivery_date']);
        is_null($order['vehicle_registered_on']) ? $order['vehicle_registered_on'] = null : $order['vehicle_registered_on'] = Carbon::createFromFormat('d/m/Y',
            $order['vehicle_registered_on']);
        $order['created_at'] = Carbon::now();

        // Add $order array to the order table and return the rows ID
        $order_id = DB::table('order')->insertGetId($order);

        $options = [];

        // Add each new factory fit option to the fit_options table and add
        // their ID to the $options array
        $factory_option_price = $request->input('factory_option_price_new');
        if ($factory_option_price[0] != null) {
            foreach ($request->input('factory_option_new') as $id => $field) {
                $options[] = DB::table('fit_options')->insertGetId([
                    'option_type' => 'factory',
                    'option_name' => $field,
                    'option_price' => $factory_option_price[$id],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }

        // Add each new dealer fit option to the fit_options table and add
        // their ID to the $options array
        $dealer_option_price = $request->input('dealer_option_price_new');
        if ($dealer_option_price[0] != null) {
            foreach ($request->input('dealer_option_new') as $id => $field) {
                $options[] = DB::table('fit_options')->insertGetId([
                    'option_type' => 'dealer',
                    'option_name' => $field,
                    'option_price' => $dealer_option_price[$id],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }

        // Add other factory options to the $options array
        if ($request->input('factory_option') != null) {
            foreach ($request->input('factory_option') as $id => $field_id) {
                array_push($options, $field_id);
            }
        }
        // Add other dealer options to the $options array
        if ($request->input('dealer_option') != null) {
            foreach ($request->input('dealer_option') as $id => $field_id) {
                array_push($options, $field_id);
            }
        }

        // Now add the $options to the fit_options_connector table
        foreach ($options as $id => $option) {
            DB::table('fit_options_connector')->insert([
                'order_id' => $order_id,
                'fit_option_id' => $option,
                'show_option' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        // Upload file if one exitsts
        if ($files = $request->file('file')) {
            $destinationPath = 'user-uploads/order-attachments/'; // upload path
            $filename = date('YmdHis') . '_' . str_replace(' ', '_', $files->getClientOriginalName());
            $filetype = $files->getClientOriginalExtension();
            $files->move($destinationPath, $filename);

            DB::table('order_uploads')->insert([
                'order_id' => $order_id,
                'file_name' => $filename,
                'file_type' => $filetype,
                'uploaded_by' => Auth::user()->id,
                'created_at' => Carbon::now()
            ]);
        }

        // Send notification to Brokers
        if ($request->input('broker') == '') {
            // Get all Brokers and send notification
            $brokers = User::where('role', 'broker')
                ->get();

            $message = 'A new ' . $request->input('vehicle_make') . ' ' . $request->input('vehicle_model') . ' has been added';
            $type = 'vehicle';

            Notification::send($brokers, new notifications($message, $order_id, $type));
        } else {
            $broker = User::where('company_id', $request->get('broker'))
                ->get();

            $message = 'A new ' . $request->input('vehicle_make') . ' ' . $request->input('vehicle_model') . ' has been added and associated to your company';
            $type = 'vehicle';

            Notification::send($broker, new notifications($message, $order_id, $type));
        }

        return view('edit-order', $this->addEditVars())->with('successMsg', 'Your order has been added successfully!');
    }

    /* Show Ford Stock and Pipeline */

    public function showCompletedOrders(Request $request)
    {
        if ($request->ajax()) {

            $data = DB::table('order')
                ->select('id', 'vehicle_make', 'vehicle_model', 'vehicle_reg', 'vehicle_type', 'chassis', 'customer_name','company_name', 'preferred_name')
                ->where('vehicle_status', 7);

            if (Helper::roleCheck(Auth::user()->id)->role == 'dealer') {
                $data->where('dealership', Auth::user()->company_id);
            }
            if (Helper::roleCheck(Auth::user()->id)->role == 'broker') {
                $data->where('broker', Auth::user()->company_id);
            }

            $data->get();

            return Datatables::of($data)
                ->addColumn('action', function ($row) {
                    $btn = '<a href="/orders/view/' . $row->id . '" class="btn btn-primary"><i class="far fa-eye"></i> View</a>';

                    return $btn;
                })
                ->addColumn('customer', function ($row) {
                    if ($row->preferred_name == 'customer') {
                        $name = $row->customer_name;
                    } else {
                        $name = $row->company_name;
                    }

                    return $name;
                })
                ->make(true);
        }

        return view('completed-orders');
    }

    /* Create orders page */

    public function showPipeline(Request $request)
    {
        if ($request->ajax()) {

            $data = Order::select('id', 'vehicle_make', 'vehicle_model', 'vehicle_derivative', 'vehicle_reg',
                'vehicle_type',
                'vehicle_doors', 'vehicle_engine', 'vehicle_colour')
                ->where('show_in_ford_pipeline', false)
                ->where('customer_name', null)
                ->where('company_name', null);

            if (Helper::roleCheck(Auth::user()->id)->role == 'dealer') {
                $data->where('hide_from_dealer', false);
            }
            if (Helper::roleCheck(Auth::user()->id)->role == 'broker') {
                $data->where('hide_from_broker', false);
            }

            $data->get();

            return Datatables::of($data)
                ->addColumn('action', function ($row) {
                    if (Helper::roleCheck(Auth::user()->id)->role != 'admin') {
                        $btn = '<a href="/orders/view/' . $row->id . '" class="btn btn-sm btn-primary"><i class="far fa-eye"></i> View</a>';
                    } else {
                        $btn = '<a href="/orders/edit/' . $row->id . '" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i> Edit</a>';

                        $btn .= '<a href="/orders/delete/leden/' . $row->id . '" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Delete</a>';
                        //$btn .= '<div class="btn-group ml-2"><label class="btn btn-sm btn-danger mb-0"><input type="checkbox" autocomplete="off" value="' . $row->id . '"> Select </label></div>';
                    }

                    return '<div class="btn-toolbar"><div class="btn-group">' . $btn . '</div></div>';
                })
                ->addColumn('options', function ($row) {
                    return DB::table('fit_options_connector')->where('order_id', $row->id)->count();
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('pipeline');
    }

    /* Create orders page */

    public function showFordPipeline(Request $request)
    {
        if ($request->ajax()) {

            $data = Order::select('id', 'vehicle_make', 'vehicle_model', 'vehicle_derivative', 'vehicle_reg',
                'vehicle_type',
                'vehicle_doors', 'vehicle_engine', 'vehicle_colour')
                ->where('show_in_ford_pipeline', true)
                ->where('customer_name', null)
                ->where('company_name', null);

            if (Helper::roleCheck(Auth::user()->id)->role == 'dealer') {
                $data->where('hide_from_dealer', false);
            }
            if (Helper::roleCheck(Auth::user()->id)->role == 'broker') {
                $data->where('hide_from_broker', false);
            }

            $data->get();

            return Datatables::of($data)
                ->addColumn('action', function ($row) {
                    if (Helper::roleCheck(Auth::user()->id)->role != 'admin') {
                        $btn = '<a href="/orders/view/' . $row->id . '" class="btn btn-sm btn-primary"><i class="far fa-eye"></i> View</a>';
                    } else {
                        $btn = '<a href="/orders/edit/' . $row->id . '" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i> Edit</a>';

                        $btn .= '<a href="/orders/delete/ford/' . $row->id . '" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Delete</a>';
                    }

                    return '<div class="btn-toolbar"><div class="btn-group">' . $btn . '</div></div>';
                })
                ->addColumn('options', function ($row) {
                    return '-';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('ford-pipeline');
    }

    public function executeDeleteSelected()
    {
        $ids = request()->input('ids');

        if ($ids) {
            foreach ($ids as $id) {
                $order = Order::find($id);

                if ($order) {
                    $order->delete();
                }
            }
        }

        return '';
    }

    public function showOrderBank(Request $request)
    {
        if ($request->ajax()) {
            $data = Order::select('id', 'vehicle_model', 'vehicle_derivative', 'order_ref', 'vehicle_reg', 'due_date',
                    'customer_name', 'company_name', 'preferred_name', 'broker_order_ref', 'broker', 'dealership')
                ->whereIn('vehicle_status', [1,2,4,10,11]) //In Stock, Orders Placed, Factory Order, Europe VHC, UK VHC
                ->where(function ($query) {
                    $query->where('customer_name', '!=', null)
                        ->orWhere('company_name', '!=', null);
                });
            //->orwhere('customer_name', '!=', null)
            //->orWhere('company_name', '!=', null);

            if (Helper::roleCheck(Auth::user()->id)->role == 'dealer') {
                $data->where('dealership', Auth::user()->company_id);
            } elseif (Helper::roleCheck(Auth::user()->id)->role == 'broker') {
                $data->where('broker', Auth::user()->company_id);
            }

            //$data->get();

            return Datatables::of($data)
                ->addColumn('vehicle_due_date', function ($row) {
                    if($row->due_date){
                        return date('d/m/yy', strtotime($row->due_date));
                    }

                    return 'TBC';
                })
                ->addColumn('customer', function ($row) {
                    if ($row->preferred_name == 'customer') {
                        $name = $row->customer_name;
                    } else {
                        $name = $row->company_name;
                    }

                    return $name;
                })
                ->addColumn('broker_name', function ($row) {
                    if (!is_null($row->broker)) {
                        $broker_name = DB::table('company')
                            ->select('company_name')
                            ->where('id', $row->broker)
                            ->first();

                        return $broker_name->company_name;
                    } else {
                        return null;
                    }
                })
                ->addColumn('dealer_name', function ($row) {
                    if (!is_null($row->dealership)) {
                        $dealer_name = DB::table('company')
                            ->select('company_name')
                            ->where('id', $row->dealership)
                            ->first();

                        return $dealer_name->company_name;
                    } else {
                        return null;
                    }
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="/orders/view/' . $row->id . '" class="btn btn-sm btn-primary"><i class="far fa-eye"></i> View</a>';

                    return $btn;
                })
                ->rawColumns(['vehicle_due_date', 'customer', 'broker_name', 'dealer_name', 'action'])
                ->make(true);
        }

        return view('order-bank');
    }

    public function showManageDeliveries(Request $request)
    {
        if ($request->ajax()) {

            $data = DB::table('order')
                ->select('id', 'vehicle_model', 'vehicle_status', 'order_ref', 'vehicle_reg', 'delivery_date',
                    'customer_name', 'company_name', 'preferred_name', 'broker_order_ref', 'broker', 'dealership')
                ->whereIn('vehicle_status', [3, 6]);

            if (Helper::roleCheck(Auth::user()->id)->role == 'dealer') {
                $data->where('dealership', Auth::user()->company_id);
            } elseif (Helper::roleCheck(Auth::user()->id)->role == 'broker') {
                $data->where('broker', Auth::user()->company_id);
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
                /*->addColumn('vehicle_due_date', function ($row) {
                    return date('d/m/yy', strtotime($row->due_date));
                })*/
                ->editColumn('delivery_date', function ($row) {
                    if ($row->delivery_date) {
                        return date('d/m/Y', strtotime($row->delivery_date));
                    }

                    return '-';
                })
                ->addColumn('customer', function ($row) {
                    if ($row->preferred_name == 'customer') {
                        $name = $row->customer_name;
                    } else {
                        $name = $row->company_name;
                    }

                    return $name;
                })
                ->addColumn('broker_name', function ($row) {
                    if (!is_null($row->broker)) {
                        $broker_name = DB::table('company')
                            ->select('company_name')
                            ->where('id', $row->broker)
                            ->first();

                        return $broker_name->company_name;
                    } else {
                        return null;
                    }
                })
                ->addColumn('dealer_name', function ($row) {
                    if (!is_null($row->dealership)) {
                        $dealer_name = DB::table('company')
                            ->select('company_name')
                            ->where('id', $row->dealership)
                            ->first();

                        return $dealer_name->company_name;
                    } else {
                        return null;
                    }
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="/orders/view/' . $row->id . '" class="btn btn-sm btn-primary"><i class="far fa-eye"></i> View</a>';

                    return $btn;
                })
                ->rawColumns(['vehicle_due_date', 'customer', 'broker_name', 'dealer_name', 'action'])
                ->make(true);
        }

        return view('deliveries');
    }

    public function showOrder(Request $request, Order $order)
    {
        if ($this->canAccessOrder($order->id) == false) {
            return view('unauthorised');
        }

        return view('view-order', [
            'order' => $order,
            'attachments' => $this->getAttachments($order->id),
        ]);
    }

    public function canAccessOrder($order_id)
    {
        $order = DB::table('order')
            ->select('id')
            ->where('id', $order_id)
            ->where('customer_name', null)
            ->orWhere('company_name', null)
            ->first();

        if (is_null($order->id) && Helper::roleCheck(Auth::user()->id)->role != 'admin') {
            return false;
        } else {
            return true;
        }
    }

    public function showEditOrder(Request $request, Order $order)
    {
        if (Helper::roleCheck(Auth::user()->id)->role == 'admin') {
            return view('edit-order', $this->addEditVars($order->id));
        } elseif (Helper::roleCheck(Auth::user()->id)->role == 'dealer') {
            return view('edit-order-dealer', [
                'order_details' => $this->getOrderInfo($order->id),
            ]);
        } else {
            return view('unauthorised');
        }
    }

    public function getOrderInfo($order_id)
    {
        $order_details = DB::table('order')
            ->where('id', $order_id)
            ->first();

        return $order_details;
    }

    public function executeEditOrder(Request $request, Order $order)
    {
        if (Helper::roleCheck(Auth::user()->id)->role == 'admin') {
            // Place all standard inputs into their own array
            foreach ($request->post() as $field => $value) {
                if (!is_array($value) && $field != "_token" && $field != "file") {
                    $order_details[$field] = $value;
                }
            }

            $this->validate($request, [
                'vehicle_make' => 'required',
                'vehicle_model' => 'required',
                'vehicle_type' => 'required',
                'vehicle_derivative' => 'required',
                'vehicle_engine' => 'required',
                'vehicle_trans' => 'required',
                'vehicle_fuel_type' => 'required',
                'vehicle_colour' => 'required',
                'vehicle_body' => 'required',
                'vehicle_trim' => 'required',
                'vehicle_status' => 'required',
                'due_date' => 'nullable|date_format:d/m/Y',
                'delivery_date' => 'nullable|date_format:d/m/Y',
                'vehicle_registered_on' => 'nullable|date_format:d/m/Y',
            ]);

            $order_details['due_date'] = null;
            $order_details['delivery_date'] = null;
            $order_details['vehicle_registered_on'] = null;



            if ($request->input('due_date')) {
                $order_details['due_date'] = Carbon::createFromFormat('d/m/Y', $request->input('due_date'));
            }



            if ($request->input('delivery_date')) {
                $order_details['delivery_date'] = Carbon::createFromFormat('d/m/Y',$request->input('delivery_date'));
            }

            if ($request->input('vehicle_registered_on')) {
                $order_details['vehicle_registered_on'] = Carbon::createFromFormat('d/m/Y',$request->input('vehicle_registered_on'));
            }
            if ($request->input('finance_commission_paid')) {
                $order_details['finance_commission_paid'] = Carbon::createFromFormat('d/m/Y',$request->input('finance_commission_paid'));
            }
            if ($request->input('invoice_broker_paid')) {
                $order_details['invoice_broker_paid'] = Carbon::createFromFormat('d/m/Y',$request->input('invoice_broker_paid'));
            }

            if ($request->input('commission_broker_paid')) {
                $order_details['commission_broker_paid'] = Carbon::createFromFormat('d/m/Y',$request->input('commission_broker_paid'));
            }

            //dd($order_details);

            // Update order details
            $order->update($order_details);

            $options = [];

            // Add each new factory fit option to the fit_options table and add
            // their ID to the $options array
            $factory_option_price = $request->input('factory_option_price_new');
            if ($factory_option_price[0] != null) {
                foreach ($request->input('factory_option_new') as $id => $field) {
                    if ($field && isset($factory_option_price[$id])) {

                        $factory_option = FitOption::where('option_type', 'factory')
                            ->where('option_name', $field)
                            ->where('option_price', $factory_option_price[$id])
                            ->first();

                        if (!$factory_option) {
                            $factory_option = new FitOption();
                            $factory_option->option_type = 'factory';
                            $factory_option->option_name = $field;
                            $factory_option->option_price = $factory_option_price[$id];
                        }

                        $factory_option->save();
                        $options[$factory_option->id] = $factory_option->id;
                    }
                }
            }

            // Add each new dealer fit option to the fit_options table and add
            // their ID to the $options array
            $dealer_option_price = $request->input('dealer_option_price_new');
            if ($dealer_option_price[0] != null) {
                foreach ($request->input('dealer_option_new') as $id => $field) {
                    if ($field && isset($dealer_option_price[$id])) {
                        $dealer_option = FitOption::where('option_type', 'dealer')
                            ->where('option_name', $field)
                            ->where('option_price', $dealer_option_price[$id])
                            ->first();

                        if (!$dealer_option) {
                            $dealer_option = new FitOption();
                            $dealer_option->option_type = 'dealer';
                            $dealer_option->option_name = $field;
                            $dealer_option->option_price = $dealer_option_price[$id];
                        }

                        $dealer_option->save();
                        $options[$dealer_option->id] = $dealer_option->id;
                    }

                }
            }

            // Add other factory options to the $options array
            if ($request->input('factory_option') != null) {
                foreach ($request->input('factory_option') as $id => $field_id) {
                    array_push($options, $field_id);
                }
            }
            // Add other dealer options to the $options array
            if ($request->input('dealer_option') != null) {
                foreach ($request->input('dealer_option') as $id => $field_id) {
                    array_push($options, $field_id);
                }
            }

            // Delete current options connection
            DB::table('fit_options_connector')
                ->where('order_id', $order->id)
                ->delete();

            // Now add the $options to the fit_options_connector table again
            foreach ($options as $id => $option) {
                DB::table('fit_options_connector')->insert([
                    'order_id' => $order->id,
                    'fit_option_id' => $option,
                    'show_option' => true,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            }

            // Upload file if one exitsts
            if ($files = $request->file('file')) {
                $destinationPath = 'user-uploads/order-attachments/'; // upload path
                $filename = date('YmdHis') . '_' . str_replace(' ', '_', $files->getClientOriginalName());
                $filetype = $files->getClientOriginalExtension();
                $files->move($destinationPath, $filename);

                DB::table('order_uploads')->insert([
                    'order_id' => $order->id,
                    'file_name' => $filename,
                    'file_type' => $filetype,
                    'uploaded_by' => Auth::user()->id,
                    'created_at' => Carbon::now()
                ]);
            }

            return redirect()->route('order.edit', $order->id)->with('successMsg',
                'Your order has been updated successfully!');
        } elseif (Helper::roleCheck(Auth::user()->id)->role == 'dealer') {
            $order->update([
                'chassis_prefix' => $request->input('chassis_prefix'),
                'chassis' => $request->input('chassis'),
                'vehicle_reg' => $request->input('vehicle_reg'),
                'vehicle_registered_on' => $request->input('vehicle_registered_on'),
                'vehicle_status' => $request->input('vehicle_status'),
            ]);

            if ($request->input('vehicle_status') == 3) {
                $broker = DB::table('order')->select('broker')->where('id', $order->id)->first();
                $users = User::where('company_id', $broker->broker)->get();

                foreach ($users as $user) {
                    $mail_data = [
                        'name' => $user->firstname,
                        'content' => 'Order #' . $order->id . ' has been updated and has now become available for delivery. Please view this order and request a delivery date.',
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

            return redirect()->route('order_bank')->with('successMsg', 'Your order has been updated successfully!');
        }
    }

    public function showReserveOrder(Request $request, Order $order)
    {
        return view('reserve-order', [
            'order_details' => $this->getOrderInfo($order->id),
        ]);
    }

    public function executeReserveOrder(Request $request, Order $order)
    {
        $is_reserved = DB::table('order')
            ->select('reserved_on')
            ->where('id', $order->id)
            ->first();

        is_null($request->input('delivery_date')) ? $delivery_date = null : $delivery_date = Carbon::createFromFormat('d/m/Y',
            $request->input('delivery_date'));

        if (!is_null($is_reserved->reserved_on)) {
            DB::table('order')
                ->where('id', $order->id)
                ->update([
                    'customer_name' => $request->input('customer_name'),
                    'company_name' => $request->input('company_name'),
                    'delivery_date' => $delivery_date,
                    'broker' => Auth::user()->company_id,
                    'vehicle_status' => 2,
                    'broker_accepted' => 1,
                ]);

            $flash_message = 'Your reservation has been updated!';
        } else {
            DB::table('order')
                ->where('id', $order->id)
                ->update([
                    'customer_name' => $request->input('customer_name'),
                    'company_name' => $request->input('company_name'),
                    'delivery_date' => $delivery_date,
                    'reserved_on' => Carbon::now(),
                    'broker' => Auth::user()->company_id,
                    'vehicle_status' => 2,
                    'broker_accepted' => 1,
                ]);

            $flash_message = 'Your have successfully reserved a vehicle!';
        }

        if (!is_null($delivery_date)) {
            // Send email to admin users when broker reserves an order
            $users = User::where('company_id', 1)->get();

            foreach ($users as $user) {
                $mail_data = [
                    'name' => $user->firstname,
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

        return redirect()->route('order_bank')->with('successMsg', $flash_message);
    }

    public function executeDateAccept(Request $request, Order $order)
    {
        if (Helper::roleCheck(Auth::user()->id)->role == 'admin') {
            DB::table('order')
                ->where('id', $order->id)
                ->update([
                    'admin_accepted' => 1,
                ]);
        } elseif (Helper::roleCheck(Auth::user()->id)->role == 'dealer') {
            DB::table('order')
                ->where('id', $order->id)
                ->update([
                    'dealer_accepted' => 1,
                ]);
        } else {
            DB::table('order')
                ->where('id', $order->id)
                ->update([
                    'broker_accepted' => 1,
                ]);
        }

        $accepted = DB::table('order')
            ->select('admin_accepted', 'dealer_accepted', 'broker_accepted', 'dealership', 'broker')
            ->where('id', $order->id)
            ->first();

        if ($accepted->admin_accepted == 1 && $accepted->broker_accepted == 1 && $accepted->dealer_accepted == 0) {
            // email to dealer to accept delivery time
            $users = User::where('company_id', $accepted->dealership)->get();

            foreach ($users as $user) {
                $mail_data = [
                    'name' => $user->firstname,
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
        } elseif ($accepted->admin_accepted == 1 && $accepted->broker_accepted == 0 && $accepted->dealer_accepted == 1) {
            // Email to Broker to accept delivery time
            $users = User::where('company_id', $accepted->broker)->get();

            foreach ($users as $user) {
                $mail_data = [
                    'name' => $user->firstname,
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
        } elseif ($accepted->admin_accepted == 1 && $accepted->broker_accepted == 1 && $accepted->dealer_accepted == 1) {
            // Email to all parties to say delivery date is confirmed
            $users = User::where('company_id', 1)
                ->orWhere('company_id', $accepted->dealership)
                ->orWhere('company_id', $accepted->broker)
                ->get();

            foreach ($users as $user) {
                $mail_data = [
                    'name' => $user->firstname,
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

        return redirect()->route('order.show', $order->id)->with('successMsg',
            'You have accepted the proposed delivery date');
    }

    public function showDateChange(Request $request, Order $order)
    {
        return view('order-date-change', [
            'order_details' => $this->getOrderInfo($order->id),
        ]);
    }

    public function executeDateChange(Request $request, Order $order)
    {
        $old = DB::table('order')
            ->select('admin_accepted', 'dealer_accepted', 'broker_accepted')
            ->where('id', $order->id)
            ->first();

        if (Helper::roleCheck(Auth::user()->id)->role == 'admin') {
            DB::table('order')
                ->where('id', $order->id)
                ->update([
                    'delivery_date' => Carbon::createFromFormat('d/m/Y', $request->input('delivery_date')),
                    'admin_accepted' => 1,
                    'dealer_accepted' => 0,
                    'broker_accepted' => 0,
                ]);
        } elseif (Helper::roleCheck(Auth::user()->id)->role == 'dealer') {
            DB::table('order')
                ->where('id', $order->id)
                ->update([
                    'delivery_date' => Carbon::createFromFormat('d/m/Y', $request->input('delivery_date')),
                    'admin_accepted' => 0,
                    'dealer_accepted' => 1,
                    'broker_accepted' => 0,
                ]);
        } else {
            DB::table('order')
                ->where('id', $order->id)
                ->update([
                    'delivery_date' => Carbon::createFromFormat('d/m/Y', $request->input('delivery_date')),
                    'admin_accepted' => 0,
                    'dealer_accepted' => 0,
                    'broker_accepted' => 1,
                ]);
        }

        $accepted = DB::table('order')
            ->select('admin_accepted', 'dealer_accepted', 'broker_accepted', 'dealership', 'broker')
            ->where('id', $order->id)
            ->first();

        if ($accepted->admin_accepted == 0 && $accepted->broker_accepted == 1 && $accepted->dealer_accepted == 0) {
            // email to admin to accept delivery time
            $users = User::where('company_id', 1)->get();

            foreach ($users as $user) {
                $mail_data = [
                    'name' => $user->firstname,
                    'content' => 'Order #' . $order->id . ' has had the proposed delivery date changed. Please view this order and accept the delivery date.',
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
        } elseif ($accepted->admin_accepted == 1 && $accepted->broker_accepted == 0 && $accepted->dealer_accepted == 0) {
            if ($old->dealer_accepted == 1) {
                // Email Dealer to accept new date from Admin
                $users = User::where('company_id', $accepted->dealership)->get();

                foreach ($users as $user) {
                    $mail_data = [
                        'name' => $user->firstname,
                        'content' => 'Order #' . $order->id . ' has had the proposed delivery date changed. Please view this order and accept the delivery date.',
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
            } elseif ($old->broker_accepted == 1) {
                // Email Broker to accept new date from Admin
                $users = User::where('company_id', $accepted->broker)->get();

                foreach ($users as $user) {
                    $mail_data = [
                        'name' => $user->firstname,
                        'content' => 'Order #' . $order->id . ' has had the proposed delivery date changed. Please view this order and accept the delivery date.',
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
        } elseif ($accepted->admin_accepted == 0 && $accepted->broker_accepted == 0 && $accepted->dealer_accepted == 1) {
            // Email to admin to confirm date change
            $users = User::where('company_id', 1)->get();

            foreach ($users as $user) {
                $mail_data = [
                    'name' => $user->firstname,
                    'content' => 'Order #' . $order->id . ' has had the proposed delivery date changed. Please view this order and accept the delivery date.',
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

        return redirect()->route('order.show', $order->id)->with('successMsg',
            'You have successfully change the propsed delivery date');
    }

    public function executeDeleteOrder(Request $request,$type, Order $order)
    {
        $order->delete();

        if($type == 'ford'){
            $route = 'pipeline.ford';
        }else{
            $route = 'pipeline';
        }

        return redirect()->route($route)->with('successMsg', 'The order has been successfully deleted');
    }


    public function executePDF(Order $order)
    {

        /*dump($order->totalDiscount());
        dump($order->factoryOptionsSubTotal());
        dump($order->factoryOptionsDiscount());
        dump($order->factoryOptionsTotal());
        dump($order->dealerOptionsTotal());

        exit();*/

        // Get Fit options lists
        $factory_fit_options = $order->factoryOptions()->get();
        $dealer_fit_options = $order->dealerOptions()->get();


        // Define variables
        $subtotal = $order->invoiceSubTotal();

        $vat = $order->invoiceVat();
        $total = $order->invoiceValue();

        $deliveryAddress = array_filter([
            $order->customer_name,
            $order->company_name,
            $order->delivery_address_1,
            $order->delivery_address_2,
            $order->delivery_town,
            $order->delivery_city,
            $order->delivery_county,
            $order->delivery_postcode,
        ]);

        $invoiceAddress  = array_filter([
            $order->invoice_company_details->name,
            nl2br($order->invoice_company_details->address)
        ]);

        $registrationAddress  = array_filter([
            $order->registration_company_details->name,
            nl2br($order->registration_company_details->address)
        ]);


        $vehicleDetails = [
            [
                'Vehicle Make' => $order->vehicle_make,
                'Vehicle Model' => $order->vehicle_model,
                'Vehicle Type' => $order->vehicle_type,
            ],
            [
                'Vehicle Derivative' => $order->vehicle_derivative,
                'Vehicle Engine' => $order->vehicle_engine,
                'Vehicle Transmission' => $order->vehicle_trans,
            ],
            [
                'Vehicle Fuel Type' => $order->vehicle_fuel_type,
                'Vehicle Colour' => $order->vehicle_colour,
                'Vehicle Body' => $order->vehicle_body,
            ],
            [

                'Vehicle Trim' => $order->vehicle_trim,
                'Dealership' => Helper::getCompanyName($order->dealership),
                'Broker' => Helper::getCompanyName($order->broker),
            ],
            [

                'Vehicle Chassis Prefix' => $order->chassis_prefix,
                'Vehicle Chassis' => $order->chassis,
                'Vehicle Reg' => $order->vehicle_reg,
            ],
        ];
        $vehicleDetailsHtml = '';

        foreach ($vehicleDetails as $section) {
            $vehicleDetailsHtml .= '<tr>';

            foreach ($section as $name => $item) {
                $vehicleDetailsHtml .= '<td><strong>' . ($name ? $name . ':' : '') . ' </strong> ' . $item . '</td>';
            }

            $vehicleDetailsHtml .= '</tr>';
        }

        $html = '<!DOCTYPE html>
        <html lang="en">

        <head>
            <title>Leden Order - #' . $order->id . '</title>

            <style>
                body {
                    font-family:Arial, Helvetica, sans-serif;
                    color: #858796;
                    font-size: 10px;
                }

                .contents tr th, .details tr th {
                    padding: 20px 20px 20px 20px;
                    background: #1f3458;
                    color: #ffffff;
                }

                .contents tr td, .details tr td {
                    padding: 10px 20px 10px 20px;
                }

                .contents tr td, .details tr td {
                    border-bottom: 1px solid #e3e6f0;
                }

                .contents tr td:first-child, .details tr td {
                    border-left: 1px solid #e3e6f0;
                }

                .contents tr td:last-child {
                    border-right: 1px solid #e3e6f0;
                }

                .contents tr:nth-child(odd), .details tr:nth-child(odd) {
                    background: #f8f9fc;
                }

                .details tr td {
                    border-right: 1px solid #e3e6f0;
                }

            </style>
        </head>

        <table cellspacing="0" cellpadding="0" border="0" width="100%" align="center">
            <tr>
                <td>
                    <img src="https://www.ledenonline.co.uk/images/leden-group-ltd.png" width="350" height="auto" style="display: block;" />
                </td>
            </tr>
            <tr>
                <td style="padding:40px 0px 0px 0px;">
                    <table cellspacing="0" cellpadding="0" border="0" width="100%">
                        <tr>
                            <td valign="top" width="25%">
                                <strong>Delivery Details:</strong><br>
                                ' . implode('<br>', $deliveryAddress) . '
                            </td>
                            <td valign="top" width="25%">
                            <strong>Invoice Details:</strong><br>
                                ' . implode('<br>', $invoiceAddress) . '
                            </td>
                            <td valign="top" width="25%">
                            <strong>Registration Details:</strong><br>
                                ' . implode('<br>', $registrationAddress) . '
                            </td>
                            <td style="text-align: right;" valign="top">
                                <span style="font-size: 28px; font-weight: bold;">Order #' . $order->id . '</span><br>
                                The Leden Group Limited<br> 3 Centrus<br> Mead Lane<br> Hertford<br> Hertfordshire<br> SG13 7GX
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding: 40px 0px 0px 0px;">
                    <table cellspacing="0" cellpadding="0" border="0" width="100%" class="details">
                        <tr>
                            <th colspan="3" style="text-align: left;">
                                Vehicle Details
                            </th>
                        </tr>
                        ' . $vehicleDetailsHtml . '
                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding: 40px 0px 0px 0px;">
                    <table cellspacing="0" cellpadding="0" border="0" width="100%">
                        <tr>
                            <td width="48%" valign="top">
                                <table cellspacing="0" cellpadding="0" border="0" width="100%" class="contents">
                                    <tr>
                                        <th style="text-align: left;">
                                            Factory Option
                                        </th>
                                        <th style="text-align: right;">
                                            Cost (£)
                                        </th>
                                    </tr>';

        foreach ($factory_fit_options as $factory_option) {

            $html .= '<tr>
                            <td>
                                ' . $factory_option->option_name . '
                            </td>
                            <td style="text-align: right;">
                                £' . number_format($factory_option->option_price, '2', '.', ',') . '
                            </td>
                        </tr>';
        }

        $html .= '</table>
                            </td>
                            <td></td>
                            <td width="48%" valign="top">
                                <table cellspacing="0" cellpadding="0" border="0" width="100%" class="contents">
                                    <tr>
                                        <th style="text-align: left;">
                                            Dealer Fit Option
                                        </th>
                                        <th style="text-align: right;">
                                            Cost (£)
                                        </th>
                                    </tr>';

        foreach ($dealer_fit_options as $dealer_option) {

            $html .= '<tr>
                            <td>
                                ' . $dealer_option->option_name . '
                            </td>
                            <td style="text-align: right;">
                                £' . number_format($dealer_option->option_price, '2', '.', ',') . '
                            </td>
                        </tr>';
        }

        $html .= '</table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding: 40px 0px 40px 0px;">
                    <table cellspacing="0" cellpadding="0" border="0" width="100%">
                        <tr>
                            <td width="56%" valign="top">
                                <table cellspacing="0" cellpadding="0" border="0" width="100%" class="contents">
                                    <tr>
                                        <th style="text-align: left;">
                                            Cost Breakdown
                                        </th>
                                        <th style="text-align: right;">
                                            Cost (£)
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>
                                            List Price
                                        </td>
                                        <td style="text-align: right;">
                                            £' . number_format($order->list_price, '2', '.', ',') . '
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Metallic Paint
                                        </td>
                                        <td style="text-align: right;">
                                            £' . number_format($order->metallic_paint, '2', '.', ',') . '
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Dealer Discount
                                        </td>
                                        <td style="text-align: right;">
                                            ' . number_format($order->dealer_discount, '3', '.', ',') . '%
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Manufacturer Discount
                                        </td>
                                        <td style="text-align: right;">
                                            ' . number_format($order->manufacturer_discount, '3', '.', ',') . '%
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Manufacturer Delivery Cost
                                        </td>
                                        <td style="text-align: right;">
                                            £' . number_format($order->manufacturer_delivery_cost, '2', '.', ',') . '
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            1st Reg Fee
                                        </td>
                                        <td style="text-align: right;">
                                            £' . number_format($order->first_reg_fee, '2', '.', ',') . '
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            RFL
                                        </td>
                                        <td style="text-align: right;">
                                            £' . number_format($order->rfl_cost, '2', '.', ',') . '
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Onward Delivery
                                        </td>
                                        <td style="text-align: right;">
                                            £' . number_format($order->onward_delivery, '2', '.', ',') . '
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td></td>
                            <td width="40%" valign="top">
                                <table cellspacing="0" cellpadding="0" border="0" width="100%" class="pricing">
                                    <tr>
                                        <td style="padding: 20px 20px 10px 20px;">
                                            Subtotal
                                        </td>
                                        <td style="text-align: right; padding: 20px 20px 10px 20px;">
                                            £' . number_format($subtotal, '2', '.', ',') . '
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border-bottom: 1px solid #e3e6f0; padding: 10px 20px 20px 20px;">
                                            + VAT
                                        </td>
                                        <td style="border-bottom: 1px solid #e3e6f0; text-align: right; padding: 10px 20px 20px 20px;">
                                            £' . number_format($vat, '2', '.', ',') . '
                                        </td>
                                    </tr>
                                    <tr>
                                        <td  style="border-bottom: 1px solid #e3e6f0; padding: 10px 20px 20px 20px;">
                                            <strong>Total</strong>
                                        </td>
                                        <td  style="border-bottom: 1px solid #e3e6f0; text-align: right; padding: 10px 20px 20px 20px;">
                                            <strong>£' . number_format($total, '2', '.', ',') . '</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 20px 20px 10px 20px;">
                                            Please invoice funder for
                                        </td>
                                        <td style="text-align: right; padding: 20px 20px 10px 20px;">
                                            £' . number_format($order->invoice_funder_for, '2', '.', ',') . '
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="padding: 20px 20px 10px 20px;">
                                            <strong>We will invoice you for the difference</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 20px 20px 10px 20px;">
                                            inc VAT
                                        </td>
                                        <td style="text-align: right; padding: 20px 20px 10px 20px;">
                                            £' . number_format($order->invoiceDifferenceIncVat(), '2', '.', ',') . '
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 20px 20px 10px 20px;">
                                            exc VAT
                                        </td>
                                        <td style="text-align: right; padding: 20px 20px 10px 20px;">
                                            £' . number_format($order->invoiceDifferenceExVat(), '2', '.', ',') . '
                                        </td>
                                    </tr>
                                    <tr>
                                        <td  style="border-top: 1px solid #e3e6f0; padding: 20px 20px 10px 20px;">
                                            Commission to Finance Company
                                        </td>
                                        <td  style="border-top: 1px solid #e3e6f0; text-align: right; padding: 20px 20px 10px 20px;">
                                            £' . number_format($order->invoice_finance, '2', '.', ',') . '</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 20px 20px 10px 20px;">
                                            Invoice to Broker
                                        </td>
                                        <td style="text-align: right; padding: 20px 20px 10px 20px;">
                                            £' . number_format($order->invoice_broker, '2', '.', ',') . '
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 20px 20px 10px 20px;">
                                            Commission to Broker
                                        </td>
                                        <td style="text-align: right; padding: 20px 20px 10px 20px;">
                                            £' . number_format($order->commission_broker, '2', '.', ',') . '
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td bgcolor="#1f3458" style="text-align: center; padding: 20px 20px 20px 20px; font-size: 14px; color: #ffffff;">
                    Copyright &copy; 2020 The Leden Group Limited, All rights reserved.
                </td>
            </tr>
        </table>';

        //return $html;

        return PDF::loadHTML($html)->setPaper('a4',
            'portrait')->setWarnings(false)->download('leden_order_' . $order->id . '.pdf');
    }

    /* Download order PDF */

    public function deleteOrderUpload(OrderUpload $orderUpload)
    {

        if ($orderUpload) {
            try {
                $orderUpload->delete();

            } catch (\Exception $exception) {
                return response()->json(array('msg' => 'Failed to delete'), 400);
            }

            return response()->json(array('msg' => $orderUpload->file_name . ' was deleted'), 200);
        }
    }
}
