<?php

namespace App\Models\Finance;

use App\Models\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property mixed|string $option
 */
class FinanceType extends Model
{
    protected $guarded = [];
    function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'finance_type', 'id');
    }
}
