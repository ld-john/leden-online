<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TacticalOffer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'value',
        'start_date',
        'end_date',
        'manufacturer_id',
        'applicable_model'
    ];

    public function manufacturer(): BelongsTo
    {
        return $this->belongsTo(Manufacturer::class);
    }

    public function vehicles(): BelongsToMany
    {
        return $this->belongsToMany(Vehicle::class, 'tactical_offer_vehicle')
            ->withPivot('order_id', 'applied_amount')
            ->withTimestamps();

    }

    public function applicableModel(): BelongsTo
    {
        return $this->belongsTo(VehicleModel::class, 'applicable_model', 'id');
    }

    public function scopeForVehicle($query, $manufacturerId, $modelName)
    {
        return $query->active()
            ->where('manufacturer_id', $manufacturerId)
            ->where(function ($query) use ($modelName) {
                $query->whereNull('applicable_model')
                    ->orWhere('applicable_model', $modelName);
            });
    }

    public function scopeActive(Builder $query): Builder
    {
        $now = now();
        return $query->where(function ($query) use ($now) {
            $query->where('start_date', '<=', $now)
                ->orWhereNull('start_date');
        })->where(function ($query) use ($now) {
            $query->where('end_date', '>=', $now)
                ->orWhereNull('end_date');
        });
    }
}
