<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\OrderUpload
 *
 * @property int $id
 * @property int $order_id
 * @property string $file_name
 * @property string $file_type
 * @property int $uploaded_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderUpload newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderUpload newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\OrderUpload onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderUpload query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderUpload whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderUpload whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderUpload whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderUpload whereFileType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderUpload whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderUpload whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderUpload whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderUpload whereUploadedBy($value)
 * @method static \Illuminate\Database\Query\Builder|\App\OrderUpload withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\OrderUpload withoutTrashed()
 * @mixin \Eloquent
 */
class OrderUpload extends Model
{
    use SoftDeletes;
}
