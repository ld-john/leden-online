<?php

namespace App\Http\Livewire\Order;

use App\Enums\VehicleStatus;
use App\Http\Controllers\OrderController;
use App\Livewire\Forms\BondForm;
use App\Livewire\Forms\CostForm;
use App\Livewire\Forms\CustomerForm;
use App\Livewire\Forms\VehicleForm;
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
use Carbon\Carbon;
use ErrorException;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\NoReturn;
use LaravelIdea\Helper\App\Models\_IH_FitOption_C;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\Features\SupportRedirects\Redirector;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class OrderForm extends Component
{
    use WithFileUploads;

    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public CustomerForm $customer;
    public BondForm $bond;
    public VehicleForm $vehicleForm;
    public CostForm $cost;

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

    public $broker;
    public $broker_ref; // Order
    public $order_ref;
    public $delivery_date; // Order

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
    public $order_date;
    public ?string $terminal_pause = null;

    public $now;
    protected $rules = [
        'attachments.*' => 'max:4096',
        'delivery_date' => 'nullable|date',
        'dealer_pay_date' => 'nullable|date',
        'invoice_broker_paid' => 'nullable|date',
        'commission_broker_paid' => 'nullable|date',
        'finance_commission_paid' => 'nullable|date',
        'finance_broker' => 'nullable|numeric|different:vehicleForm.broker_id',
    ];
    protected $messages = [
        'finance_broker.different' =>
            '<strong>Broker</strong> and <strong>Finance Broker</strong> need to be different brokers',
        'attachments.*.max' =>
            'An <strong>Attachment</strong> is too big! (Max 4Mb)',
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

            $this->vehicleForm->setVehicle($this->vehicle);
            $this->bond->setBond($this->vehicle->bond);
            $this->cost->setCosts($this->vehicle);

        }
        if (isset($this->order)) {

            $this->order->load('bond', 'customer');

            $this->bond->setBond($this->order->bond);
            $this->customer->setCustomer($this->order->customer);
            $this->vehicleForm->setVehicle($this->order->vehicle);
            $this->cost->setCosts($this->order->vehicle);

            $this->order_date = $this->formatDate($this->order->order_date ?? $this->order->created_at);

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
            $this->broker_ref = $this->order->broker_ref;
            $this->comments = $this->order->comments;
            $this->registration_company = $this->order->registration_company_id;
            $this->invoice_company = $this->order->invoice_company_id;
            $this->delivery_date = $this->formatDate($this->order->delivery_date);
            $this->fin_number = $this->order->fin_number;
            $this->deal_number = $this->order->deal_number;
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
            $this->renewal_date = $this->formatDate($this->order->renewal_date);
            $this->exclusion = $this->order->exception;

            $this->cost->setInvoiceCosts($this->order->invoice);
        }
    }

    public function updatedVehicleFormStatus($value): void
    {
        if (is_int($value)) {
            $this->vehicleForm->vehicle_status = VehicleStatus::tryFrom($value);
        }
    }

    /**
     * When the Make is updated, clear the vehicle model.
     * @return void
     */
    public function updatedVehicleFormMake(): void
    {
        $this->vehicleForm->model = null;
        $this->reset('vehicleForm.selectedOffers');
    }


    public function updatedVehicleFormSelectedOffers(): void
    {
        if (end($this->vehicleForm->selectedOffers) === 'none') {
            $this->vehicleForm->selectedOffers = ['none'];
        } elseif (count($this->vehicleForm->selectedOffers) > 1 && in_array('none', $this->vehicleForm->selectedOffers)) {
            $this->vehicleForm->selectedOffers = array_diff($this->vehicleForm->selectedOffers, ['none']);
            $this->vehicleForm->selectedOffers = array_values($this->vehicleForm->selectedOffers);
        }
    }

    /**
     * When the Vehicle Model is updated, clear old information and check the dealer discount on offer/
     * @return void
     */
    public function updatedVehicleFormModel(): void
    {
        $this->vehicleForm->type = null;
        $this->vehicleForm->derivative = null;
        $this->vehicleForm->engine = null;
        $this->vehicleForm->transmission = null;
        $this->vehicleForm->fuel_type = null;
        $this->vehicleForm->colour = null;
        $this->vehicleForm->trim = null;
        $this->cost->getDealerDiscount($this->vehicleForm->model, $this->vehicleForm->dealer_id);
        $this->reset('vehicleForm.selectedOffers');
    }

    public function scrollToTop(): void
    {
        $this->dispatch('scroll-to-top');
    }

    /**
     * When the Dealership is updated, check for the discount they offer
     * @return void
     */
    public function updatedVehicleFormDealerId(): void
    {
        $this->cost->getDealerDiscount($this->vehicleForm->model, $this->vehicleForm->dealer_id);
    }

    public function updatedCostDealerDiscountOverride(bool $value): void
    {
        if (! $value) {
            $this->cost->getDealerDiscount($this->vehicleForm->model, $this->vehicleForm->dealer_id);
        }
    }


    /**
     * Updates the factory fit options array when changes are made in the form
     * @return void
     */
    public function updatedFactoryFitOptions(): void
    {
        $this->vehicleForm->factoryFitOptionsArray = FitOption::find(
            $this->vehicleForm->factoryFitOptions,
        );
    }

    #[NoReturn]
    public function updatedCustomerId($value): void
    {
        $customer = Customer::where('id', $value)->first();

        $this->customer->customerDetails($customer);
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
    public function formatDate(mixed $value) : ?string
    {
        if (blank($value) || str_starts_with((string) $value, '0000-00-00')) {
            return null;
        }

        return Carbon::parse($value)->format('Y-m-d');
    }

    /**
     * Compile order attributes into a reusable array.
     */
    private function getOrderAttributes(int $vehicleId, Customer $customer, int $invoiceId): array
    {
        return [
            'vehicle_id'               => $vehicleId,
            'customer_id'              => $customer->id,
            'broker_id'                => $this->vehicleForm->broker_id,
            'finance_broker_toggle'    => $this->finance_broker_toggle,
            'finance_broker_id'        => $this->finance_broker,
            'dealer_id'                => $this->vehicleForm->dealer_id,
            'comments'                 => $this->comments,
            'broker_ref'               => $this->broker_ref,
            'due_date'                 => $this->vehicleForm->due_date ?? null,
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
            'order_date'               => $this->order_date
        ];
    }

    public function saveOrder(): RedirectResponse|Redirector
    {

        $this->terminalPause = ($this->terminalPause === 'yes' || $this->terminalPause === true);

        $this->delivery_date = $this->formatDate($this->delivery_date);

        $this->order_date = $this->formatDate($this->order_date);

        $this->customer->validate();
        $this->validate();

        $order = DB::transaction(function () {
            $isNewOrder = !isset($this->order);

            $vehicle = $this->vehicleForm->createOrFindVehicle();

            $bond = $this->bond->save();

            $this->vehicleForm->save($bond);

            $this->cost->saveCosts($vehicle);

            $customer = $this->customer->save();

            $invoice = $isNewOrder ? Invoice::create() : $this->order->invoice;
            $this->saveInvoice($invoice);

            $orderAttributes = $this->getOrderAttributes($vehicle->id, $customer, $invoice->id);

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
            ->when($this->vehicleForm->model, fn($q) => $q->where('model', $this->vehicleForm->model))
            ->when($this->vehicleForm->model_year, fn($q) => $q->where('model_year', $this->vehicleForm->model_year))
            ->when($this->factoryFitSearch, fn($q) => $q->where('option_name', 'like', '%' . $this->factoryFitSearch . '%'))
            ->paginate(5, ['*'], 'factory_page');
    }

    #[Computed]
    public function dealer_options(): _IH_FitOption_C|array|LengthAwarePaginator
    {
        return FitOption::latest()
            ->where('option_type', 'dealer')
            ->whereNull('archived_at')
            ->when($this->vehicleForm->model, fn($q) => $q->where('model', $this->vehicleForm->model))
            ->when($this->vehicleForm->dealer_id, fn($q) => $q->where('dealer_id', $this->vehicleForm->dealer_id))
            ->when($this->dealerFitSearch, fn($q) => $q->where('option_name', 'like', '%' . $this->dealerFitSearch . '%'))
            ->paginate(5, ['*'], 'dealer_options');
    }

    #[Computed]
    public function selectedFactoryFitOptions(): _IH_FitOption_C|array|Collection|FitOption|null
    {
        if (empty($this->vehicleForm->factoryFitOptions))
        {
            return collect();
        }

        return FitOption::find($this->vehicleForm->factoryFitOptions);
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

        $metallicPaintDiscount = DealerDiscount::where('model_id', $this->vehicleForm->model)
            ->where('dealer_id', $this->vehicleForm->dealer_id)
            ->value('paint_discount') ?? 0;

        $meta = VehicleMeta::whereRelation('vehicle_model', 'vehicle_model_id', $this->vehicleForm->model)
            ->get()
            ->groupBy('type');

        $options = [
            'metallic_paint_discount' => $metallicPaintDiscount,
            'customers' => $this->customer->getSearchResults(),
            'manufacturers' => Manufacturer::all()
                ->keyBy('id')
                ->sortby('name'),
            'vehicle_models' => VehicleModel::where(
                'manufacturer_id',
                $this->vehicleForm->make,
            )
                ->orderBy('name')
                ->get(),

            'types'         => $meta->get('type', collect()),
            'derivatives'   => $meta->get('derivative', collect()),
            'engines'       => $meta->get('engine', collect()),
            'transmissions' => $meta->get('transmission', collect()),
            'fuel_types'    => $meta->get('fuel', collect()),
            'colours'       => $meta->get('colour', collect()),
            'trims'         => $meta->get('trim', collect()),

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
            'finance_commission_invoice_number' =>
                $this->invoice_finance_number,
            'broker_invoice_number' => $this->invoice_broker_number,
            'broker_commission_invoice_number' =>
                $this->commission_broker_number,
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

        $this->cost->saveInvoiceCosts($invoice);
    }

    #[Computed]
    public function availableStatuses(): Collection
    {
        $statuses = VehicleStatus::cases();

        return collect($statuses)->filter(function($status) {
            if ($status === VehicleStatus::IN_STOCK_REGISTERED && !$this->vehicleForm->vehicle_registered_on) {
                return false;
            }

            if ($status === VehicleStatus::COMPLETED_ORDERS) {
                return $this->vehicleForm->vehicle_registered_on && $this->vehicleForm->vehicle_registered_on < now();
            }

            return true;
        });
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
        $this->resetPage('factory_page');
    }
    public function updatingDealerFitSearch(): void
    {
        $this->resetPage('dealer_page');
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
