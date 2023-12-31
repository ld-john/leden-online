<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}
