<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property mixed $name
 * @property mixed|string $slug
 * @property int|mixed $manufacturer_id
 * @property mixed|null $id
 * @method static where(string $string, $model)
 */
class VehicleModel extends Model
{
    protected $guarded = [];

    public function manufacturer(): BelongsTo
    {
        return $this->belongsTo(Manufacturer::class);
    }

    public function vehicle_meta()
    {
        $this->belongsToMany(VehicleMeta::class);
    }

    /**
     * Get all fit options for this vehicle model.
     */
    public function fitOptions(): HasMany
    {

        return $this->hasMany(FitOption::class, 'model', 'id');
    }

    public function getGroupedFitOptionsAttribute(): Collection
    {
        return $this->fitOptions
            ->groupBy('option_name')
            ->map(function (Collection $options, string $optionName) {
            $prices = $options->pluck('option_price')->filter()->unique();
            $minPrice = $prices->min();
            $maxPrice = $prices->max();

            // Find duplicate combinations of (model_year + option_price + dealer)
            $duplicateCount = $options->count() - $options->unique(function ($item) {
                    return $item->model_year . '|' . (float) $item->option_price . '|' . $item->dealer;
                })->count();

            return (object) [
                'option_name' => $optionName,
                'items' => $options,
                'total_count' => $options->count(),
                'has_duplicates' => $duplicateCount > 0,
                'duplicate_count' => $duplicateCount,
                'model_years' => $options->pluck('model_year')->filter()->unique()->sort()->values(),
                'min_price' => $minPrice,
                'max_price' => $maxPrice,
                'has_price_range' => $minPrice !== $maxPrice,
                'dealer_names' => $options->pluck('dealer.company_name')->filter()->unique()
            ];
        });
    }
}
