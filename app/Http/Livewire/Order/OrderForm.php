<?php

namespace App\Http\Livewire\Order;

use App\Http\Controllers\OrderController;
use App\Models\Company;
use App\Models\Customer;
use App\Models\DealerDiscount;
use App\Models\Finance\FinanceType;
use App\Models\Finance\InitialPayment;
use App\Models\Finance\Maintenance;
use App\Models\Finance\Mileage;
use App\Models\Finance\Term;
use App\Models\FitOption;
use App\Models\Invoice;
use App\Models\Manufacturer;
use App\Models\Order;
use App\Models\OrderUpload;
use App\Models\Permission;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleMeta;
use App\Models\VehicleModel;
use App\Notifications\DeliveryDateSetNotification;
use App\Notifications\RegistrationNumberAddedEmailNotification;
use App\Notifications\RegistrationNumberAddedNotification;
use App\Notifications\VehicleInStockEmailNotification;
use App\Notifications\VehicleInStockNotification;
use Carbon\Carbon;
use ErrorException;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;

class OrderForm extends Component
{
    use WithFileUploads;

    public bool $showAdditionalInformation = false;
    public bool $showDeliveryInformation = false;
    public bool $showInvoicingInformation = false;
    public bool $showCostBreakdown = false;
    public bool $showCompanyInfo = false;
    public bool $showDealerFitOptions = false;
    public bool $showFactoryFitOptions = false;
    public bool $showVehicleInfo = true;
    public bool $showCustomerInfo = true;
    public $vehicle;
    public $order;
    public $newCustomer = false;
    public $customer_id;
    public $customer_name;
    public $make;
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
    public $build_date; //Vehicle
    public $due_date; // Vehicle
    public $delivery_date; // Order
    public $model_year;
    public $registered_date;
    public $ford_pipeline = '0';
    public $factoryFitOptions = []; // Vehicle -> JSON of IDs to fit_options
    public $dealerFitOptions = []; // Vehicle
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
    public $show_discount = '0'; // Vehicle
    public $show_offer = '0'; // Vehicle
    public $hide_from_broker = '0';
    public $hide_from_dealer = '0';
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
    public $dealer_invoice_override_allowed = false;
    public $dealer_discount_override;
    public $fleet_procure_invoice;
    public $fleet_invoice_number;
    public $fleet_procure_paid;
    public $finance_bonus_invoice;
    public $finance_company_bonus_invoice_number;
    public $finance_company_bonus_pay_date;
    public $fin_number = '65634';
    public $deal_number = '90191';
    public $ford_bonus_invoice;
    public $ford_bonus_pay_date;
    public $factoryFitSearch;
    public $factoryFitOptionsArray = [];
    public $dealerFitOptionsArray = [];
    public $dealerFitSearch;
    public $attachments = [];
    public $fields = 1;
    public $successMsg;
    public $financeType;
    public $financeTypeOptions;
    public $maintenance;
    public $maintenanceOptions;
    public $term;
    public $termOptions;
    public $initialPayments;
    public $initialPaymentsOptions;
    public $terminalPause;
    public $mileage;
    public $mileageOptions;
    public $rental_value;
    public $maintenance_rental_value;
    public $renewal_date;
    public $now;
    protected $rules = [
        'make' => 'required_without:newmake',
        'customer_name' => 'required_without:customer_id',
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
        'build_date' => 'nullable|date',
        'due_date' => 'nullable|date',
        'registered_date' => 'nullable|date',
        'dealer_pay_date' => 'nullable|date',
        'invoice_broker_paid' => 'nullable|date',
        'commission_broker_paid' => 'nullable|date',
        'finance_commission_paid' => 'nullable|date',
    ];
    protected $messages = [
        'make.required_without' => 'No <strong>Make</strong> selected',
        'customer_name.required_without' =>
            'No <strong>Customer Name</strong> selected',
        'model.required' => 'No <strong>Model</strong> selected',
        'type.required' => 'No <strong>Vehicle Type</strong> selected',
        'derivative.required' =>
            'No <strong>Vehicle Derivative</strong> selected',
        'engine.required' => 'No <strong>Engine</strong> selected',
        'transmission.required' => 'No <strong>Transmission</strong> selected',
        'fuel_type.required' => 'No <strong>Fuel Type</strong> selected',
        'colour.required' => 'No <strong>Colour</strong> selected',
        'trim.required' => 'No <strong>Vehicle Trim</strong> selected',
        'status.required' => 'No <strong>Order Status</strong> selected',
        'attachments.*.max' =>
            'An <strong>Attachment</strong> is too big! (Max 1Mb)',
        'dealership.required' => 'No <strong>Dealer</strong> selected',
        'broker.required' => 'No <strong>Broker</strong> selected',
        'order_ref.required' =>
            'You must supply an <strong>Order Ref.</strong>',
    ];

    /**
     * Set up the basic information for the order form, and set the information from the Vehicle or Order if they are being given to the Order Form
     * @throws Exception
     */
    public function mount()
    {
        $this->now = date('Y-m-d');

        $this->financeTypeOptions = FinanceType::all();
        $this->maintenanceOptions = Maintenance::all();
        $this->termOptions = Term::all();
        $this->initialPaymentsOptions = InitialPayment::all();
        $this->mileageOptions = Mileage::all();

        if (isset($this->vehicle)) {
            $this->registered_date = $this->vehicle->vehicle_registered_on;
            $this->due_date = $this->vehicle->due_date;
            $this->build_date = $this->vehicle->build_date;

            $this->make = $this->vehicle->make;
            $this->model = VehicleModel::where(
                'name',
                $this->vehicle->model,
            )->first()?->id;
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
            $this->list_price = $this->vehicle->list_price;
            $this->metallic_paint = $this->vehicle->metallic_paint;
            $this->first_reg_fee = $this->vehicle->first_reg_fee;
            $this->rfl_cost = $this->vehicle->rfl_cost;
            $this->onward_delivery = $this->vehicle->onward_delivery;
            $this->show_discount = $this->vehicle->show_discount;
            $this->show_offer = $this->vehicle->show_offer;
            $this->hide_from_broker = $this->vehicle->hide_from_broker;
            $this->hide_from_dealer = $this->vehicle->hide_from_dealer;
            $this->broker = $this->vehicle->broker_id;
            $this->dealership = $this->vehicle->dealer_id;
            $this->factoryFitOptions = $this->vehicle
                ->factoryFitOptions()
                ->pluck('id')
                ->toArray();
            $this->dealerFitOptions = $this->vehicle
                ->dealerFitOptions()
                ->pluck('id')
                ->toArray();
            $this->factoryFitOptionsArray = $this->vehicle->factoryFitOptions();
            $this->dealerFitOptionsArray = $this->vehicle->dealerFitOptions();
            $this->getDealerDiscount();
        }
        if (isset($this->order)) {
            $this->dealer_pay_date = $this->order->invoice->dealer_pay_date;
            $this->invoice_broker_paid = $this->order->invoice->broker_pay_date;
            $this->commission_broker_paid =
                $this->order->invoice->broker_commission_pay_date;
            $this->finance_commission_paid =
                $this->order->invoice->finance_commission_pay_date;
            $this->fleet_procure_paid =
                $this->order->invoice->fleet_procure_pay_date;
            $this->finance_company_bonus_pay_date =
                $this->order->invoice->finance_company_bonus_pay_date;
            $this->ford_bonus_pay_date =
                $this->order->invoice->ford_bonus_pay_date;

            $this->dealer_discount_override = true;
            $this->customer_id = $this->order->customer->id;
            $this->customer_name = $this->order->customer->customer_name;
            $this->customer_phone = $this->order->customer->phone_number;
            $this->delivery_address_1 = $this->order->customer->address_1;
            $this->delivery_address_2 = $this->order->customer->address_2;
            $this->delivery_town = $this->order->customer->town;
            $this->delivery_city = $this->order->customer->city;
            $this->delivery_county = $this->order->customer->county;
            $this->delivery_postcode = $this->order->customer->postcode;

            $this->invoice_finance =
                $this->order->invoice->commission_to_finance_company;
            $this->invoice_finance_number =
                $this->order->invoice->finance_commission_invoice_number;
            $this->invoice_value_to_broker =
                $this->order->invoice->invoice_value_to_broker;
            $this->invoice_broker_number =
                $this->order->invoice->broker_invoice_number;
            $this->commission_broker =
                $this->order->invoice->commission_to_broker;
            $this->commission_broker_number =
                $this->order->invoice->broker_commission_invoice_number;
            $this->dealer_discount = $this->order->invoice->dealer_discount;
            $this->manufacturer_discount =
                $this->order->invoice->manufacturer_discount;
            $this->invoice_funder_for =
                $this->order->invoice->invoice_funder_for;
            $this->manufacturer_delivery_cost =
                $this->order->invoice->manufacturer_delivery_cost;
            $this->fleet_procure_invoice =
                $this->order->invoice->fleet_procure_invoice;
            $this->fleet_invoice_number =
                $this->order->invoice->fleet_procure_invoice_number;
            $this->finance_bonus_invoice =
                $this->order->invoice->finance_company_bonus_invoice;
            $this->finance_company_bonus_invoice_number =
                $this->order->invoice->finance_company_bonus_invoice_number;
            $this->ford_bonus_invoice = $this->order->invoice->ford_bonus;
            $this->dealer_invoice_number =
                $this->order->invoice->dealer_invoice_number;
            if ($this->order->invoice->invoice_value_to_dealer) {
                $this->dealer_invoice_override =
                    $this->order->invoice->invoice_value_to_dealer;
            } elseif ($this->order->invoice->invoice_value_from_dealer) {
                $this->dealer_invoice_override =
                    0 - $this->order->invoice->invoice_value_from_dealer;
            }
            $this->dealer_invoice_override_allowed =
                $this->order->invoice->dealer_value_overruled;
            $this->order_ref = $this->order->vehicle->ford_order_number;
            $this->broker_ref = $this->order->broker_ref;
            $this->comments = $this->order->comments;
            $this->registration_company = $this->order->registration_company_id;
            $this->invoice_company = $this->order->invoice_company_id;
            $this->dealership = $this->order->dealer_id;
            $this->broker = $this->order->broker_id;
            $this->delivery_date = $this->order->delivery_date;
            $this->registered_date =
                $this->order->vehicle->vehicle_registered_on;
            $this->build_date = $this->order->vehicle->build_date;
            $this->due_date = $this->order->vehicle->due_date;
            $this->make = $this->order->vehicle->make;
            $this->model = VehicleModel::where(
                'name',
                $this->order->vehicle->model,
            )->first()->id;
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
            $this->fin_number = $this->order->fin_number;
            $this->deal_number = $this->order->deal_number;
            $this->factoryFitOptions = $this->order->vehicle
                ->factoryFitOptions()
                ->pluck('id')
                ->toArray();
            $this->dealerFitOptions = $this->order->vehicle
                ->dealerFitOptions()
                ->pluck('id')
                ->toArray();
            $this->factoryFitOptionsArray = $this->order->vehicle->factoryFitOptions();
            $this->dealerFitOptionsArray = $this->order->vehicle->dealerFitOptions();
            $this->list_price = $this->order->vehicle->list_price;
            $this->metallic_paint = $this->order->vehicle->metallic_paint;
            $this->first_reg_fee = $this->order->vehicle->first_reg_fee;
            $this->rfl_cost = $this->order->vehicle->rfl_cost;
            $this->onward_delivery = $this->order->vehicle->onward_delivery;
            $this->show_discount = $this->order->vehicle->show_discount;
            $this->show_offer = $this->order->vehicle->show_offer;
            $this->hide_from_broker = $this->order->vehicle->hide_from_broker;
            $this->hide_from_dealer = $this->order->vehicle->hide_from_dealer;
            $this->financeType = $this->order->finance_type;
            $this->maintenance = $this->order->maintenance;
            $this->term = $this->order->term;
            $this->initialPayments = $this->order->initial_payment;
            if ($this->order->terminal_pause) {
                $this->terminalPause = 'yes';
            } else {
                $this->terminalPause = 'no';
            }
            $this->mileage = $this->order->mileage;
            $this->rental_value = $this->order->rental;
            $this->maintenance_rental_value = $this->order->maintenance_rental;
            $this->renewal_date = $this->order->renewal_date;
        }
    }

    /**
     * When the Make is updated, clear the vehicle model.
     * @return void
     */
    public function updatedMake(): void
    {
        $this->model = null;
    }

    /**
     * When the Vehicle Model is updated, clear old information and check the dealer discount on offer/
     * @return void
     */
    public function updatedModel(): void
    {
        $this->type = null;
        $this->derivative = null;
        $this->engine = null;
        $this->transmission = null;
        $this->fuel_type = null;
        $this->colour = null;
        $this->trim = null;
        $this->getDealerDiscount();
    }

    /**
     * When the Dealership is updated, check for the discount they offer
     * @return void
     */
    public function updatedDealership(): void
    {
        $this->getDealerDiscount();
    }

    /**
     * Updates the factory fit options array when changes are made in the form
     * @return void
     */
    public function updatedFactoryFitOptions(): void
    {
        $this->factoryFitOptionsArray = FitOption::find(
            $this->factoryFitOptions,
        );
    }

    /**
     * Updates the dealer fit options array when changes are made in the form
     * @return void
     */
    public function updatedDealerFitOptions(): void
    {
        $this->dealerFitOptionsArray = FitOption::find($this->dealerFitOptions);
    }

    /**
     * Validate each item as it is updated
     * @param $propertyName
     * @return void
     */
    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName);
    }

    /**
     * Remove selected attachments
     * @param $key
     * @return void
     */
    public function removeAttachment($key): void
    {
        unset($this->attachments[$key]);
    }

    /**
     * Save the order and all the details to the various models
     * @throws ErrorException
     */
    public function orderFormSubmit()
    {
        if ($this->orbit_number === '') {
            $this->orbit_number = null;
        }
        if ($this->terminalPause === 'yes') {
            $this->terminalPause = true;
        } else {
            $this->terminalPause = false;
        }
        $this->validate();
        if (!isset($this->order)) {
            if (isset($this->vehicle)) {
                $vehicle = $this->vehicle;
                if (isset($this->orbit_number)) {
                    $vehicle->orbit_number = $this->orbit_number;
                } elseif ($this->orbit_number === null) {
                    $vehicle->orbit_number = null;
                }
            } elseif (
                !isset($this->orbit_number) ||
                $this->orbit_number === ''
            ) {
                $vehicle = Vehicle::create();
            } else {
                $vehicle = Vehicle::firstOrNew([
                    'orbit_number' => $this->orbit_number,
                ]);
                if ($vehicle->order) {
                    throw new ErrorException('Vehicle already ordered');
                }
            }
            $this->saveVehicleDetails($vehicle);
            if (!isset($this->customer_id) || $this->customer_id === '') {
                $customer = Customer::create();
                $this->saveCustomerDetails($customer);
                $customer = $customer->id;
            } else {
                $customer = $this->customer_id;
            }
            $invoice = Invoice::create();
            $this->saveInvoice($invoice);
            $order = Order::create();
            $order->vehicle_id = $vehicle->id;
            $order->customer_id = $customer;
            $order->broker_id = $this->broker;
            $order->dealer_id = $this->dealership;
            $order->comments = $this->comments;
            $order->broker_ref = $this->broker_ref;
            $order->delivery_date = $this->delivery_date;
            $order->registration_company_id = $this->registration_company;
            $order->invoice_company_id = $this->invoice_company;
            $order->invoice_id = $invoice->id;
            $order->fin_number = $this->fin_number;
            $order->deal_number = $this->deal_number;
            $order->finance_type = $this->financeType;
            $order->maintenance = $this->maintenance;
            $order->term = $this->term;
            $order->initial_payment = $this->initialPayments;
            $order->terminal_pause = $this->terminalPause;
            $order->mileage = $this->mileage;
            $order->rental = $this->rental_value;
            $order->maintenance_rental = $this->maintenance_rental_value;
            $order->renewal_date = $this->renewal_date;
            $order->save();
            $this->setInvoiceValue($order, $invoice);
            foreach ($this->attachments as $attachment) {
                $file = new OrderUpload();
                $file->file_name = $attachment->store('attachments');
                $file->uploaded_by = auth()->id();
                $file->order_id = $order->id;
                $file->file_type = $attachment->getClientOriginalExtension();
                $file->save();
            }
            notify()->success(
                'Order was created successfully',
                'Order Created',
            );
        } else {
            //Update Vehicle
            $vehicle = $this->order->vehicle;
            $this->saveVehicleDetails($vehicle);

            //Update Invoice
            $invoice = $this->order->invoice;
            $this->saveInvoice($invoice);

            //Update Customer
            $customer = $this->order->customer;
            $this->saveCustomerDetails($customer);

            //Update Order
            $order = $this->order;
            $order->update([
                'vehicle_id' => $vehicle->id,
                'customer_id' => $customer->id,
                'broker_id' => $this->broker,
                'dealer_id' => $this->dealership,
                'comments' => $this->comments,
                'broker_ref' => $this->broker_ref,
                'due_date' => $this->due_date,
                'delivery_date' => $this->delivery_date,
                'registration_company_id' => $this->registration_company,
                'invoice_company_id' => $this->invoice_company,
                'invoice_id' => $invoice->id,
                'fin_number' => $this->fin_number,
                'deal_number' => $this->deal_number,
                'finance_type' => $this->financeType,
                'maintenance' => $this->maintenance,
                'term' => $this->term,
                'initial_payment' => $this->initialPayments,
                'terminal_pause' => $this->terminalPause,
                'mileage' => $this->mileage,
                'rental' => $this->rental_value,
                'maintenance_rental' => $this->maintenance_rental_value,
                'renewal_date' => $this->renewal_date,
            ]);

            if ($order->wasChanged('delivery_date')) {
                if ($order->delivery_date) {
                    $brokers = User::where('company_id', $this->broker)->get();
                    foreach ($brokers as $broker) {
                        $broker->notify(
                            new DeliveryDateSetNotification($vehicle),
                        );
                    }
                }
            }

            $this->setInvoiceValue($order, $invoice);

            foreach ($this->attachments as $attachment) {
                $file = new OrderUpload();
                $file->file_name = $attachment->store('attachments');
                $file->uploaded_by = auth()->id();
                $file->order_id = $order->id;
                $file->file_type = $attachment->getClientOriginalExtension();
                $file->save();
            }
            notify()->success(
                'Order was updated successfully',
                'Order Updated',
            );
        }
        OrderController::setProvisionalRegDate($vehicle);
        Reservation::where('vehicle_id', $vehicle->id)->delete();
        return redirect(route('order.edit', $order->id));
    }

    /**
     * Clear the customer ID
     * @return void
     */
    public function clearCustomerID(): void
    {
        $this->customer_id = null;
    }

    /**
     * Render the Order Form
     * @return Factory|View|Application
     */
    public function render(): Factory|View|Application
    {
        $companies = Company::orderBy('company_name')->get();

        $options = [
            'customers' => Customer::orderBy('customer_name')
                ->when($this->customer_name, function ($query) {
                    $query->where(
                        'customer_name',
                        'like',
                        '%' . $this->customer_name . '%',
                    );
                })
                ->paginate(5),
            'manufacturers' => Manufacturer::all()->keyBy('id'),
            'vehicle_models' => VehicleModel::where(
                'manufacturer_id',
                $this->make,
            )
                ->orderBy('name')
                ->get(),
            'types' => VehicleMeta::where('type', 'type')
                ->whereRelation(
                    'vehicle_model',
                    'vehicle_model_id',
                    '=',
                    $this->model,
                )
                ->get(),
            'derivatives' => VehicleMeta::where('type', 'derivative')
                ->whereRelation(
                    'vehicle_model',
                    'vehicle_model_id',
                    '=',
                    $this->model,
                )
                ->get(),
            'engines' => VehicleMeta::where('type', 'engine')
                ->whereRelation(
                    'vehicle_model',
                    'vehicle_model_id',
                    '=',
                    $this->model,
                )
                ->get(),
            'transmissions' => VehicleMeta::where('type', 'transmission')
                ->whereRelation(
                    'vehicle_model',
                    'vehicle_model_id',
                    '=',
                    $this->model,
                )
                ->get(),
            'fuel_types' => VehicleMeta::where('type', 'fuel')
                ->whereRelation(
                    'vehicle_model',
                    'vehicle_model_id',
                    '=',
                    $this->model,
                )
                ->get(),
            'colours' => VehicleMeta::where('type', 'colour')
                ->whereRelation(
                    'vehicle_model',
                    'vehicle_model_id',
                    '=',
                    $this->model,
                )
                ->get(),
            'trims' => VehicleMeta::where('type', 'trim')
                ->whereRelation(
                    'vehicle_model',
                    'vehicle_model_id',
                    '=',
                    $this->model,
                )
                ->get(),
            'brokers' => $companies->where('company_type', 'broker'),
            'dealers' => $companies->where('company_type', 'dealer'),
            'registration_companies' => $companies->where(
                'company_type',
                'registration',
            ),
            'invoice_companies' => $companies->where('company_type', 'invoice'),

            'factory_options' => FitOption::latest()
                ->where('option_type', 'factory')
                ->when($this->model, function ($query) {
                    $query->where('model', $this->model);
                })
                ->when($this->model_year, function ($query) {
                    $query->where('model_year', $this->model_year);
                })
                ->when($this->factoryFitSearch, function ($query) {
                    $query->where(
                        'option_name',
                        'like',
                        '%' . $this->factoryFitSearch . '%',
                    );
                })
                ->paginate(5),
            'dealer_options' => FitOption::latest()
                ->where('option_type', 'dealer')
                ->when($this->model, function ($query) {
                    $query->where('model', $this->model);
                })
                ->when($this->dealership, function ($query) {
                    $query->where('dealer_id', $this->dealership);
                })
                ->when($this->dealerFitSearch, function ($query) {
                    $query->where(
                        'option_name',
                        'like',
                        '%' . $this->dealerFitSearch . '%',
                    );
                })
                ->paginate(5),
        ];
        return view('livewire.order.order-form', $options);
    }

    /**
     * Save the details to the Invoice model
     * @param Invoice $invoice
     * @return void
     */
    public function saveInvoice(Invoice $invoice): void
    {
        $invoice->finance_commission_invoice_number =
            $this->invoice_finance_number;
        $invoice->update([
            'finance_commission_invoice_number' =>
                $this->invoice_finance_number,
            'broker_invoice_number' => $this->invoice_broker_number,
            'broker_commission_invoice_number' =>
                $this->commission_broker_number,
            'dealer_discount' => $this->dealer_discount,
            'manufacturer_discount' => $this->manufacturer_discount,
            'manufacturer_delivery_cost' => $this->manufacturer_delivery_cost,
            'onward_delivery' => $this->onward_delivery,
            'invoice_funder_for' => $this->invoice_funder_for,
            'invoice_value' => $this->invoice_finance,
            'invoice_value_to_broker' => $this->invoice_value_to_broker,
            'commission_to_broker' => $this->commission_broker,
            'commission_to_finance_company' => $this->invoice_finance,
            'finance_commission_pay_date' => $this->finance_commission_paid,
            'broker_commission_pay_date' => $this->commission_broker_paid,
            'broker_pay_date' => $this->invoice_broker_paid,
            'dealer_pay_date' => $this->dealer_pay_date,
            'dealer_invoice_number' => $this->dealer_invoice_number,
            'fleet_procure_invoice' => $this->fleet_procure_invoice,
            'fleet_procure_invoice_number' => $this->fleet_invoice_number,
            'fleet_procure_pay_date' => $this->fleet_procure_paid,
            'finance_company_bonus_invoice' => $this->finance_bonus_invoice,
            'finance_company_bonus_invoice_number' =>
                $this->finance_company_bonus_invoice_number,
            'finance_company_bonus_pay_date' =>
                $this->finance_company_bonus_pay_date,
            'ford_bonus' => $this->ford_bonus_invoice,
            'ford_bonus_pay_date' => $this->ford_bonus_pay_date,
        ]);
    }

    /**
     * Save the details to the Customer Model
     * @param Customer $customer
     * @return void
     */
    public function saveCustomerDetails(Customer $customer): void
    {
        $customer->update([
            'customer_name' => $this->customer_name,
            'address_1' => $this->delivery_address_1,
            'address_2' => $this->delivery_address_2,
            'town' => $this->delivery_town,
            'city' => $this->delivery_city,
            'county' => $this->delivery_county,
            'postcode' => $this->delivery_postcode,
            'phone_number' => $this->customer_phone,
        ]);
    }

    /**
     * Save the details to the Vehicle model
     * @param Vehicle $vehicle
     * @return void
     */
    public function saveVehicleDetails(Vehicle $vehicle): void
    {
        $vehicle->update([
            'vehicle_status' => $this->status,
            'reg' => $this->registration,
            'dealer_id' => $this->dealership,
            'broker_id' => $this->broker,
            'vehicle_registered_on' => $this->registered_date,
            'due_date' => $this->due_date,
            'build_date' => $this->build_date,
            'model_year' => $this->model_year,
            'ford_order_number' => $this->order_ref,
            'make' => $this->make,
            'model' => VehicleModel::where('id', $this->model)->first()->name,
            'chassis' => $this->chassis,
            'derivative' => $this->derivative,
            'engine' => $this->engine,
            'transmission' => $this->transmission,
            'fuel_type' => $this->fuel_type,
            'colour' => $this->colour,
            'trim' => $this->trim,
            'chassis_prefix' => $this->chassis_prefix,
            'type' => $this->type,
            'metallic_paint' => $this->metallic_paint,
            'list_price' => $this->list_price,
            'first_reg_fee' => $this->first_reg_fee,
            'rfl_cost' => $this->rfl_cost,
            'onward_delivery' => $this->onward_delivery,
            'hide_from_broker' => $this->hide_from_broker,
            'hide_from_dealer' => $this->hide_from_dealer,
            'show_in_ford_pipeline' => $this->ford_pipeline,
        ]);

        if ($this->orbit_number !== $vehicle->orbit_number) {
            $vehicle->update([
                'orbit_number' => $this->orbit_number,
            ]);
        }
        $brokers = User::where('company_id', $this->broker)->get();
        $permission = Permission::where('name', 'receive-emails')->first();
        $mailBrokers = $permission?->users
            ->where('company_id', $this->broker)
            ->all();
        if ($vehicle->wasChanged('vehicle_status')) {
            if ($vehicle->vehicle_status === '7') {
                $this->order->update(['completed_date' => now()]);
            } elseif ($vehicle->vehicle_status === '1') {
                foreach ($brokers as $broker) {
                    $broker->notify(new VehicleInStockNotification($vehicle));
                }
                if ($mailBrokers) {
                    foreach ($mailBrokers as $broker) {
                        $broker->notify(
                            new VehicleInStockEmailNotification($vehicle),
                        );
                    }
                }
            }
        }

        if ($vehicle->wasChanged('reg')) {
            foreach ($brokers as $broker) {
                $broker->notify(
                    new RegistrationNumberAddedNotification($vehicle),
                );
            }
            if ($mailBrokers) {
                foreach ($mailBrokers as $broker) {
                    $broker->notify(
                        new RegistrationNumberAddedEmailNotification($vehicle),
                    );
                }
            }
        }

        $fitOptions = array_merge(
            $this->factoryFitOptions,
            $this->dealerFitOptions,
        );

        $vehicle->fitOptions()->sync($fitOptions);
    }

    /**
     * Set the Invoice Value based on either the amounts set on the order or an override
     * @param $order
     * @param $invoice
     * @return void
     */
    public function setInvoiceValue($order, $invoice): void
    {
        if ($this->dealer_invoice_override_allowed) {
            if ($this->dealer_invoice_override < 0) {
                $invoice->invoice_value_from_dealer =
                    $this->dealer_invoice_override * -1;
                $invoice->invoice_value_to_dealer = null;
            } else {
                $invoice->invoice_value_to_dealer =
                    $this->dealer_invoice_override;
                $invoice->invoice_value_from_dealer = null;
            }
            $invoice->dealer_value_overruled = true;
        } else {
            $invoice_value = $order->invoiceDifferenceExVat();
            if ($invoice_value < 0) {
                $invoice->invoice_value_from_dealer = $invoice_value * -1;
                $invoice->invoice_value_to_dealer = null;
            } else {
                $invoice->invoice_value_to_dealer = $invoice_value;
                $invoice->invoice_value_from_dealer = null;
            }
            $invoice->dealer_value_overruled = false;
        }

        $invoice->save();
    }

    /**
     * Get the dealer discount based on the model of the vehicle and the Dealer
     * @return void
     */
    private function getDealerDiscount(): void
    {
        if (!$this->dealer_discount_override) {
            $this->dealer_discount =
                floatval(
                    DealerDiscount::where('model_id', '=', $this->model)
                        ->where('dealer_id', '=', $this->dealership)
                        ->first()?->discount,
                ) ?? 0;
            $this->dispatchBrowserEvent('dealerDiscountChanged');
        }
    }
}
