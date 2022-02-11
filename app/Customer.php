<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function name()
    {
        if ($this->preferred_name === "company") {
            $name = $this->company_name;
        } else {
            $name = $this->customer_name;
        }

        return $name;
    }
}
