<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property mixed|string $name
 * @property mixed|string $type
 * @property mixed $vehicle_model
 * @property mixed $id
 */
class VehicleMeta extends Model
{
    use SoftDeletes;
    protected $guarded = [];
    protected $table = 'vehicle_meta';

    public function vehicle_model(): BelongsToMany
    {
        return $this->belongsToMany(VehicleModel::class);
    }
}
