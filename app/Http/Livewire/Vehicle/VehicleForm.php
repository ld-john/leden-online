<?php

namespace App\Http\Livewire\Vehicle;

use App\Company;
use App\FitOption;
use App\Manufacturer;
use App\Vehicle;
use App\VehicleMeta\Colour;
use App\VehicleMeta\Derivative;
use App\VehicleMeta\Engine;
use App\VehicleMeta\Fuel;
use App\VehicleMeta\Transmission;
use App\VehicleMeta\Trim;
use App\VehicleMeta\Type;
use DateTime;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
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

    public bool $trimInput = true;
    public bool $colourInput = true;
    public bool $fuelInput = true;
    public bool $transmissionInput = true;
    public bool $engineInput = true;
    public bool $derivativeInput = true;
    public bool $modelInput = true;
    public bool $makeInput = true;

    public $vehicle;

    public $make;
    public $newmake;
    public $orbit_number;
    public $model;
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
    public $broker;
    public $factoryFitSearch;
    public $dealerFitSearch;
    public $successMsg = '';
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
        'registered_date' => 'nullable|date',
    ];

    protected $messages = [
        'make.required_without' => 'No <strong>Make</strong> selected',
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
    ];
    /**
     * @var mixed
     */

    public function mount()
    {
        if (isset($this->vehicle)) {
            $this->make = $this->vehicle->make ?: null;
            $this->model = $this->vehicle->make ? $this->vehicle->model : null;

            if ($this->vehicle->vehicle_registered_on) {
                $reg = new DateTime($this->vehicle->vehicle_registered_on);
                $this->registered_date = $reg->format('Y-m-d');
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
        $vehicle->model = $this->model;
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
            $this->successMsg = 'Vehicle Edited';
        } else {
            $this->successMsg = 'Vehicle Created';
        }

        $this->markOrderComplete($vehicle, $vehicle->order());
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
        $fitoptions = FitOption::latest()->get();

        $options = [
            'dealers' => Company::where('company_type', 'dealer')->get(),
            'manufacturers' => Manufacturer::all()->keyBy('id'),
            'types' => Type::all(),
            'derivatives' => Derivative::all(),
            'engines' => Engine::all(),
            'transmissions' => Transmission::all(),
            'fuel_types' => Fuel::all(),
            'colours' => Colour::all(),
            'trims' => Trim::all(),
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
