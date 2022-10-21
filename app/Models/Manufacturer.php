<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Manufacturer extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class, 'make', 'id');
    }

    public function vehicle_models(): HasMany
    {
        return $this->hasMany(VehicleModel::class, 'manufacturer_id', 'id');
    }
}
