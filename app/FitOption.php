<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\FitOption
 *
 * @property int $id
 * @property string $option_type
 * @property string $option_name
 * @property float $option_price
 * @property string $model
 * @property string $model_year
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FitOption newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FitOption newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FitOption query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FitOption whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FitOption whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FitOption whereOptionName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FitOption whereOptionPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FitOption whereOptionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FitOption whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FitOption extends Model
{
    public function dealer(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Company::class, 'id', 'dealer_id');
    }
}
