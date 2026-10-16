<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

/**
 * @property mixed $id
 * @property mixed $company_name
 */
class Company extends Model
{
    use SoftDeletes, Notifiable;

    protected $guarded = [];

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class, 'dealer_id', 'id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'company_id', 'id');
    }

    public function routeNotificationForMail( $notification = null)
    {
        return $this->company_email;
    }
}
