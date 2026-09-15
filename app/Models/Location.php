<?php

namespace App\Models;

use App\Enums\VehicleStatus;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed $id
 * @property mixed $location
 * @property mixed $status
 */
class Location extends Model
{
    protected $fillable = ['name', 'status', 'location'];

    protected $casts = [
        'status' => VehicleStatus::class
    ];
}
