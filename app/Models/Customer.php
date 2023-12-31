<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property mixed $phone_number
 * @property mixed $postcode
 * @property mixed $county
 * @property mixed $city
 * @property mixed $town
 * @property mixed $address_2
 * @property mixed $address_1
 * @property mixed $customer_name
 * @property mixed $id
 * @property mixed $orders
 */
class Customer extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    public function name()
    {
        return $this->customer_name;
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'customer_id', 'id');
    }
}
