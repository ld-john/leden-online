<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property mixed|string $expiry_date
 * @property mixed $vehicle_id
 * @property mixed $broker_id
 * @property mixed $customer_id
 * @property mixed $id
 * @property mixed $company
 * @property mixed $customer
 */
class Reservation extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    public function customer(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'customer_id');
    }

    public function company(): HasOne
    {
        return $this->hasOne(Company::class, 'id', 'broker_id');
    }
    public function vehicle(): HasOne
    {
        return $this->hasOne(Vehicle::class, 'id', 'vehicle_id');
    }
    public function leden_user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'leden_user_id');
    }
}
