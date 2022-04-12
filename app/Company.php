<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    public function vehicles(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
    	return $this->hasMany(Vehicle::class, 'dealer_id', 'id');
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(User::class, 'company_id', 'id');
    }
}
