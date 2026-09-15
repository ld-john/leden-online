<?php

namespace App\Http\Livewire\Order;

use App\Enums\VehicleStatus;
use App\Http\Controllers\OrderController;
use App\Livewire\Forms\BondForm;
use App\Livewire\Forms\CostForm;
use App\Livewire\Forms\CustomerForm;
use App\Models\Bond;
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
use App\Models\TacticalOffer;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleMeta;
use App\Models\VehicleModel;
use App\Notifications\DeliveryDateSetNotification;
use App\Notifications\RegistrationNumberAddedEmailNotification;
use App\Notifications\RegistrationNumberAddedNotification;
use App\Notifications\VehicleInStockEmailNotification;
use App\Notifications\VehicleInStockNotification;
use ErrorException;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use LaravelIdea\Helper\App\Models\_IH_FitOption_C;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class OrderForm extends Component
{
    use WithFileUploads;

    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public CustomerForm $customer;
    public CostForm $cost;
    public BondForm $bond;

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
    public ?VehicleStatus $status = null;
    public $build_date; //Vehicle
    public $due_date; // Vehicle
    public $delivery_date; // Order
    public $model_year;
    public $registered_date;
    public $contract_date;
    public $ford_pipeline = '0';
    public $factoryFitOptions = []; // Vehicle -> JSON of IDs to fit_options
    public $dealerFitOptions = []; // Vehicle
    public $dealership;
    public $registration_company; // Order
    public $invoice_company; // Order
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
    public $dealer_invoice_override = false;
    public $fleet_procure_invoice;
    public $fleet_invoice_number;
    public $fleet_procure_paid;
    public $finance_bonus_invoice;
    public $finance_company_bonus_invoice_number;
    public bool $dealer_invoice_override_allowed = false;
    public $finance_company_bonus_pay_date;
    public $fin_number = '65634';
    public $deal_number = '90191';

    public bool $exclusion = false;
    public $finance_broker_toggle = false;
    public $finance_broker;
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
    public $delivery_month;
    public $recall_reason;
    public ?string $order_date;
    public ?string $terminal_pause;

    public ?array $selectedOffers = [];
    public $now;
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
        'build_date' => 'nullable|date',
        'due_date' => 'nullable|date',
        'registered_date' => 'nullable|date',
        'contract_date' => 'nullable|date',
        'dealer_pay_date' => 'nullable|date',
        'invoice_broker_paid' => 'nullable|date',
        'commission_broker_paid' => 'nullable|date',
        'finance_commission_paid' => 'nullable|date',
        'finance_broker' => 'nullable|numeric|different:broker',
    ];
    protected $messages = [
        'make.required_without' => 'No <strong>Make</strong> selected',
        'finance_broker.different' =>
            '<strong>Broker</strong> and <strong>Finance Broker</strong> need to be different brokers',
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
    public function mount(): void
    {
        $this->now = date('Y-m-d');

        $this->financeTypeOptions = FinanceType::all();
        $this->maintenanceOptions = Maintenance::all();
        $this->termOptions = Term::all();
        $this->initialPaymentsOptions = InitialPayment::all();
        $this->mileageOptions = Mileage::all();

        if (isset($this->vehicle)) {
            $this->registered_date = $this->vehicle->vehicle_registered_on;
            $this->due_date = $this->vehicle->due_date?->format('Y-m-d') ?? '';
            $this->build_date = $this->vehicle->build_date?->format('Y-m-d') ?? '';
            $this->contract_date = $this->vehicle->contract_date;
            $this->recall_reason = $this->vehicle->recall_reason;

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
            $this->cost->list_price = $this->vehicle->list_price;
            $this->cost->metallic_paint = $this->vehicle->metallic_paint;
            $this->cost->first_reg_fee = $this->vehicle->first_reg_fee;
            $this->cost->rfl_cost = $this->vehicle->rfl_cost;
            $this->cost->onward_delivery = $this->vehicle->onward_delivery;
            $this->cost->show_discount = $this->vehicle->show_discount;
            $this->cost->show_offer = $this->vehicle->show_offer;
            $this->cost->hide_from_broker = $this->vehicle->hide_from_broker;
            $this->cost->hide_from_dealer = $this->vehicle->hide_from_dealer;
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
            $this->selectedOffers = $this->vehicle->tacticalOffers()->pluck('tactical_offers.id')->toArray();
            $this->cost->getDealerDiscount($this->model, $this->dealership);
        }
        if (isset($this->order)) {

            $this->order->load('bond', 'customer');

            $this->bond->setBond($this->order->bond);
            $this->customer->setCustomer($this->order->customer);

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
            $this->finance_broker_toggle = $this->order->finance_broker_toggle;
            $this->finance_broker = $this->order->finance_broker_id;
            $this->delivery_month = $this->order->delivery_month;

            $this->cost->dealer_discount_override = true;

            $this->invoice_finance =
                $this->order->invoice->commission_to_finance_company;
            $this->cost->leden_discount = $this->order->invoice->leden_discount;
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
            $this->cost->dealer_discount = $this->order->invoice->dealer_discount;
            $this->cost->manufacturer_discount =
                $this->order->invoice->manufacturer_discount;
            $this->cost->invoice_funder_for =
                $this->order->invoice->invoice_funder_for;
            $this->cost->manufacturer_delivery_cost =
                $this->order->invoice->manufacturer_delivery_cost;
            $this->cost->grant = $this->order->invoice->grant;
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
            $this->recall_reason = $this->order->vehicle->recall_reason;
            $this->broker_ref = $this->order->broker_ref;
            $this->comments = $this->order->comments;
            $this->registration_company = $this->order->registration_company_id;
            $this->invoice_company = $this->order->invoice_company_id;
            $this->dealership = $this->order->dealer_id;
            $this->broker = $this->order->broker_id;
            $this->delivery_date = $this->order->delivery_date;
            $this->registered_date =
                $this->order->vehicle->vehicle_registered_on;
            $this->build_date = $this->order->vehicle->build_date?->format('Y-m-d') ?? '';
            $this->due_date = $this->order->vehicle->due_date?->format('Y-m-d') ?? '';
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
            $this->cost->list_price = $this->order->vehicle->list_price;
            $this->cost->metallic_paint = $this->order->vehicle->metallic_paint;
            $this->cost->first_reg_fee = $this->order->vehicle->first_reg_fee;
            $this->cost->rfl_cost = $this->order->vehicle->rfl_cost;
            $this->cost->onward_delivery = $this->order->vehicle->onward_delivery;
            $this->cost->show_discount = $this->order->vehicle->show_discount;
            $this->cost->show_offer = $this->order->vehicle->show_offer;
            $this->cost->hide_from_broker = $this->order->vehicle->hide_from_broker;
            $this->cost->hide_from_dealer = $this->order->vehicle->hide_from_dealer;
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
            $this->exclusion = $this->order->exception;
            $this->selectedOffers = $this->order->vehicle?->tacticalOffers()->pluck('tactical_offers.id')->toArray();
        }
    }

    public function updatedStatus($value): void
    {
        if (is_int($value)) {
            $this->status = VehicleStatus::tryFrom($value);
        }
    }

    /**
     * When the Make is updated, clear the vehicle model.
     * @return void
     */
    public function updatedMake(): void
    {
        $this->model = null;
        $this->reset('selectedOffers');
    }


    public function updatedSelectedOffers(): void
    {
        if (end($this->selectedOffers) === 'none') {
            $this->selectedOffers = ['none'];
        } elseif (count($this->selectedOffers) > 1 && in_array('none', $this->selectedOffers)) {
            $this->selectedOffers = array_diff($this->selectedOffers, ['none']);
            $this->selectedOffers = array_values($this->selectedOffers);
        }
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
        $this->cost->getDealerDiscount($this->model, $this->dealership);
        $this->reset('selectedOffers');
    }

    public function scrollToTop(): void
    {
        $this->dispatch('scroll-to-top');
    }

    /**
     * When the Dealership is updated, check for the discount they offer
     * @return void
     */
    public function updatedDealership(): void
    {
        $this->cost->getDealerDiscount($this->model, $this->dealership);
    }

    public function updatedCostDealerDiscountOverride(bool $value): void
    {
        if (! $value) {
            $this->cost->getDealerDiscount($this->model, $this->dealership);
        }
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
     * Sanitise empty or invalid date strings to null.
     */
    private function sanitiseDate(?string $date): ?string
    {
        if (empty($date) || $date === '-0001-11-30' || $date === '0000-00-00') {
            return null;
        }

        return $date;
    }

    /**
     * Compile order attributes into a reusable array.
     */
    private function getOrderAttributes(int $vehicleId, Customer $customer, int $invoiceId, ?Bond $bond): array
    {
        return [
            'vehicle_id'               => $vehicleId,
            'customer_id'              => $customer->id,
            'broker_id'                => $this->broker,
            'finance_broker_toggle'    => $this->finance_broker_toggle,
            'finance_broker_id'        => $this->finance_broker,
            'dealer_id'                => $this->dealership,
            'comments'                 => $this->comments,
            'broker_ref'               => $this->broker_ref,
            'due_date'                 => $this->due_date ?? null,
            'delivery_date'            => $this->delivery_date,
            'registration_company_id'  => $this->registration_company,
            'invoice_company_id'       => $this->invoice_company,
            'invoice_id'               => $invoiceId,
            'fin_number'               => $this->fin_number,
            'deal_number'              => $this->deal_number,
            'finance_type'             => $this->financeType,
            'maintenance'              => $this->maintenance,
            'term'                     => $this->term,
            'initial_payment'          => $this->initialPayments,
            'terminal_pause'           => $this->terminalPause,
            'mileage'                  => $this->mileage,
            'rental'                   => $this->rental_value,
            'maintenance_rental'       => $this->maintenance_rental_value,
            'renewal_date'             => $this->renewal_date,
            'exception'                => $this->exclusion,
            'delivery_month'           => $this->delivery_month,
            'bond_id'                  => $bond?->id,
        ];
    }

    public function saveOrder()
    {
        $this->orbit_number = empty($this->orbit_number) ? null : $this->orbit_number;
        $this->terminalPause = ($this->terminalPause === 'yes' || $this->terminalPause === true);

        $this->delivery_date = $this->sanitiseDate($this->delivery_date);
        $this->registered_date = $this->sanitiseDate($this->registered_date);
        $this->contract_date = $this->sanitiseDate($this->contract_date);

        $this->customer->validate();
        $this->cost->validate();
        $this->validate();

        $order = DB::transaction(function () {
            $isNewOrder = !isset($this->order);

            if ($isNewOrder) {
                if (isset($this->vehicle)) {
                    $vehicle = $this->vehicle;
                    $vehicle->orbit_number = $this->orbit_number;
                } elseif (empty($this->orbit_number)) {
                    $vehicle = Vehicle::create();
                } else {
                    $vehicle = Vehicle::firstOrCreate(['orbit_number' => $this->orbit_number]);
                    if ($vehicle->order()->exists()) {
                        throw new ErrorException('Vehicle is already assigned to an existing order.');
                    }
                }
            } else {
                $vehicle = $this->order->vehicle;
            }

            $this->saveVehicleDetails($vehicle);

            $customer = $this->customer->save();

            $invoice = $isNewOrder ? Invoice::create() : $this->order->invoice;
            $this->saveInvoice($invoice);

            $bond = $this->bond->save();

            $orderAttributes = $this->getOrderAttributes($vehicle->id, $customer, $invoice->id, $bond);

            if ($isNewOrder) {
                $order = Order::create($orderAttributes);
                notify()->success('Order was created successfully', 'Order Created');
            } else {
                $order = $this->order;
                $order->update($orderAttributes);

                if ($order->wasChanged('delivery_date') && $order->delivery_date) {
                    $brokers = User::where('company_id', $this->broker)->get();
                    foreach ($brokers as $broker) {
                        $broker->notify(new DeliveryDateSetNotification($vehicle));
                    }
                }

                notify()->success('Order was updated successfully', 'Order Updated');
            }

            $this->setInvoiceAndAttachments($order, $invoice);

            OrderController::setProvisionalRegDate($vehicle);
            Reservation::where('vehicle_id', $vehicle->id)->delete();

            return $order;
        });

        return to_route('order.edit', $order->id);
    }

    /**
     * Clear the customer ID
     * @return void
     */
    public function clearCustomerID(): void
    {
        $this->customer->clear();
    }

    #[Computed]
    public function factory_options(): _IH_FitOption_C|array|LengthAwarePaginator
    {
        return FitOption::latest()
            ->where('option_type', 'factory')
            ->whereNull('archived_at')
            ->when($this->model, fn($q) => $q->where('model', $this->model))
            ->when($this->model_year, fn($q) => $q->where('model_year', $this->model_year))
            ->when($this->factoryFitSearch, fn($q) => $q->where('option_name', 'like', '%' . $this->factoryFitSearch . '%'))
            ->paginate(5, ['*'], 'factory_page');
    }

    #[Computed]
    public function dealer_options(): _IH_FitOption_C|array|LengthAwarePaginator
    {
        return FitOption::latest()
            ->where('option_type', 'dealer')
            ->whereNull('archived_at')
            ->when($this->model, fn($q) => $q->where('model', $this->model))
            ->when($this->dealership, fn($q) => $q->where('dealer_id', $this->dealership))
            ->when($this->dealerFitSearch, fn($q) => $q->where('option_name', 'like', '%' . $this->dealerFitSearch . '%'))
            ->paginate(5, ['*'], 'dealer_options');
    }

    #[Computed]
    public function selectedFactoryFitOptions(): _IH_FitOption_C|array|Collection|FitOption|null
    {
        if (empty($this->factoryFitOptions))
        {
            return collect();
        }

        return FitOption::find($this->factoryFitOptions);
    }

    #[Computed]
    public function selectedDealerFitOptions(): _IH_FitOption_C|array|Collection|FitOption|null
    {
        if (empty($this->dealerFitOptions))
        {
            return collect();
        }
        return FitOption::find($this->dealerFitOptions);
    }

    /**
     * Render the Order Form
     * @return Factory|View|Application
     */
    public function render(): Factory|View|Application
    {
        $companies = Company::orderBy('company_name')->get();

        $metallicPaintDiscount = DealerDiscount::where('model_id', $this->model)
            ->where('dealer_id', $this->dealership)
            ->get()
            ->pluck('paint_discount');

        if ($metallicPaintDiscount->isNotEmpty()) {
            $metallicPaintDiscount = $metallicPaintDiscount[0];
        } else {
            $metallicPaintDiscount = 0;
        }

        $options = [
            'metallic_paint_discount' => $metallicPaintDiscount,
            'customers' => $this->customer->getSearchResults(),
            'manufacturers' => Manufacturer::all()
                ->keyBy('id')
                ->sortby('name'),
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
            'delivery_agents' => $companies->where('company_type', 'delivery'),
            'registration_companies' => $companies->where(
                'company_type',
                'registration',
            ),
            'invoice_companies' => $companies->where('company_type', 'invoice'),
            'delivery_months' => [
                ['id' => 'Jan', 'name' => 'January'],
                ['id' => 'Feb', 'name' => 'February'],
                ['id' => 'Mar', 'name' => 'March'],
                ['id' => 'Apr', 'name' => 'April'],
                ['id' => 'May', 'name' => 'May'],
                ['id' => 'Jun', 'name' => 'June'],
                ['id' => 'Jul', 'name' => 'July'],
                ['id' => 'Aug', 'name' => 'August'],
                ['id' => 'Sep', 'name' => 'September'],
                ['id' => 'Oct', 'name' => 'October'],
                ['id' => 'Nov', 'name' => 'November'],
                ['id' => 'Dec', 'name' => 'December']
            ]
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
            'leden_discount' => $this->cost->leden_discount,
            'grant' => $this->cost->grant,
            'finance_commission_invoice_number' =>
                $this->invoice_finance_number,
            'broker_invoice_number' => $this->invoice_broker_number,
            'broker_commission_invoice_number' =>
                $this->commission_broker_number,
            'dealer_discount' => $this->cost->dealer_discount,
            'manufacturer_discount' => $this->cost->manufacturer_discount,
            'manufacturer_delivery_cost' => $this->cost->manufacturer_delivery_cost,
            'onward_delivery' => $this->cost->onward_delivery,
            'invoice_funder_for' => $this->cost->invoice_funder_for,
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

    #[Computed]
    public function availableStatuses(): Collection
    {
        $statuses = VehicleStatus::cases();

        return collect($statuses)->filter(function($status) {
            if ($status === VehicleStatus::IN_STOCK_REGISTERED && !$this->registered_date) {
                return false;
            }

            if ($status === VehicleStatus::COMPLETED_ORDERS) {
                return $this->registered_date && $this->registered_date < now();
            }

            return true;
        });
    }



    /**
     * Save the details to the Vehicle model
     * @param Vehicle $vehicle
     * @return void
     */
    public function saveVehicleDetails(Vehicle $vehicle): void
    {
        if ($this->due_date === '') {
            $this->due_date = null;
        }

        $vehicle->update([
            'vehicle_status' => $this->status,
            'recall_reason' => $this->recall_reason,
            'reg' => $this->registration,
            'dealer_id' => $this->dealership,
            'broker_id' => $this->broker,
            'vehicle_registered_on' => $this->registered_date,
            'due_date' => $this->due_date,
            'build_date' => $this->build_date,
            'contract_date' => $this->contract_date,
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
            'metallic_paint' => $this->cost->metallic_paint,
            'list_price' => $this->cost->list_price,
            'first_reg_fee' => $this->cost->first_reg_fee,
            'rfl_cost' => $this->cost->rfl_cost,
            'onward_delivery' => $this->cost->onward_delivery,
            'hide_from_broker' => $this->cost->hide_from_broker,
            'hide_from_dealer' => $this->cost->hide_from_dealer,
            'show_in_ford_pipeline' => $this->ford_pipeline,
        ]);

        if ($this->orbit_number !== $vehicle->orbit_number) {
            $vehicle->update([
                'orbit_number' => $this->orbit_number,
            ]);
        }

        $actualOffers = array_filter($this->selectedOffers, fn ($id) => $id !== 'none');

        $pivotData = [];

        if (!empty($actualOffers)) {
            $offers = TacticalOffer::whereIn('id', $actualOffers)
                ->active()
                ->get();

            $pivotData = $offers->pluck('value', 'id')
                ->mapWithKeys(function($value, $id) {
                    return [
                        $id => [
                            'applied_amount' => $value
                        ]
                    ];
                })
                ->toArray();

        }
        $vehicle
            ->tacticalOffers()
            ->sync($pivotData);

        $brokers = User::where('company_id', $this->broker)->get();
        $permission = Permission::where('name', 'receive-emails')->first();

        $mailBrokers = $permission?->users
            ->where('company_id', $this->broker)
            ->all();

        if ($vehicle->wasChanged('vehicle_status')) {
            if ($vehicle->vehicle_status == VehicleStatus::COMPLETED_ORDERS) {
                $this->order->update(['completed_date' => now()]);
            } elseif ( $vehicle->vehicle_status?->isInStock()) {
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

                $vehicle->update([
                    'due_date' => null,
                ]);
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
        $isOverridden = $this->dealer_invoice_override_allowed;

        $rawValue = $isOverridden ? $this->dealer_invoice_override : $order->invoiceDifferenceExVat();

        $invoice->invoice_value_from_dealer = ($rawValue < 0) ? abs($rawValue) : null;
        $invoice->invoice_value_to_dealer = ($rawValue >= 0) ? $rawValue : null;

        $invoice->dealer_value_overruled = $isOverridden;

        $invoice->save();
    }

    public function updatingFactoryFitSearch(): void
    {
        $this->resetPage('factoryOptionsPage');
    }
    public function updatingDealerFitSearch(): void
    {
        $this->resetPage('dealerOptionsPage');
    }

    /**
     * @param Order $order
     * @param Invoice $invoice
     * @return void
     */
    public function setInvoiceAndAttachments(Order $order, Invoice $invoice): void
    {
        $pendingOffers = $order->vehicle->tacticalOffers()->wherePivot('order_id', null)->get();

        foreach ($pendingOffers as $offer) {
            $order->vehicle->tacticalOffers()->updateExistingPivot($offer->id, [
                'order_id' => $order->id
            ]);
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
    }
}
