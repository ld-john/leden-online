<?php

namespace App\Http\Livewire\Vehicle;

use App\Enums\VehicleStatus;
use App\Http\Controllers\OrderController;
use App\Livewire\Forms\BondForm;
use App\Livewire\Forms\CostForm;
use App\Models\Company;
use App\Models\FitOption;
use App\Models\Manufacturer;
use App\Models\Permission;
use App\Models\TacticalOffer;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleMeta;
use App\Models\VehicleModel;
use App\Notifications\RegistrationNumberAddedEmailNotification;
use App\Notifications\RegistrationNumberAddedNotification;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use LaravelIdea\Helper\App\Models\_IH_FitOption_C;
use LaravelIdea\Helper\App\Models\_IH_TacticalOffer_C;
use LaravelIdea\Helper\App\Models\_IH_VehicleModel_C;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\Features\SupportRedirects\Redirector;
use Livewire\WithPagination;

class VehicleForm extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public \App\Livewire\Forms\VehicleForm $vehicleForm;
    public CostForm $cost;
    public BondForm $bond;

    public function getQueryString(): array
    {
        return [];
    }
    public $vehicle;
    public $tactical_offer;
    public $factoryFitSearch;
    public $dealerFitSearch;
    public $successMsg = '';
    public $now = '';

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

        $this->vehicleForm->setVehicle($this->vehicle);

        $this->bond->setBond($this->vehicle->bond);

        $this->cost->setCosts($this->vehicle);

        $this->tactical_offer = $this->vehicle->tactical_offer;
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
     */
    public function orderFormSubmit(): RedirectResponse|Redirector
    {
        $isExistingVehicle = (bool) $this->vehicle;

        $bond = $this->bond->save();

        $vehicle = $this->vehicleForm->save($bond);

        $this->cost->saveCosts($vehicle);

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
            if ($status === VehicleStatus::IN_STOCK_REGISTERED && !$this->vehicleForm->vehicle_registered_on) {
                return false;
            }

            if ($status === VehicleStatus::COMPLETED_ORDERS) {
                return $this->vehicleForm->vehicle_registered_on && $this->vehicleForm->vehicle_registered_on < now();
            }

            return true;
        });
    }

    #[Computed]
    public function modelMeta(): Collection
    {
        if (!$this->vehicleForm->model) {
            return collect();
        }

        return VehicleMeta::whereRelation('vehicle_model', 'vehicle_model_id', $this->vehicleForm->model)->get();
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
        return $this->vehicleForm->make
            ? VehicleModel::where('manufacturer_id', $this->vehicleForm->make)->orderBy('name')->get()
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
            ->when($this->vehicleForm->model, fn($q) => $q->where('model', $this->vehicleForm->model))
            ->when($this->vehicleForm->model_year, fn($q) => $q->where('model_year', $this->vehicleForm->model_year))
            ->when($this->factoryFitSearch, fn($q) => $q->where('option_name', 'like', '%' . $this->factoryFitSearch . '%'))
            ->paginate(5, ['*'], 'factory_page');
    }

    /**
     * Get offers specifically for this vehicle.
     * We use a Computed property so it refreshes if the vehicle changes.
     */
    #[Computed]
    public function availableOffers(): _IH_TacticalOffer_C|array|Collection
    {
        if (!$this->vehicleForm->make) {
            return collect();
        }

        return TacticalOffer::query()
            ->active()
            ->where('manufacturer_id', $this->vehicleForm->make)
            ->where(function ($query) {
                $query->where('applicable_model', $this->vehicleForm->model)
                    ->orWhereNull('applicable_model');
            })
            ->get();
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
