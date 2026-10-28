<?php

namespace App\Livewire\Forms;

use App\Enums\VehicleStatus;
use App\Models\Bond;
use App\Models\Permission;
use App\Models\TacticalOffer;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleModel;
use App\Notifications\RegistrationNumberAddedEmailNotification;
use App\Notifications\RegistrationNumberAddedNotification;
use App\Notifications\VehicleInStockEmailNotification;
use App\Notifications\VehicleInStockNotification;
use ErrorException;
use Illuminate\Support\Collection;
use Livewire\Attributes\Validate;
use Livewire\Form;

class VehicleForm extends Form
{
    public ?Vehicle $vehicle;

    public CostForm $cost;

    #[Validate(['required'])]
    public ?VehicleStatus $vehicle_status = null;

    #[Validate(['nullable'])]
    public $reg = '';

    #[Validate(['required', 'boolean'])]
    public $show_in_ford_pipeline = false;

    #[Validate(['required'])]
    public $type = '';

    #[Validate(['nullable', 'date'])]
    public $due_date = '';

    #[Validate(['nullable'])]
    public $chassis_prefix = '';

    #[Validate(['required'])]
    public $trim = '';

    #[Validate(['required'])]
    public $colour = '';

    #[Validate(['required'])]
    public $fuel_type = '';

    #[Validate(['required'])]
    public $transmission = '';

    #[Validate(['required'])]
    public $engine = '';

    #[Validate(['required'])]
    public $derivative = '';

    #[Validate(['nullable'])]
    public $chassis = '';

    #[Validate(['required'])]
    public $model = '';

    #[Validate(['required'])]
    public $make = '';

    #[Validate(['required'])]
    public $ford_order_number = '';

    #[Validate(['nullable'])]
    public $model_year = '';

    #[Validate(['nullable', 'date'])]
    public $vehicle_registered_on = '';

    #[Validate(['required'])]
    public $broker_id = '';

    #[Validate(['required'])]
    public $dealer_id = '';

    #[Validate(['nullable'])]
    public $orbit_number = '';

    #[Validate(['nullable', 'date'])]
    public $build_date = '';

    #[Validate(['nullable'])]
    public $compound = '';

    #[Validate(['nullable', 'date'])]
    public $contract_date = '';

    #[Validate(['nullable'])]
    public $recall_reason = '';

    public array $factoryFitOptions = []; // Vehicle -> JSON of IDs to fit_options
    public array $dealerFitOptions = []; // Vehicle
    public array|Collection $factoryFitOptionsArray = [];
    public array|Collection $dealerFitOptionsArray = [];
    public ?array $selectedOffers = [];

    public function setVehicle(?Vehicle $vehicle): void
    {
        if (!$vehicle) {
            return;
        }

        $this->vehicle = $vehicle;

        $this->make = $vehicle->make;
        $this->model = VehicleModel::where(
            'name',
            $vehicle->model,
        )->first()?->id;
        $this->orbit_number = $vehicle->orbit_number;
        $this->type = $vehicle->type;
        $this->reg = $vehicle->reg;
        $this->derivative = $vehicle->derivative;
        $this->engine = $vehicle->engine;
        $this->transmission = $vehicle->transmission;
        $this->fuel_type = $vehicle->fuel_type;
        $this->colour = $vehicle->colour;
        $this->trim = $vehicle->trim;
        $this->chassis_prefix = $vehicle->chassis_prefix;
        $this->chassis = $vehicle->chassis;
        $this->vehicle_status = $vehicle->vehicle_status;
        $this->recall_reason = $vehicle->recall_reason;
        $this->due_date = $vehicle->due_date?->format('Y-m-d') ?? null;
        $this->build_date = $vehicle->build_date?->format('Y-m-d') ?? null;
        $this->contract_date = $vehicle->contract_date?->format('Y-m-d') ?? null;
        $this->model_year = $vehicle->model_year;
        $this->vehicle_registered_on = $vehicle->vehicle_registered_on?->format('Y-m-d') ?? null;
        $this->show_in_ford_pipeline = $vehicle->show_in_ford_pipeline;
        $this->ford_order_number = $vehicle->ford_order_number;
        $this->broker_id = $vehicle->broker_id;
        $this->dealer_id = $vehicle->dealer_id;
        $this->compound = $vehicle->compound;
        $this->factoryFitOptions = $vehicle
            ->factoryFitOptions()
            ->pluck('id')
            ->toArray();
        $this->dealerFitOptions = $vehicle
            ->dealerFitOptions()
            ->pluck('id')
            ->toArray();
        $this->factoryFitOptionsArray = $vehicle->factoryFitOptions();
        $this->dealerFitOptionsArray = $vehicle->dealerFitOptions();
        $this->selectedOffers = $vehicle->tacticalOffers()->pluck('tactical_offers.id')->toArray();

    }

    /**
     * @throws ErrorException
     */
    public function createOrFindVehicle() : Vehicle
    {

        if (isset($this->vehicle)) {
            $this->vehicle->update([
                'orbit_number' => $this->orbit_number
            ]);
        } elseif (empty($this->orbit_number)) {
            $this->vehicle->create();
        } else {
            $this->vehicle->firstOrCreate(['orbit_number' => $this->orbit_number]);
            if ($this->vehicle->order->exists()) {
                throw new ErrorException('Vehicle is already assigned to an existing order.');
            }
        }

        return $this->vehicle;
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

    public function save(?Bond $bond) : ?Vehicle
    {
        $this->due_date = $this->sanitiseDate($this->due_date);
        $this->build_date = $this->sanitiseDate($this->build_date);
        $this->contract_date = $this->sanitiseDate($this->contract_date);
        $this->vehicle_registered_on = $this->sanitiseDate($this->vehicle_registered_on);
        $this->validate();

        $this->vehicle->update(
            [
                'make' => $this->make,
                'model' => VehicleModel::where('id', $this->model)->first()->name,
                'type' => $this->type,
                'reg' => $this->reg,
                'derivative' => $this->derivative,
                'engine' => $this->engine,
                'transmission' => $this->transmission,
                'fuel_type' => $this->fuel_type,
                'colour' => $this->colour,
                'trim' => $this->trim,
                'chassis_prefix' => $this->chassis_prefix,
                'chassis' => $this->chassis,
                'vehicle_status' => $this->vehicle_status,
                'recall_reason' => $this->recall_reason,
                'build_date' => $this->build_date,
                'due_date' => $this->due_date,
                'contract_date' => $this->contract_date,
                'model_year' => $this->model_year,
                'vehicle_registered_on' => $this->vehicle_registered_on,
                'show_in_ford_pipeline' => $this->show_in_ford_pipeline,
                'ford_order_number' => $this->ford_order_number,
                'broker_id' => $this->broker_id,
                'dealer_id' => $this->dealer_id
            ]
        );

        if ($this->orbit_number !== $this->vehicle->orbit_number) {
            $this->vehicle->update([
                'orbit_number' => $this->orbit_number,
            ]);
        }

        if ($bond) {
            $this->vehicle->update([
                'bond_id' => $bond->id,
            ]);
        }

        $brokers = User::where('company_id', $this->broker_id)->get();
        $permission = Permission::where('name', 'receive-emails')->first();

        $mailBrokers = $permission?->users
            ->where('company_id', $this->broker_id)
            ->all();

        if ($this->vehicle->wasChanged('vehicle_status')) {
            if ($this->vehicle->vehicle_status == VehicleStatus::COMPLETED_ORDERS) {
                $this->order->update(['completed_date' => now()]);
            } elseif ( $this->vehicle->vehicle_status?->isInStock()) {
                foreach ($brokers as $broker) {
                    $broker->notify(new VehicleInStockNotification($this->vehicle));
                }
                if ($mailBrokers) {
                    foreach ($mailBrokers as $broker) {
                        $broker->notify(
                            new VehicleInStockEmailNotification($this->vehicle),
                        );
                    }
                }

                $this->vehicle->update([
                    'due_date' => null,
                ]);
            }
        }

        if ($this->vehicle->wasChanged('reg')) {
            foreach ($brokers as $broker) {
                $broker->notify(
                    new RegistrationNumberAddedNotification($this->vehicle),
                );
            }
            if ($mailBrokers) {
                foreach ($mailBrokers as $broker) {
                    $broker->notify(
                        new RegistrationNumberAddedEmailNotification($this->vehicle),
                    );
                }
            }
        }

        $actualOffers = array_filter($this->selectedOffers, fn ($id) => $id !== 'none');

        $pivotData = [];

        if (!empty($actualOffers)) {
            $offers = TacticalOffer::whereIn('id', $actualOffers)
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

        $this->vehicle->tacticalOffers()->sync($pivotData);

        $fitOptions = array_merge($this->factoryFitOptions, $this->dealerFitOptions);

        $this->vehicle->fitOptions()->sync($fitOptions);

        return $this->vehicle;
    }
}
