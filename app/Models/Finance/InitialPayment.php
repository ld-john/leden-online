<?php

namespace App\Models\Finance;

use App\Models\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property mixed $option
 */
class InitialPayment extends Model
{
    protected $guarded = [];
    function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'finance_type', 'id');
    }
}
