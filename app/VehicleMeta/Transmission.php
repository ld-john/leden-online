<?php

namespace App\VehicleMeta;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transmission extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'vehicle-transmissions';
}
