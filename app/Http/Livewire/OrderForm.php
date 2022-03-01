<?php

namespace App\Http\Livewire;

use App\Company;
use App\Customer;
use App\FitOption;
use App\Invoice;
use App\Manufacturer;
use App\Notifications\notifications;
use App\Order;
use App\OrderUpload;
use App\User;
use App\Vehicle;
use App\VehicleMeta\Colour;
use App\VehicleMeta\Derivative;
use App\VehicleMeta\Engine;
use App\VehicleMeta\Fuel;
use App\VehicleMeta\Transmission;
use App\VehicleMeta\Trim;
use App\VehicleMeta\Type;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use DateTime;

class OrderForm extends Component
{
    use WithFileUploads;

    public $makeInput = true, $modelInput = true, $derivativeInput = true, $engineInput = true, $transmissionInput = true, $fuelInput = true, $colourInput = true, $trimInput = true;

    public
        $showCustomerInfo = true,
        $showVehicleInfo = false,
        $showFactoryFitOptions = false,
        $showDealerFitOptions = false,
        $showCompanyInfo = false,
        $showCostBreakdown = false,
        $showInvoicingInformation = false,
        $showDeliveryInformation= false,
        $showAdditionalInformation = false;
    public $vehicle;
    public $order;

    public $newCustomer = true;
    public $customer_id;
    public $name;
    public $company;
    public $preferred = "customer";
    public $make;
    public $newmake;
    public $model;
    public $orbit_number;
    public $type;
    public $registration;
    public $derivative;
    public $engine;
    public $transmission;
    public $fuel_type;
    public $colour;
    public $trim;
    public $broker;
    public $broker_ref; // Order
    public $order_ref;
    public $chassis_prefix;
    public $chassis;
    public $status;
    public $due_date; // Order
    public $delivery_date; // Order
    public $model_year;
    public $registered_date;
    public $ford_pipeline = "0";
    public $factory_fit_options; // Vehicle -> JSON of IDs to fit_options
    public $factory_fit_name_manual_add; // View use only
    public $factory_fit_price_manual_add;
    public $dealer_fit_options; // Vehicle
    public $dealer_fit_name_manual_add;
    public $dealer_fit_price_manual_add;
    public $dealership;
    public $registration_company; // Order
    public $invoice_company; // Order
    public $list_price;
    public $metallic_paint;
    public $dealer_discount;
    public $manufacturer_discount;
    public $manufacturer_delivery_cost;
    public $first_reg_fee;
    public $rfl_cost;
    public $onward_delivery;
    public $invoice_funder_for;
    public $show_discount = "0"; // Vehicle
    public $show_offer = "0"; // Vehicle
    public $hide_from_broker = "0";
    public $hide_from_dealer = "0";
    public $invoice_finance;
    public $invoice_finance_number;
    public $finance_commission_paid;
    public $invoice_value_to_broker;
    public $invoice_broker_number;
    public $invoice_broker_paid;
    public $commission_broker;
    public $commission_broker_number;
    public $commission_broker_paid;
    public $customer_phone;
    public $delivery_address_1;
    public $delivery_address_2;
    public $delivery_town;
    public $delivery_city;
    public $delivery_county;
    public $delivery_postcode;
    public $comments;
    public $dealer_invoice_number;
    public $dealer_pay_date;
    public $dealer_invoice_override;
    public $attachments = [];
    public $fields = 1;
    public $successMsg;
    protected $rules = [
        'make' => 'required_without:newmake',
        'model' => 'required',
        'type' => 'required',
        'derivative' => 'required',
        'engine' => 'required',
        'transmission' => 'required',
        'fuel_type' => 'required',
        'colour' => 'required',
        'trim' => 'required',
        'status' => 'required',
        'attachments.*' => 'max:4096',
        'broker' => 'required',
        'dealership' => 'required',
        'order_ref' => 'required',
        'delivery_date' => 'nullable|date',
        'due_date' => 'nullable|date',
        'registered_date' => 'nullable|date',
        'dealer_pay_date' => 'nullable|date',
        'invoice_broker_paid' => 'nullable|date',
        'commission_broker_paid' => 'nullable|date',
        'finance_commission_paid' => 'nullable|date',
    ];

    protected $messages = [
        'make.required_without' => 'No <strong>Make</strong> selected',
        'model.required' => 'No <strong>Model</strong> selected',
        'type.required' => 'No <strong>Vehicle Type</strong> selected',
        'derivative.required' => 'No <strong>Vehicle Derivative</strong> selected',
        'engine.required' => 'No <strong>Engine</strong> selected',
        'transmission.required' => 'No <strong>Transmission</strong> selected',
        'fuel_type.required' => 'No <strong>Fuel Type</strong> selected',
        'colour.required' => 'No <strong>Colour</strong> selected',
        'trim.required' => 'No <strong>Vehicle Trim</strong> selected',
        'status.required' => 'No <strong>Order Status</strong> selected',
        'attachments.*.max' => 'An <strong>Attachment</strong> is too big! (Max 1Mb)',
        'attachments.*.max' => 'An <strong>Attachment</strong> is too big! (Max 1Mb)',
        'dealership.required' => 'No <strong>Dealer</strong> selected',
        'broker.required' => 'No <strong>Broker</strong> selected',
        'order_ref.required' => 'You must supply an <strong>Order Ref.</strong>',
        'factory_fit_name_manual_add.required' => '<strong>Factory Fit Option</strong> requires a <strong>Name</strong> selected',
        'dealer_fit_name_manual_add.required' => '<strong>Dealer Fit Option</strong> requires a <strong>Name</strong> selected',
        'factory_fit_price_manual_add.required' => '<strong>Factory Fit Option</strong> requires a <strong>Price</strong> selected',
        'dealer_fit_price_manual_add.required' => '<strong>Dealer Fit Option</strong> requires a <strong>Price</strong> selected',
    ];

    /**
     * @throws Exception
     */
    public function mount()
    {
        $newCustomer = $this->newCustomer;

        if (isset ($this->vehicle))
        {

            if ( $this->vehicle->vehicle_registered_on) {
                $reg = new DateTime( $this->vehicle->vehicle_registered_on );
                $this->registered_date = $reg->format( 'd/m/Y' );
            }

            $this->make = $this->vehicle->make;
            $this->model = $this->vehicle->model;
            $this->orbit_number = $this->vehicle->orbit_number;
            $this->type = $this->vehicle->type;
            $this->order_ref = $this->vehicle->ford_order_number;
            $this->registration = $this->vehicle->reg;
            $this->derivative = $this->vehicle->derivative;
            $this->engine = $this->vehicle->engine;
            $this->transmission = $this->vehicle->transmission;
            $this->fuel_type = $this->vehicle->fuel_type;
            $this->colour = $this->vehicle->colour;
            $this->trim = $this->vehicle->trim;
            $this->chassis_prefix = $this->vehicle->chassis_prefix;
            $this->chassis = $this->vehicle->chassis;
            $this->status = $this->vehicle->vehicle_status;
            $this->model_year = $this->vehicle->model_year;
            $this->ford_pipeline = $this->vehicle->show_in_ford_pipeline;
            $this->factory_fit_options = $this->vehicle->factory_fit_options;
            $this->dealer_fit_options = $this->vehicle->dealer_fit_options;
            $this->list_price = $this->vehicle->list_price;
            $this->metallic_paint = $this->vehicle->metallic_paint;
            $this->first_reg_fee = $this->vehicle->first_reg_fee;
            $this->rfl_cost = $this->vehicle->rfl_cost;
            $this->onward_delivery = $this->vehicle->onward_delivery;
            $this->show_discount = $this->vehicle->show_discount;
            $this->show_offer = $this->vehicle->show_offer;
            $this->hide_from_broker = $this->vehicle->hide_from_broker;
            $this->hide_from_dealer = $this->vehicle->hide_from_dealer;
        }

        if (isset($this->order)) {

            // Handle Dates coming in so JS can play nice with them.

            if ( $this->order->due_date ) {

                $del = new DateTime( $this->order->due_date);
                $this->due_date = $del->format( 'd/m/Y');

            }

            if ( $this->order->delivery_date ) {

                $del = new DateTime( $this->order->delivery_date );
                $this->delivery_date = $del->format( 'd/m/Y');

            }

            if ( $this->order->vehicle->vehicle_registered_on) {
                $reg = new DateTime( $this->order->vehicle->vehicle_registered_on );
                $this->registered_date = $reg->format( 'd/m/Y' );
            }

            if ($this->order->invoice->dealer_pay_date) {
                $dpd = new DateTime ($this->order->invoice->dealer_pay_date);
                $this->dealer_pay_date = $dpd->format('d/m/Y');
            }

            if ($this->order->invoice->broker_pay_date) {
                $dpd = new DateTime ($this->order->invoice->broker_pay_date);
                $this->invoice_broker_paid = $dpd->format('d/m/Y');
            }

            if ($this->order->invoice->broker_commission_pay_date) {
                $dpd = new DateTime ($this->order->invoice->broker_commission_pay_date);
                $this->commission_broker_paid = $dpd->format('d/m/Y');
            }

            if ($this->order->invoice->finance_commission_pay_date) {
                $dpd = new DateTime ($this->order->invoice->finance_commission_pay_date);
                $this->finance_commission_paid = $dpd->format('d/m/Y');
            }

            $this->name = $this->order->customer->customer_name;
            $this->company = $this->order->customer->company_name;
            $this->preferred = $this->order->customer->preferred_name;
            $this->customer_phone = $this->order->customer->phone_number;
            $this->delivery_address_1 = $this->order->customer->address_1;
            $this->delivery_address_2 = $this->order->customer->address_2;
            $this->delivery_town = $this->order->customer->town;
            $this->delivery_city = $this->order->customer->city;
            $this->delivery_county = $this->order->customer->county;
            $this->delivery_postcode = $this->order->customer->postcode;

            $this->invoice_finance = $this->order->invoice->commission_to_finance_company;
            $this->invoice_finance_number = $this->order->invoice->finance_commission_invoice_number;
            $this->invoice_value_to_broker = $this->order->invoice->invoice_value_to_broker;
            $this->invoice_broker_number = $this->order->invoice->broker_invoice_number;
            $this->commission_broker = $this->order->invoice->commission_to_broker;
            $this->commission_broker_number = $this->order->invoice->broker_commission_invoice_number;
            $this->dealer_discount = $this->order->invoice->dealer_discount;
            $this->manufacturer_discount = $this->order->invoice->manufacturer_discount;
            $this->invoice_funder_for = $this->order->invoice->invoice_funder_for;
            $this->manufacturer_delivery_cost = $this->order->invoice->manufacturer_delivery_cost;

            $this->order_ref = $this->order->vehicle->ford_order_number;
            $this->broker_ref = $this->order->broker_ref;
            $this->comments = $this->order->comments;
            $this->registration_company = $this->order->registration_company_id;
            $this->invoice_company = $this->order->invoice_company_id;
            $this->dealership = $this->order->dealer_id;
            $this->broker = $this->order->broker_id;
            $this->make = $this->order->vehicle->make;
            $this->model = $this->order->vehicle->model;
            $this->orbit_number = $this->order->vehicle->orbit_number;
            $this->type = $this->order->vehicle->type;
            $this->registration = $this->order->vehicle->reg;
            $this->derivative = $this->order->vehicle->derivative;
            $this->engine = $this->order->vehicle->engine;
            $this->transmission = $this->order->vehicle->transmission;
            $this->fuel_type = $this->order->vehicle->fuel_type;
            $this->colour = $this->order->vehicle->colour;
            $this->trim = $this->order->vehicle->trim;
            $this->chassis_prefix = $this->order->vehicle->chassis_prefix;
            $this->chassis = $this->order->vehicle->chassis;
            $this->status = $this->order->vehicle->vehicle_status;
            $this->model_year = $this->order->vehicle->model_year;
            $this->ford_pipeline = $this->order->vehicle->show_in_ford_pipeline;
            $this->factory_fit_options = $this->order->vehicle->factory_fit_options;
            $this->dealer_fit_options = $this->order->vehicle->dealer_fit_options;
            $this->list_price = $this->order->vehicle->list_price;
            $this->metallic_paint = $this->order->vehicle->metallic_paint;
            $this->first_reg_fee = $this->order->vehicle->first_reg_fee;
            $this->rfl_cost = $this->order->vehicle->rfl_cost;
            $this->onward_delivery = $this->order->vehicle->onward_delivery;
            $this->show_discount = $this->order->vehicle->show_discount;
            $this->show_offer = $this->order->vehicle->show_offer;
            $this->hide_from_broker = $this->order->vehicle->hide_from_broker;
            $this->hide_from_dealer = $this->order->vehicle->hide_from_dealer;
        }

    }


    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function updatednewMake($value)
    {
        if ($value != '') {
            $this->make = null;
            $this->modelInput = false;
        }
    }


    public function newFactoryFit() {

        $this->validate([
            'factory_fit_name_manual_add' => 'required',
            'factory_fit_price_manual_add' => 'required'
        ]);

        $factory_fit_option = new FitOption();
        $factory_fit_option->option_name = $this->factory_fit_name_manual_add;
        $factory_fit_option->option_price = $this->factory_fit_price_manual_add;
        $factory_fit_option->option_type = 'factory';
        $factory_fit_option->save();

        $this->factory_fit_options[] = strval( $factory_fit_option->id );

    }

    public function newDealerFit() {
        $this->validate([
            'dealer_fit_name_manual_add' => 'required',
            'dealer_fit_price_manual_add' => 'required'
        ]);
        $dealer_fit_option = new FitOption();
        $dealer_fit_option->option_name = $this->dealer_fit_name_manual_add;
        $dealer_fit_option->option_price = $this->dealer_fit_price_manual_add;
        $dealer_fit_option->option_type = 'dealer';
        $dealer_fit_option->save();

        $this->dealer_fit_options[] = strval( $dealer_fit_option->id );
    }

    public function handleAddField()
    {
        $this->fields++;
    }

    public function removeAttachment($key)
    {
        unset( $this->attachments[$key] );
    }

    public function orderFormSubmit()
    {


        if ($this->orbit_number === '') {
            $this->orbit_number = null;
        }

        if ($this->delivery_date) {
            $this->delivery_date = DateTime::createFromFormat('d/m/Y', $this->delivery_date );
        }
        if ($this->due_date) {
            $this->due_date = DateTime::createFromFormat('d/m/Y', $this->due_date );
        }
        if ($this->registered_date) {
            $this->registered_date = DateTime::createFromFormat('d/m/Y', $this->registered_date);
        }
        if ($this->dealer_pay_date) {
            $this->dealer_pay_date = DateTime::createFromFormat('d/m/Y', $this->dealer_pay_date);
        }
        if ($this->invoice_broker_paid) {
            $this->invoice_broker_paid = DateTime::createFromFormat('d/m/Y', $this->invoice_broker_paid);
        }
        if ($this->commission_broker_paid) {
            $this->commission_broker_paid = DateTime::createFromFormat('d/m/Y', $this->commission_broker_paid);
        }
        if ($this->finance_commission_paid) {
            $this->finance_commission_paid = DateTime::createFromFormat('d/m/Y', $this->finance_commission_paid);
        }

        $this->validate();

        if ( !isset( $this->order )) {

            if (isset($this->newmake)) {

                $slug = Str::slug($this->newmake);

                $manufacturer = Manufacturer::firstOrCreate(
                    ['slug' => $slug],
                    [
                        'name' => ucwords($this->newmake),
                        'models' => json_encode($this->model)
                    ]
                );

                $this->make = $manufacturer->id;

            }

            if ( isset($this->vehicle) ) {
                $vehicle = $this->vehicle;
                if (isset($this->orbit_number )) {
                    $vehicle->orbit_number = $this->orbit_number;
                } elseif ($this->orbit_number === null) {
                    $vehicle->orbit_number = null;
                }
            } elseif (!isset ($this->orbit_number) || $this->orbit_number === '') {
                $vehicle = new Vehicle();
            } else {
                $vehicle = Vehicle::firstOrNew(array(
                    'orbit_number' => $this->orbit_number,
                ));
            }

            $vehicle->vehicle_status = $this->status;
            $vehicle->reg = $this->registration;
            $vehicle->vehicle_registered_on = $this->registered_date;
            $vehicle->model_year = $this->model_year;
            $vehicle->ford_order_number = $this->order_ref;
            $vehicle->make = $this->make;
            $vehicle->model = $this->model;
            $vehicle->chassis = $this->chassis;
            $vehicle->derivative = $this->derivative;
            $vehicle->engine = $this->engine;
            $vehicle->transmission = $this->transmission;
            $vehicle->fuel_type = $this->fuel_type;
            $vehicle->colour = $this->colour;
            $vehicle->trim = $this->trim;
            $vehicle->dealer_fit_options = isset($this->dealer_fit_options) ? json_encode($this->dealer_fit_options) : null;
            $vehicle->factory_fit_options = isset($this->factory_fit_options) ? json_encode($this->factory_fit_options) : null;
            $vehicle->chassis_prefix = $this->chassis_prefix;
            $vehicle->type = $this->type;
            $vehicle->metallic_paint = $this->metallic_paint;
            $vehicle->list_price = $this->list_price;
            $vehicle->first_reg_fee = $this->first_reg_fee;
            $vehicle->rfl_cost = $this->rfl_cost;
            $vehicle->broker_id = $this->broker;
            $vehicle->dealer_id = $this->dealership;
            $vehicle->onward_delivery = $this->onward_delivery;
            $vehicle->hide_from_broker = $this->hide_from_broker;
            $vehicle->hide_from_dealer = $this->hide_from_dealer;
            $vehicle->show_in_ford_pipeline = $this->ford_pipeline;

            $vehicle->save();

            if (!isset($this->customer_id) || $this->customer_id === '') {
                $customer = new Customer();
                $customer->customer_name = $this->name;
                $customer->company_name = $this->company;
                $customer->preferred_name = $this->preferred;
                $customer->address_1 = $this->delivery_address_1;
                $customer->address_2 = $this->delivery_address_2;
                $customer->town = $this->delivery_town;
                $customer->city = $this->delivery_city;
                $customer->county = $this->delivery_county;
                $customer->postcode = $this->delivery_postcode;
                $customer->phone_number = $this->customer_phone;

                $customer->save();

                $customer = $customer->id;
            } else {
                $customer = $this->customer_id;
            }

            $invoice = new Invoice();
            $invoice->finance_commission_invoice_number = $this->invoice_finance_number;
            $invoice->broker_invoice_number = $this->invoice_broker_number;
            $invoice->broker_commission_invoice_number = $this->commission_broker_number;
            $invoice->dealer_discount = $this->dealer_discount;
            $invoice->manufacturer_discount = $this->manufacturer_discount;
            $invoice->manufacturer_delivery_cost = $this->manufacturer_delivery_cost;
            $invoice->onward_delivery = $this->onward_delivery;
            $invoice->invoice_funder_for = $this->invoice_funder_for;
            $invoice->invoice_value = $this->invoice_finance;
            $invoice->invoice_value_to_broker = $this->invoice_value_to_broker;
            $invoice->commission_to_broker = $this->commission_broker;
            $invoice->commission_to_finance_company = $this->invoice_finance;
            $invoice->finance_commission_pay_date = $this->finance_commission_paid;
            $invoice->broker_commission_pay_date = $this->commission_broker_paid;
            $invoice->broker_pay_date = $this->invoice_broker_paid;
            $invoice->dealer_pay_date = $this->dealer_pay_date;
            $invoice->dealer_invoice_number = $this->dealer_invoice_number;
            $invoice->save();


            $order = new Order();
            $order->vehicle_id = $vehicle->id;
            $order->customer_id = $customer;
            $order->broker_id = $this->broker;
            $order->dealer_id = $this->dealership;
            $order->comments = $this->comments;
            $order->broker_ref = $this->broker_ref;
            $order->due_date = $this->due_date;
            $order->delivery_date = $this->delivery_date;
            $order->registration_company_id = $this->registration_company;
            $order->invoice_company_id = $this->invoice_company;
            $order->invoice_id = $invoice->id;
            $order->save();

            $this->markOrderComplete($vehicle, $order);

            foreach ($this->attachments as $attachment) {
                $file = new OrderUpload();
                $file->file_name = $attachment->store('attachments');
                $file->uploaded_by = auth()->id();
                $file->order_id = $order->id;
                $file->file_type = $attachment->getClientOriginalExtension();
                $file->save();
            }

            $this->successMsg = "Order Created";
        } else {



            //Update Vehicle
            $vehicle = $this->order->vehicle;

            $vehicle->vehicle_status = $this->status;
            $vehicle->reg = $this->registration;
            $vehicle->orbit_number = $this->orbit_number;
            $vehicle->ford_order_number = $this->order_ref;
            $vehicle->broker_id = $this->broker;
            $vehicle->dealer_id = $this->dealership;
            $vehicle->vehicle_registered_on = $this->registered_date;
            $vehicle->model_year = $this->model_year;
            $vehicle->make = $this->make;
            $vehicle->model = $this->model;
            $vehicle->derivative = $this->derivative;
            $vehicle->engine = $this->engine;
            $vehicle->transmission = $this->transmission;
            $vehicle->fuel_type = $this->fuel_type;
            $vehicle->colour = $this->colour;
            $vehicle->trim = $this->trim;
            $vehicle->dealer_fit_options = $this->dealer_fit_options;
            $vehicle->factory_fit_options = $this->factory_fit_options;
            $vehicle->chassis = $this->chassis;
            $vehicle->chassis_prefix = $this->chassis_prefix;
            $vehicle->type = $this->type;
            $vehicle->metallic_paint = $this->metallic_paint;
            $vehicle->list_price = $this->list_price;
            $vehicle->first_reg_fee = $this->first_reg_fee;
            $vehicle->rfl_cost = $this->rfl_cost;
            $vehicle->onward_delivery = $this->onward_delivery;
            $vehicle->hide_from_broker = $this->hide_from_broker;
            $vehicle->hide_from_dealer = $this->hide_from_dealer;
            $vehicle->show_in_ford_pipeline = $this->ford_pipeline;
            $vehicle->save();

            //Update Invoice
            $invoice = $this->order->invoice;
            $invoice->finance_commission_invoice_number = $this->invoice_finance_number;
            $invoice->broker_invoice_number = $this->invoice_broker_number;
            $invoice->broker_commission_invoice_number = $this->commission_broker_number;
            $invoice->dealer_discount = $this->dealer_discount;
            $invoice->manufacturer_discount = $this->manufacturer_discount;
            $invoice->manufacturer_delivery_cost = $this->manufacturer_delivery_cost;
            $invoice->onward_delivery = $this->onward_delivery;
            $invoice->invoice_funder_for = $this->invoice_funder_for;
            $invoice->invoice_value = $this->invoice_finance;
            $invoice->invoice_value_to_broker = $this->invoice_value_to_broker;
            $invoice->commission_to_broker = $this->commission_broker;
            $invoice->commission_to_finance_company = $this->invoice_finance;
            $invoice->finance_commission_pay_date = $this->finance_commission_paid;
            $invoice->broker_commission_pay_date = $this->commission_broker_paid;
            $invoice->broker_pay_date = $this->invoice_broker_paid;
            $invoice->dealer_pay_date = $this->dealer_pay_date;
            $invoice->dealer_invoice_number = $this->dealer_invoice_number;
            $invoice->save();

            //Update Customer
            $customer = $this->order->customer;
            $customer->customer_name = $this->name;
            $customer->company_name = $this->company;
            $customer->preferred_name = $this->preferred;
            $customer->address_1 = $this->delivery_address_1;
            $customer->address_2 = $this->delivery_address_2;
            $customer->town = $this->delivery_town;
            $customer->city = $this->delivery_city;
            $customer->county = $this->delivery_county;
            $customer->postcode = $this->delivery_postcode;
            $customer->phone_number = $this->customer_phone;

            $customer->save();

            //Update Order
            $order = $this->order;
            $order->vehicle_id = $vehicle->id;
            $order->customer_id = $customer->id;
            $order->broker_id = $this->broker;
            $order->dealer_id = $this->dealership;
            $order->comments = $this->comments;
            $order->broker_ref = $this->broker_ref;
            $order->due_date = $this->due_date;
            $order->delivery_date = $this->delivery_date;
            $order->registration_company_id = $this->registration_company;
            $order->invoice_company_id = $this->invoice_company;
            $order->invoice_id = $invoice->id;
            $order->save();

            $this->markOrderComplete($vehicle, $order);

            $invoice_value = $order->invoiceDifferenceExVat();

            if($invoice_value < 0) {
                if($this->dealer_invoice_override) {
                    $invoice->invoice_value_from_dealer = $this->dealer_invoice_override;
                } else {
                    $invoice->invoice_value_from_dealer = $invoice_value * -1;
                }
            } else {
                $invoice->invoice_value_from_dealer = null;
            }
            $invoice->save();

            foreach ($this->attachments as $attachment) {
                $file = new OrderUpload();
                $file->file_name = $attachment->store('attachments');
                $file->uploaded_by = auth()->id();
                $file->order_id = $order->id;
                $file->file_type = $attachment->getClientOriginalExtension();
                $file->save();
            }


            $this->delivery_date = ( $this->delivery_date ? $this->delivery_date->format( 'd/m/Y') : null );
            $this->due_date = ( $this->due_date ? $this->due_date->format( 'd/m/Y') : null );
            $this->registered_date = ( $this->registered_date ? $this->registered_date->format( 'd/m/Y') : null );
            $this->dealer_pay_date = ($this->dealer_pay_date ? $this->dealer_pay_date->format('d/m/Y') : null);
            $this->invoice_broker_paid = ($this->invoice_broker_paid ? $this->invoice_broker_paid->format('d/m/Y') : null);
            $this->commission_broker_paid = ($this->commission_broker_paid ? $this->commission_broker_paid->format('d/m/Y') : null);
            $this->finance_commission_paid = ($this->finance_commission_paid ? $this->finance_commission_paid->format('d/m/Y') : null);

            $this->successMsg = "Order Updated";
        }
    }

    public function render()
    {
        $companies = Company::latest()->get();
        $fitoptions = FitOption::latest()->get();

        $options = [
            'customers'         => Customer::all(),
            'manufacturers'     => Manufacturer::all()->keyBy('id'),
            'types'             => Type::all(),
            'derivatives'       => Derivative::all(),
            'engines'           => Engine::all(),
            'transmissions'     => Transmission::all(),
            'fuel_types'        => Fuel::all(),
            'colours'           => Colour::all(),
            'trims'             => Trim::all(),

            'brokers'                => $companies->where('company_type', 'broker'),
            'dealers'                => $companies->where('company_type', 'dealer'),
            'registration_companies' => $companies->where('company_type', 'registration'),
            'invoice_companies'      => $companies->where('company_type', 'invoice'),

            'factory_options'   => $fitoptions->where('option_type', 'factory'),
            'dealer_options'    => $fitoptions->where('option_type', 'dealer')
        ];
        return view('livewire.order-form', $options );
    }

    private function markOrderComplete($vehicle, $order)
    {
        if ($vehicle->vehicle_status === '7') {
            $order->update(['completed_date' => now()]);
        }
    }
}
