<?php

namespace App\Http\Livewire\Vehicle;

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
use Illuminate\Routing\Redirector;
use Illuminate\Validation\ValidationException;
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
    public $order_ref;
    public $chassis_prefix;
    public $chassis;
    public $status;
    public $model_year;
    public $registered_date;
    public $due_date;
    public $build_date;
    public $ford_pipeline = '0';
    public $factoryFitOptions = []; // Vehicle -> JSON of IDs to fit_options
    public $dealerFitOptions = []; // Vehicle
    public $factoryFitOptionsArray = [];
    public $dealerFitOptionsArray = [];
    public $list_price;
    public $metallic_paint;
    public $dealer_discount;
    public $manufacturer_discount;
    public $manufacturer_delivery_cost;
    public $first_reg_fee;
    public $rfl_cost;
    public $onward_delivery;
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
        if (isset($this->vehicle)) {
            $this->make = $this->vehicle->make ?: null;
            $this->model = VehicleModel::where(
                'name',
                $this->vehicle->model,
            )->first()->id;

            $this->registered_date = $this->vehicle->vehicle_registered_on;
            $this->build_date = $this->vehicle->build_date;
            $this->due_date = $this->vehicle->due_date;

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
            $this->factoryFitOptionsArray = $this->vehicle->factoryFitOptions();
            $this->dealerFitOptionsArray = $this->vehicle->dealerFitOptions();
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
     * @return Application|RedirectResponse|Redirector
     */
    public function orderFormSubmit()
    {
        if (
            $this->due_date === '-0001-11-30' ||
            $this->due_date === '' ||
            $this->due_date === '0000-00-00'
        ) {
            $this->due_date = null;
        }

        $this->validate();

        if ($this->orbit_number === '') {
            $this->orbit_number = null;
        }

        if ($this->vehicle) {
            $vehicle = $this->vehicle;
            if (isset($this->orbit_number)) {
                $vehicle->orbit_number = $this->orbit_number;
            } elseif ($this->orbit_number === null) {
                $vehicle->orbit_number = null;
            }
        } elseif ($this->orbit_number === null) {
            $vehicle = Vehicle::create();
        } else {
            $vehicle = Vehicle::firstOrCreate([
                'orbit_number' => $this->orbit_number,
            ]);
        }

        $vehicle->update([
            'vehicle_status' => $this->status,
            'reg' => $this->registration,
            'ford_order_number' => $this->order_ref,
            'model_year' => $this->model_year,
            'make' => $this->make,
            'model' => VehicleModel::where('id', $this->model)->first()->name,
            'derivative' => $this->derivative,
            'engine' => $this->engine,
            'transmission' => $this->transmission,
            'fuel_type' => $this->fuel_type,
            'colour' => $this->colour,
            'chassis' => $this->chassis,
            'dealer_id' => $this->dealership,
            'broker_id' => $this->broker,
            'trim' => $this->trim,
            'chassis_prefix' => $this->chassis_prefix,
            'type' => $this->type,
            'metallic_paint' => $this->metallic_paint,
            'list_price' => $this->list_price,
            'first_reg_fee' => $this->first_reg_fee,
            'rfl_cost' => $this->rfl_cost,
            'onward_delivery' => $this->onward_delivery,
            'build_date' => $this->build_date,
            'due_date' => $this->due_date,
            'vehicle_registered_on' => $this->registered_date,
            'hide_from_broker' => $this->hide_from_broker,
            'hide_from_dealer' => $this->hide_from_dealer,
            'show_offer' => $this->show_offer,
            'show_discount' => $this->show_discount,
            'show_in_ford_pipeline' => $this->ford_pipeline,
        ]);

        $fitOptions = array_merge(
            $this->factoryFitOptions,
            $this->dealerFitOptions,
        );

        $vehicle->fitOptions()->sync($fitOptions);

        if ($this->vehicle) {
            notify()->success(
                'Vehicle was updated successfully',
                'Vehicle Updated',
            );
            $brokers = User::where('company_id', $this->broker)->get();
            $permission = Permission::where('name', 'receive-emails')->first();
            $mailBrokers = $permission?->users
                ->where('company_id', $this->broker)
                ->all();
            if ($vehicle->wasChanged('reg')) {
                foreach ($brokers as $broker) {
                    $broker->notify(
                        new RegistrationNumberAddedNotification($vehicle),
                    );
                }
                if ($mailBrokers) {
                    foreach ($mailBrokers as $broker) {
                        $broker->notify(
                            new RegistrationNumberAddedEmailNotification(
                                $vehicle,
                            ),
                        );
                    }
                }
            }
        } else {
            notify()->success(
                'Vehicle was created successfully',
                'Vehicle Created',
            );
        }

        $this->markOrderComplete($vehicle, $vehicle->order());

        OrderController::setProvisionalRegDate($vehicle);

        return redirect(route('vehicle.show', $vehicle->id));
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

    /**
     * Render the Vehicle Form
     * @return Factory|View|Application
     */
    public function render(): Factory|View|Application
    {
        $options = [
            'dealers' => Company::where('company_type', 'dealer')->get(),
            'brokers' => Company::where('company_type', 'broker')->get(),
            'manufacturers' => Manufacturer::all()
                ->keyBy('id')
                ->sortBy('name'),
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
            'factory_options' => FitOption::latest()
                ->where('option_type', 'factory')
                ->whereNull('archived_at')
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
                ->paginate(5, ['*'], 'factory_options'),
            'dealer_options' => FitOption::latest()
                ->where('option_type', 'dealer')
                ->whereNull('archived_at')
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
                ->paginate(5, ['*'], 'dealer_options'),
        ];
        return view('livewire.vehicle.vehicle-form', $options);
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
}
