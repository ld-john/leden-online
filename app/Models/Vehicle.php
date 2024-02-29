<?php

namespace App\Models;

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

    /**
     * @var mixed
     */
    protected $guarded = [];

    /**
     * @var mixed|string
     */
    protected $touches = ['order'];

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

    public function status(): string
    {
        return $this->statusMatch($this->vehicle_status);
    }

    public function websiteLocation(): array
    {
        $data = [
            'location' => 'Unknown',
            'route' => null,
            'status' => 'danger',
        ];

        if (!$this->order) {
            $data['status'] = 'warning';
            if ($this->ring_fenced_stock) {
                $data['location'] = 'Ring-fenced Stock';
                $data['route'] = 'ring_fenced_stock';
            } else {
                $data['location'] = 'Leden Stock';
                $data['route'] = 'pipeline';
            }
        } else {
            $data['status'] = 'primary';
            if (
                $this->vehicle_status === 1 ||
                $this->vehicle_status === 4 ||
                $this->vehicle_status === 10 ||
                $this->vehicle_status === 11 ||
                $this->vehicle_status === 12 ||
                $this->vehicle_status === 13 ||
                $this->vehicle_status === 14 ||
                $this->vehicle_status === 15 ||
                $this->vehicle_status === 16 ||
                $this->vehicle_status === 17 ||
                $this->vehicle_status === 18 ||
                $this->vehicle_status === 19
            ) {
                $data['location'] = 'Order Bank';
                $data['route'] = 'order_bank';
            } elseif ($this->vehicle_status === 7) {
                $data['location'] = 'Completed Orders';
                $data['route'] = 'completed_orders';
            } elseif (
                $this->vehicle_status === 3 ||
                $this->vehicle_status === 5 ||
                $this->vehicle_status === 6
            ) {
                $data['location'] = 'Manage Deliveries';
                $data['route'] = 'manage_deliveries';
            }
        }

        if ($this->reservation) {
            $data['location'] = 'Reservation';
            $data['route'] = 'reservation.index';
            $data['status'] = 'danger';
        }

        return $data;
    }

    public function simplified_type(): string
    {
        $type = $this->type;
        $type = explode(' ', $type);
        return $type[0];
    }

    public static function statusMatch($value): string
    {
        $statuses = Vehicle::statusList();

        return $statuses[$value] ?? 'Status Not Known';
    }

    public static function statusList(): array
    {
        return [
            1 => 'In Stock',
            3 => 'Ready for Delivery',
            4 => 'Factory Order',
            5 => 'Awaiting Delivery Confirmation',
            6 => 'Delivery Booked',
            7 => 'Completed Orders',
            10 => 'Europe VHC',
            12 => 'At Converter',
            13 => 'Awaiting Ship',
            11 => 'UK VHC',
            14 => 'Recall',
            15 => 'In Stock (Registered)',
            16 => 'Damaged/Recalled',
            17 => 'In Stock (Awaiting Dealer Options)',
            18 => 'Dealer Transfer',
            19 => 'Order In Query',
        ];
    }

    public function niceName(): string
    {
        return $this->manufacturer->name .
            ' ' .
            $this->model .
            ' ' .
            $this->derivative;
    }
}
