<?php

namespace App;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Maatwebsite\Excel\Excel;
use PhpOffice\PhpSpreadsheet\Writer\Exception;

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
 */
class Vehicle extends Model
{
    use HasFactory;
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

    public static function statusMatch($value): string
    {
        return match ($value) {
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
            default => 'Not Known',
        };
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
