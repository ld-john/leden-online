<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property mixed|string $delivery_date
 * @property mixed $delivery_address1
 * @property mixed $delivery_address2
 * @property mixed $delivery_town
 * @property mixed $delivery_city
 * @property mixed $delivery_postcode
 * @property mixed $contact_name
 * @property mixed $contact_number
 * @property mixed $id
 * @property mixed $funder_confirmation
 */
class Delivery extends Model
{
    protected $fillable = [
        'id',
        'delivery_date',
        'delivery_address1',
        'delivery_address2',
        'delivery_town',
        'delivery_city',
        'delivery_postcode',
        'contact_name',
        'contact_number',
        'funder_confirmation',
        'delivery_agent_id',
    ];

    public function order(): HasOne
    {
        return $this->hasOne(Order::class, 'delivery_id', 'id');
    }

    public function deliveryAgent(): HasOne
    {
        return $this->hasOne(Company::class, 'id', 'delivery_agent_id');
    }
}
