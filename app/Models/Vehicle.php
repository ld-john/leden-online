<?php

namespace App\Models;

use App\Enums\VehicleStatus;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property mixed $vehicle_status
 * @property mixed $reg
 * @property mixed|string $show_in_ford_pipeline
 * @property mixed|string $hide_from_dealer
 * @property mixed|string $hide_from_broker
 * @property mixed $onward_delivery
 * @property mixed $rfl_cost
 * @property mixed $first_reg_fee
 * @property mixed $list_price
 * @property mixed $metallic_paint
 * @property mixed $type
 * @property mixed $due_date
 * @property mixed $chassis_prefix
 * @property false|mixed|string|null $factory_fit_options
 * @property false|mixed|string|null $dealer_fit_options
 * @property mixed $trim
 * @property mixed $colour
 * @property mixed $fuel_type
 * @property mixed $transmission
 * @property mixed $engine
 * @property mixed $derivative
 * @property mixed $chassis
 * @property mixed $model
 * @property mixed $make
 * @property mixed $ford_order_number
 * @property mixed $model_year
 * @property mixed $vehicle_registered_on
 * @property mixed $broker_id
 * @property mixed $dealer_id
 * @property mixed $manufacturer
 * @property mixed $id
 * @property mixed $order
 * @property mixed $orbit_number
 */
class Vehicle extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'vehicle_status',
        'reg',
        'ford_order_number',
        'model_year',
        'make',
        'model',
        'derivative',
        'engine',
        'transmission',
        'fuel_type',
        'show_in_ford_pipeline',
        'show_discount',
        'show_offer',
        'hide_from_dealer',
        'hide_from_broker',
        'vehicle_registered_on',
        'contract_date',
        'due_date',
        'build_date',
        'tactical_offer',
        'onward_delivery',
        'rfl_cost',
        'first_reg_fee',
        'list_price',
        'metallic_paint',
        'type',
        'chassis_prefix',
        'compound',
        'trim',
        'broker_id',
        'dealer_id',
        'chassis',
        'colour',
        'ring_fenced_stock',
        'orbit_number',
        'factory_fit_options',
        'dealer_fit_options',
        'manufacturer',
        'order',
        'doors',
        'vehicle_reg_date',
        'recall_reason'
    ];

    /**
     * @var mixed|string
     */
    protected $touches = ['order'];

    protected $casts = [
        'vehicle_status' => VehicleStatus::class,
        'build_date' => 'datetime',
        'in_stock_at' => 'datetime',
        'due_date' => 'date',
        'contract_date' => 'date'
    ];

    public function order(): HasOne
    {
        return $this->hasOne(Order::class, 'vehicle_id', 'id');
    }

    public function reservation(): HasOne
    {
        return $this->hasOne(Reservation::class, 'vehicle_id', 'id');
    }

    public function manufacturer(): HasOne
    {
        return $this->hasOne(Manufacturer::class, 'id', 'make');
    }

    public function dealer(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'dealer_id', 'id');
    }

    public function broker(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'broker_id', 'id');
    }

    public function fitOptions(): BelongsToMany
    {
        return $this->belongsToMany(
            FitOption::class,
            'fit_options_vehicle',
            'vehicle_id',
            'option_id',
        );
    }

    public function factoryFitOptions(): Collection
    {
        return $this->fitOptions()
            ->where('option_type', '=', 'factory')
            ->get();
    }

    public function dealerFitOptions(): Collection
    {
        return $this->fitOptions()
            ->where('option_type', '=', 'dealer')
            ->get();
    }

    public function websiteLocation(): array
    {
        $data = [
            'location' => 'Unknown',
            'route' => null,
            'status' => 'danger',
        ];

        if ($this->reservation) {
            return [
                'location' => 'Reservation',
                'route' => 'reservation.index',
                'status' => 'danager'
            ];
        }

        if (!$this->order)
        {
            return [
                'location' => $this->ring_fenced_stock ? 'Ring-fenced Stock' : 'Leden Stock',
                'route' => $this->ring_fenced_stock ? 'ring_fenced_stock' : 'pipeline',
                'status' => 'warning'
            ];
        }

        $data['status'] = 'primary';

        $status = $this->vehicle_status;

        if ($status?->isOrderBank()) {
            $data['location'] = 'Order Bank';
            $data['route'] = 'order_bank';
        } elseif ($status === VehicleStatus::COMPLETED_ORDERS) {
            $data['location'] = 'Completed Orders';
            $data['route'] = 'completed_orders';
        } elseif($status?->isDeliveryManaged()) {
            $data['location'] = 'Manage Deliveries';
            $data['route'] = 'manage_deliveries';
        }

        return $data;
    }

    public function simplified_type(): string
    {
        $type = $this->type;
        $type = explode(' ', $type);
        return $type[0];
    }

    public function niceName(): Attribute
    {
        return Attribute::make(
            get: fn () => "{$this->manufacturer->name} {$this->model} {$this->derivative}",
        );
    }

    public function getAvailableOffersAttribute(): Collection
    {
        return TacticalOffer::active()
            ->where('manufacturer_id', $this->make)
            ->where(function ($query) {
                $query->whereNull('applicable_model')
                    ->orWhere('applicable_model', $this->model);
            })
            ->get();
    }

    public function tacticalOffers(): BelongsToMany
    {
        return $this->belongsToMany(TacticalOffer::class, 'tactical_offer_vehicle')
            ->withPivot('order_id', 'applied_amount')
            ->withTimestamps();
    }

    protected static function booted(): void
    {
        static::saving(function (Vehicle $vehicle) {
            if ($vehicle->isDirty('vehicle_status')) {
                if ($vehicle->vehicle_status?->isInStock()) {
                    if (is_null($vehicle->in_stock_at)) {
                        $vehicle->in_stock_at = now();
                    }
                } else {
                    $vehicle->in_stock_at = null;
                }
            }
        });
    }

    protected function timeInStock(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->in_stock_at?->diffForHumans(syntax: CarbonInterface::DIFF_ABSOLUTE)
        );
    }
}
