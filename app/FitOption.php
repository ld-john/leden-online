<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * App\FitOption
 *
 * @property int $id
 * @property string $option_type
 * @property string $option_name
 * @property float $option_price
 * @property string $model
 * @property string $model_year
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property mixed $dealer_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FitOption newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FitOption newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FitOption query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FitOption whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FitOption whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FitOption whereOptionName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FitOption whereOptionPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FitOption whereOptionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FitOption whereUpdatedAt($value)
 * @mixin Eloquent
 */
class FitOption extends Model
{
    protected $guarded = [];

    public function dealer(): HasOne
    {
        return $this->hasOne(Company::class, 'id', 'dealer_id');
    }

    public function vehicle_model(): HasOne
    {
        return $this->hasOne(VehicleModel::class, 'id', 'model');
    }

    public function vehicles(): BelongsToMany
    {
        return $this->belongsToMany(
            Vehicle::class,
            'fit_options_vehicle',
            'option_id',
            'vehicle_id',
        );
    }

    public function factoryOptionName(): Attribute
    {
        return new Attribute(
            get: fn($value, $attributes) => $attributes['option_name'] .
                '-' .
                $attributes['model_year'] .
                'MY-' .
                $attributes['model'],
        );
    }

    public function dealerOptionName(): Attribute
    {
        return new Attribute(
            get: fn($value, $attributes) => $attributes['option_name'] .
                '-' .
                $attributes['model_year'] .
                'MY-' .
                $attributes['model'],
        );
    }
}
