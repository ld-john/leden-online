<?php

namespace App\Http\Livewire\Vehicle;

use App\Models\Company;
use App\Models\FitOption;
use App\Models\Manufacturer;
use App\Models\Vehicle;
use App\Models\VehicleMeta;
use App\Models\VehicleModel;
use DateTime;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
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
     * @return void
     *@throws Exception
     */

    public function mount(): void
    {
        if (isset($this->vehicle)) {
            $this->make = $this->vehicle->make ?: null;
            $this->model = VehicleModel::where(
                'name',
                $this->vehicle->model,
            )->first()->id;

            if ($this->vehicle->vehicle_registered_on) {
                $reg = new DateTime($this->vehicle->vehicle_registered_on);
                $this->registered_date = $reg->format('Y-m-d');
            }
            if ($this->vehicle->build_date) {
                $bd = new DateTime($this->vehicle->build_date);
                $this->build_date = $bd->format('Y-m-d');
            }
            if ($this->vehicle->due_date) {
                $dd = new DateTime($this->vehicle->due_date);
                $this->due_date = $dd->format('Y-m-d');
            }

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
     * @throws ValidationException
     */
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function orderFormSubmit()
    {
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
        } elseif (!isset($this->orbit_number) || $this->orbit_number === '') {
            $vehicle = new Vehicle();
        } else {
            $vehicle = Vehicle::firstOrNew([
                'orbit_number' => $this->orbit_number,
            ]);
        }

        $vehicle->vehicle_status = $this->status;
        $vehicle->reg = $this->registration;
        $vehicle->ford_order_number = $this->order_ref;
        $vehicle->model_year = $this->model_year;
        $vehicle->make = $this->make;
        $vehicle->model = VehicleModel::where(
            'id',
            $this->model,
        )->first()->name;
        $vehicle->derivative = $this->derivative;
        $vehicle->engine = $this->engine;
        $vehicle->transmission = $this->transmission;
        $vehicle->fuel_type = $this->fuel_type;
        $vehicle->colour = $this->colour;
        $vehicle->chassis = $this->chassis;
        $vehicle->dealer_id = $this->dealership;
        $vehicle->broker_id = $this->broker;
        $vehicle->trim = $this->trim;
        $vehicle->chassis_prefix = $this->chassis_prefix;
        $vehicle->type = $this->type;
        $vehicle->metallic_paint = $this->metallic_paint;
        $vehicle->list_price = $this->list_price;
        $vehicle->first_reg_fee = $this->first_reg_fee;
        $vehicle->rfl_cost = $this->rfl_cost;
        $vehicle->onward_delivery = $this->onward_delivery;
        $vehicle->build_date = $this->build_date;
        $vehicle->due_date = $this->due_date;
        $vehicle->vehicle_registered_on = $this->registered_date;
        $vehicle->hide_from_broker = $this->hide_from_broker;
        $vehicle->hide_from_dealer = $this->hide_from_dealer;
        $vehicle->show_offer = $this->show_offer;
        $vehicle->show_discount = $this->show_discount;
        $vehicle->show_in_ford_pipeline = $this->ford_pipeline;
        $vehicle->save();

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
        } else {
            notify()->success(
                'Vehicle was created successfully',
                'Vehicle Created',
            );
        }

        $this->markOrderComplete($vehicle, $vehicle->order());

        return redirect(route('vehicle.show', $vehicle->id));
    }

    private function markOrderComplete($vehicle, $order)
    {
        if (
            $vehicle->vehicle_status === '7' &&
            $order->completed_date === null
        ) {
            $order->update(['completed_date' => now()]);
        }
    }

    public function render(): Factory|View|Application
    {
        $options = [
            'dealers' => Company::where('company_type', 'dealer')->get(),
            'brokers' => Company::where('company_type', 'broker')->get(),
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
        return view('livewire.vehicle.vehicle-form', $options);
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
}
