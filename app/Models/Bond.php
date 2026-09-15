<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bond extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'bond_value',
        'payment_date',
        'refund_value',
        'refund_date',
    ];

    protected function casts(): array
    {
        return [
            'payment_date' => 'date',
            'refund_date' => 'date',
        ];
    }

    public function order(): HasOne
    {
        return $this->hasOne(Order::class, 'bond_id', 'id');
    }
}
