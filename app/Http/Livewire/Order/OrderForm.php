<?php

namespace App\Http\Livewire\Order;

use App\Company;
use App\Customer;
use App\FitOption;
use App\Invoice;
use App\Manufacturer;
use App\Notifications\DeliveryDateSetNotification;
use App\Notifications\VehicleInStockNotification;
use App\Order;
use App\OrderUpload;
use App\Reservation;
use App\User;
use App\Vehicle;
use App\VehicleMeta\Colour;
use App\VehicleMeta\Derivative;
use App\VehicleMeta\Engine;
use App\VehicleMeta\Fuel;
use App\VehicleMeta\Transmission;
use App\VehicleMeta\Trim;
use App\VehicleMeta\Type;
use Carbon\Carbon;
use ErrorException;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use DateTime;
use Livewire\WithPagination;

class OrderForm extends Component
{
    use WithFileUploads;
    use WithPagination;
    protected string $paginationTheme = 'bootstrap';
    public function getQueryString(): array
    {
        return [];
    }
    public bool $trimInput = true;
    public bool $colourInput = true;
    public bool $fuelInput = true;
    public bool $transmissionInput = true;
    public bool $engineInput = true;
    public bool $derivativeInput = true;
    public bool $modelInput = true;
    public bool $makeInput = true;
    public bool $showAdditionalInformation = false;
    public bool $showDeliveryInformation = false;
    public bool $showInvoicingInformation = false;
    public bool $showCostBreakdown = false;
    public bool $showCompanyInfo = false;
    public bool $showDealerFitOptions = false;
    public bool $showFactoryFitOptions = false;
    public bool $showVehicleInfo = false;
    public bool $showCustomerInfo = true;
    public $vehicle;
    public $order;
    public $newCustomer = false;
    public $customer_id;
    public $customer_name;
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
     * @throws Exception
     */
    public function mount()
    {
        $this->now = date('Y-m-d');
        $newCustomer = $this->newCustomer;

        if (isset($this->vehicle)) {
            if ($this->vehicle->vehicle_registered_on) {
                $reg = new DateTime($this->vehicle->vehicle_registered_on);
                $this->registered_date = $reg->format('Y-m-d');
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
        }
        if (isset($this->order)) {
            // Handle Dates coming in so JS can play nice with them.
            if ($this->order->due_date) {
                $del = new DateTime($this->order->due_date);
                $this->due_date = $del->format('Y-m-d');
            }
            if ($this->order->delivery_date) {
                $del = new DateTime($this->order->delivery_date);
                $this->delivery_date = $del->format('Y-m-d');
            }
            if ($this->order->vehicle->vehicle_registered_on) {
                $reg = new DateTime(
                    $this->order->vehicle->vehicle_registered_on,
                );
                $this->registered_date = $reg->format('Y-m-d');
            }
            if ($this->order->invoice->dealer_pay_date) {
                $dpd = new DateTime($this->order->invoice->dealer_pay_date);
                $this->dealer_pay_date = $dpd->format('Y-m-d');
            }
            if ($this->order->invoice->broker_pay_date) {
                $dpd = new DateTime($this->order->invoice->broker_pay_date);
                $this->invoice_broker_paid = $dpd->format('Y-m-d');
            }
            if ($this->order->invoice->broker_commission_pay_date) {
                $dpd = new DateTime(
                    $this->order->invoice->broker_commission_pay_date,
                );
                $this->commission_broker_paid = $dpd->format('Y-m-d');
            }
            if ($this->order->invoice->finance_commission_pay_date) {
                $dpd = new DateTime(
                    $this->order->invoice->finance_commission_pay_date,
                );
                $this->finance_commission_paid = $dpd->format('Y-m-d');
            }
            if ($this->order->invoice->fleet_procure_pay_date) {
                $date = new DateTime(
                    $this->order->invoice->fleet_procure_pay_date,
                );
                $this->fleet_procure_paid = $date->format('Y-m-d');
            }

            if ($this->order->invoice->finance_company_bonus_pay_date) {
                $date = new DateTime(
                    $this->order->invoice->finance_company_bonus_pay_date,
                );
                $this->finance_company_bonus_pay_date = $date->format('Y-m-d');
            }

            if ($this->order->invoice->ford_bonus_pay_date) {
                $date = new DateTime(
                    $this->order->invoice->ford_bonus_pay_date,
                );
                $this->ford_bonus_pay_date = $date->format('Y-m-d');
            }

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
        }
    }

    public function updatedFactoryFitOptions()
    {
        $this->factoryFitOptionsArray = FitOption::find(
            $this->factoryFitOptions,
        );
    }

    public function updatedDealerFitOptions()
    {
        $this->dealerFitOptionsArray = FitOption::find($this->dealerFitOptions);
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

    public function removeAttachment($key)
    {
        unset($this->attachments[$key]);
    }

    /**
     * @throws ErrorException
     */
    public function orderFormSubmit()
    {
        if ($this->orbit_number === '') {
            $this->orbit_number = null;
        }
        $this->validate();
        if (!isset($this->order)) {
            if (isset($this->newmake)) {
                $slug = Str::slug($this->newmake);
                $manufacturer = Manufacturer::firstOrCreate(
                    ['slug' => $slug],
                    [
                        'name' => ucwords($this->newmake),
                        'models' => json_encode($this->model),
                    ],
                );
                $this->make = $manufacturer->id;
            }
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
                $vehicle = new Vehicle();
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
                $customer = new Customer();
                $this->saveCustomerDetails($customer);
                $customer = $customer->id;
            } else {
                $customer = $this->customer_id;
            }
            $invoice = new Invoice();
            $this->saveInvoice($invoice);
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
            $order->fin_number = $this->fin_number;
            $order->deal_number = $this->deal_number;
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
            session()->flash('message', 'Order Created');
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
                'delivery_date' => Carbon::parse($this->delivery_date)
                    ->startOfDay()
                    ->format('Y-m-d h:i:s'),
                'registration_company_id' => $this->registration_company,
                'invoice_company_id' => $this->invoice_company,
                'invoice_id' => $invoice->id,
                'fin_number' => $this->fin_number,
                'deal_number' => $this->deal_number,
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

            session()->flash('message', 'Order Updated');
        }
        Reservation::where('vehicle_id', $vehicle->id)->delete();
        return redirect(route('order.edit', $order->id));
    }

    public function clearCustomerID()
    {
        $this->customer_id = null;
    }

    public function render(): Factory|View|Application
    {
        $companies = Company::orderBy('company_name', 'asc')->get();

        $options = [
            'customers' => Customer::orderBy('customer_name', 'asc')
                ->when($this->customer_name, function ($query) {
                    $query->where(
                        'customer_name',
                        'like',
                        '%' . $this->customer_name . '%',
                    );
                })
                ->paginate(5),
            'manufacturers' => Manufacturer::all()->keyBy('id'),
            'types' => Type::all(),
            'derivatives' => Derivative::all(),
            'engines' => Engine::all(),
            'transmissions' => Transmission::all(),
            'fuel_types' => Fuel::all(),
            'colours' => Colour::all(),
            'trims' => Trim::all(),

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
     * @param Invoice $invoice
     * @return void
     */
    public function saveInvoice(Invoice $invoice): void
    {
        $invoice->finance_commission_invoice_number =
            $this->invoice_finance_number;
        $invoice->broker_invoice_number = $this->invoice_broker_number;
        $invoice->broker_commission_invoice_number =
            $this->commission_broker_number;
        $invoice->dealer_discount = $this->dealer_discount;
        $invoice->manufacturer_discount = $this->manufacturer_discount;
        $invoice->manufacturer_delivery_cost =
            $this->manufacturer_delivery_cost;
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
        $invoice->fleet_procure_invoice = $this->fleet_procure_invoice;
        $invoice->fleet_procure_invoice_number = $this->fleet_invoice_number;
        $invoice->fleet_procure_pay_date = $this->fleet_procure_paid;
        $invoice->finance_company_bonus_invoice = $this->finance_bonus_invoice;
        $invoice->finance_company_bonus_invoice_number =
            $this->finance_company_bonus_invoice_number;
        $invoice->finance_company_bonus_pay_date =
            $this->finance_company_bonus_pay_date;
        $invoice->ford_bonus = $this->ford_bonus_invoice;
        $invoice->ford_bonus_pay_date = $this->ford_bonus_pay_date;

        $invoice->save();
    }

    /**
     * @param Customer $customer
     * @return void
     */
    public function saveCustomerDetails(Customer $customer): void
    {
        $customer->customer_name = $this->customer_name;
        $customer->address_1 = $this->delivery_address_1;
        $customer->address_2 = $this->delivery_address_2;
        $customer->town = $this->delivery_town;
        $customer->city = $this->delivery_city;
        $customer->county = $this->delivery_county;
        $customer->postcode = $this->delivery_postcode;
        $customer->phone_number = $this->customer_phone;

        $customer->save();
    }

    /**
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
            'model_year' => $this->model_year,
            'ford_order_number' => $this->order_ref,
            'make' => $this->make,
            'model' => $this->model,
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

        if ($vehicle->wasChanged('vehicle_status')) {
            if ($vehicle->vehicle_status === '7') {
                $this->order->update(['completed_date' => now()]);
            } elseif ($vehicle->vehicle_status === '1') {
                $brokers = User::where('company_id', $this->broker)->get();
                foreach ($brokers as $broker) {
                    $broker->notify(new VehicleInStockNotification($vehicle));
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
}
