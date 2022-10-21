<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property mixed $update_text
 * @property mixed $dashboard
 * @property mixed|string $update_type
 * @property mixed $header
 * @property mixed $image
 */
class Updates extends Model
{
    protected $guarded = [];
    use SoftDeletes;
}
