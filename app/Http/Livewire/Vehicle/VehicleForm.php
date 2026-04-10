<?php

namespace App\Http\Livewire\Vehicle;

use App\Enums\VehicleStatus;
use App\Http\Controllers\OrderController;
use App\Models\Company;
use App\Models\FitOption;
use App\Models\Manufacturer;
use App\Models\Permission;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleMeta;
use App\Models\VehicleModel;
use App\Notifications\RegistrationNumberAddedEmailNotification;
use App\Notifications\RegistrationNumberAddedNotification;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use LaravelIdea\Helper\App\Models\_IH_FitOption_C;
use LaravelIdea\Helper\App\Models\_IH_VehicleModel_C;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class VehicleForm extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public function getQueryString(): array
    {
        return [];
    }
    public $vehicle;
    public $make;
    public $orbit_number;
    public $model;
    public $broker;
    public $dealership;
    public $type;
    public $registration;
    public $derivative;
    public $engine;
    public $transmission;
    public $fuel_type;
    public $colour;
    public $trim;
    public $compound;
    public $order_ref;
    public $chassis_prefix;
    public $chassis;
    public $status;
    public $model_year;
    public $registered_date;
    public $due_date;
    public $contract_date;
    public $build_date;
    public $ford_pipeline = '0';
    public $factoryFitOptions = []; // Vehicle -> JSON of IDs to fit_options
    public $dealerFitOptions = []; // Vehicle
    public $list_price;
    public $metallic_paint;
    public $dealer_discount;
    public $manufacturer_discount;
    public $manufacturer_delivery_cost;
    public $first_reg_fee;
    public $rfl_cost;
    public $onward_delivery;
    public $tactical_offer;
    public $show_discount = '0'; // Vehicle
    public $show_offer = '0'; // Vehicle
    public $hide_from_broker = '0';
    public $hide_from_dealer = '0';
    public $factoryFitSearch;
    public $dealerFitSearch;
    public $successMsg = '';
    public $now = '';
    protected $rules = [
        'make' => 'required',
        'model' => 'required',
        'type' => 'required',
        'derivative' => 'required',
        'engine' => 'required',
        'transmission' => 'required',
        'fuel_type' => 'required',
        'colour' => 'required',
        'trim' => 'required',
        'status' => 'required',
        'registered_date' => 'nullable|date',
        'build_date' => 'nullable|date',
        'contract_date' => 'nullable|date',
        'due_date' => 'nullable|date',
        'dealership' => 'required',
    ];

    protected $messages = [
        'make.required' => 'No <strong>Make</strong> selected',
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
        'dealership.required' => 'No <strong>Dealership</strong> selected',
    ];

    /**
     * Prepare the details of the vehicle if a vehicle model is handed to the form
     * @return void
     *@throws Exception
     */

    public function mount(): void
    {
        $this->now = date('Y-m-d');

        if (!isset($this->vehicle)) {
            return;
        }

        $this->make = $this->vehicle->make ?: null;
        $this->model = VehicleModel::where(
            'name',
            $this->vehicle->model,
        )->first()->id;

        $this->registered_date = $this->vehicle->vehicle_registered_on;
        $this->build_date = $this->vehicle->build_date;
        $this->due_date = $this->vehicle->due_date;
        $this->contract_date = $this->vehicle->contract_date;
        $this->type = $this->vehicle->type;
        $this->orbit_number = $this->vehicle->orbit_number;
        $this->order_ref = $this->vehicle->ford_order_number;
        $this->broker = $this->vehicle->broker_id;
        $this->dealership = $this->vehicle->dealer_id;
        $this->registration = $this->vehicle->reg;
        $this->derivative = $this->vehicle->derivative;
        $this->engine = $this->vehicle->engine;
        $this->transmission = $this->vehicle->transmission;
        $this->fuel_type = $this->vehicle->fuel_type;
        $this->colour = $this->vehicle->colour;
        $this->trim = $this->vehicle->trim;
        $this->compound = $this->vehicle->compound;
        $this->chassis_prefix = $this->vehicle->chassis_prefix;
        $this->chassis = $this->vehicle->chassis;
        $this->status = $this->vehicle->vehicle_status;
        $this->model_year = $this->vehicle->model_year;
        $this->ford_pipeline = $this->vehicle->show_in_ford_pipeline;
        $this->factoryFitOptions = $this->vehicle
            ->factoryFitOptions()
            ->pluck('id')
            ->toArray();
        $this->dealerFitOptions = $this->vehicle
            ->dealerFitOptions()
            ->pluck('id')
            ->toArray();
        $this->list_price = $this->vehicle->list_price;
        $this->metallic_paint = $this->vehicle->metallic_paint;
        $this->first_reg_fee = $this->vehicle->first_reg_fee;
        $this->rfl_cost = $this->vehicle->rfl_cost;
        $this->onward_delivery = $this->vehicle->onward_delivery;
        $this->tactical_offer = $this->vehicle->tactical_offer;
        $this->show_discount = $this->vehicle->show_discount;
        $this->show_offer = $this->vehicle->show_offer;
        $this->hide_from_broker = $this->vehicle->hide_from_broker;
        $this->hide_from_dealer = $this->vehicle->hide_from_dealer;

    }

    /**
     * Validate each property as the details are changed
     * @throws ValidationException
     */
    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName);
    }

    /**
     * Save the vehicle when the form is submitted
     * @return RedirectResponse
     */
    public function orderFormSubmit(): RedirectResponse
    {
        $this->sanitizeDates();
        $this->orbit_number = $this->orbit_number === '' ? null : $this->orbit_number;

        $this->validate();

        $vehicle = $this->resolveVehicle();

        $isExistingVehicle = (bool) $this->vehicle;

        $vehicle->fill($this->getVehicleAttributes());
        $vehicle->orbit_number = $this->orbit_number;
        $vehicle->save();

        $vehicle->fitOptions()->sync(
            array_merge($this->factoryFitOptions, $this->dealerFitOptions)
        );

        if ($isExistingVehicle) {
            notify()->success('Vehicle was updated successfully', 'Vehicle Updated');
            $this->handleRegistrationNotifications($vehicle);
        } else {
            notify()->success('Vehicle was created successfully', 'Vehicle Created');
        }

        $this->markOrderComplete($vehicle, $vehicle->order());
        OrderController::setProvisionalRegDate($vehicle);

        return redirect()->route('vehicle.show', $vehicle->id);
    }

    /**
     * If the order has been completed, add the completed date
     * @param $vehicle
     * @param $order
     * @return void
     */
    private function markOrderComplete($vehicle, $order): void
    {
        if (
            $vehicle->vehicle_status === '7' &&
            $order->completed_date === null
        ) {
            $order->update(['completed_date' => now()]);
        }
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

    #[Computed]
    public function modelMeta(): Collection
    {
        if (!$this->model) {
            return collect();
        }

        return VehicleMeta::whereRelation('vehicle_model', 'vehicle_model_id', $this->model)->get();
    }

    #[Computed]
    public function dealers(): Collection
    {
        return Company::where('company_type', 'dealer')->get();
    }

    #[Computed]
    public function brokers(): Collection
    {
        return Company::where('company_type', 'broker')->get();
    }

    #[Computed]
    public function vehicleModels(): _IH_VehicleModel_C|array|Collection
    {
        return $this->make
            ? VehicleModel::where('manufacturer_id', $this->make)->orderBy('name')->get()
            : collect();
    }

    #[Computed]
    public function vehicleTypes()
    {
        return $this->modelMeta->where('type', 'type');
    }

    #[Computed]
    public function derivatives()
    {
        return $this->modelMeta->where('type', 'derivative');
    }

    #[Computed]
    public function engines()
    {
        return $this->modelMeta->where('type', 'engine');
    }

    #[Computed]
    public function vehicleTransmission()
    {
        return $this->modelMeta->where('type', 'transmission');
    }

    #[Computed]
    public function fuel_types()
    {
        return $this->modelMeta->where('type', 'fuel');
    }

    #[Computed]
    public function colours()
    {
        return $this->modelMeta->where('type', 'colour');
    }

    #[Computed]
    public function compounds()
    {
        return $this->modelMeta->where('type', 'compounds');

    }

    #[Computed]
    public function trims()
    {
        return $this->modelMeta->where('type', 'trim');
    }

    #[Computed]
    public function manufacturers(): Collection
    {
        return Manufacturer::orderBy('name')->get()->keyBy('id');
    }

    #[Computed]
    public function factory_options(): _IH_FitOption_C|array|LengthAwarePaginator
    {
        return FitOption::latest()
            ->where('option_type', 'factory')
            ->whereNull('archived_at')
            ->when($this->model, fn($q) => $q->where('model', $this->model))
            ->when($this->model_year, fn($q) => $q->where('model_year'), $this->model_year)
            ->where($this->factoryFitSearch, fn($q) => $q->where('option_name', 'like', '%' . $this->factoryFitSearch . '%'))
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
     * Render the Vehicle Form
     * @return Factory|View|Application
     */
    public function render(): Factory|View|Application
    {
        return view('livewire.vehicle.vehicle-form');
    }

    public function updatingFactoryFitSearch(): void
    {
        $this->resetPage('factory_options');
    }
    public function updatingDealerFitOptions(): void
    {
        $this->resetPage('dealer_options');
    }

    /**
     * When the Factory Fit options are updated, update the array with the details of the Fit Option
     * @return void
     */
    public function updatedFactoryFitOptions(): void
    {
        $this->factoryFitOptionsArray = FitOption::find(
            $this->factoryFitOptions,
        );
    }

    /**
     * When the Dealer Fit options are updated, update the array with the details of the Fit Option
     * @return void
     */
    public function updatedDealerFitOptions(): void
    {
        $this->dealerFitOptionsArray = FitOption::find($this->dealerFitOptions);
    }

    /** New Helper Methods */

    /**
     * Handle the logic for finding or creating the vehicle instance.
     */

    protected function resolveVehicle(): Vehicle
    {
        if ($this->vehicle) {
            return $this->vehicle;
        }

        if ($this->orbit_number) {
            return Vehicle::firstOrCreate(['orbit_number' => $this->orbit_number]);
        }

        return Vehicle::create();
    }

    /**
     * Consolidate the large data mapping array here.
     */
    protected function getVehicleAttributes(): array
    {
        return [
            'vehicle_status'        => $this->status,
            'reg'                   => $this->registration,
            'ford_order_number'     => $this->order_ref,
            'model_year'            => $this->model_year,
            'make'                  => $this->make,
            'model'                 => VehicleModel::find($this->model)?->name, // Safer query
            'derivative'            => $this->derivative,
            'engine'                => $this->engine,
            'transmission'          => $this->transmission,
            'fuel_type'             => $this->fuel_type,
            'colour'                => $this->colour,
            'chassis'               => $this->chassis,
            'dealer_id'             => $this->dealership,
            'broker_id'             => $this->broker,
            'trim'                  => $this->trim,
            'compound'              => $this->compound,
            'chassis_prefix'        => $this->chassis_prefix,
            'type'                  => $this->type,
            'metallic_paint'        => $this->metallic_paint,
            'list_price'            => $this->list_price,
            'first_reg_fee'         => $this->first_reg_fee,
            'rfl_cost'              => $this->rfl_cost,
            'onward_delivery'       => $this->onward_delivery,
            'tactical_offer'        => $this->tactical_offer,
            'build_date'            => $this->build_date,
            'due_date'              => $this->due_date,
            'contract_date'         => $this->contract_date,
            'vehicle_registered_on' => $this->registered_date,
            'hide_from_broker'      => $this->hide_from_broker,
            'hide_from_dealer'      => $this->hide_from_dealer,
            'show_offer'            => $this->show_offer,
            'show_discount'         => $this->show_discount,
            'show_in_ford_pipeline' => $this->ford_pipeline,
        ];
    }
    /**
     * Fix bad date formats.
     */
    protected function sanitizeDates(): void
    {
        $badDates = ['-0001-11-30', '0000-00-00', ''];

        if (in_array($this->due_date, $badDates)) {
            $this->due_date = null;
        }
        if (in_array($this->contract_date, $badDates)) {
            $this->contract_date = null;
        }

    }

    /**
     * Handle the complex notification logic separately.
     */
    protected function handleRegistrationNotifications($vehicle): void
    {
        if (!$vehicle->wasChanged('reg')) {
            return;
        }

        $brokers = User::where('company_id', $this->broker)->get();

        foreach ($brokers as $broker) {
            $broker->notify(new RegistrationNumberAddedNotification($vehicle));
        }

        $permission = Permission::where('name', 'receive-emails')->first();
        $mailBrokers = $permission?->users
            ->where('company_id', $this->broker) ?? collect();

        foreach ($mailBrokers as $broker) {
            $broker->notify(new RegistrationNumberAddedEmailNotification($vehicle));
        }

    }
}
