<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\FailedJob
 *
 * @property int $id
 * @property string $connection
 * @property string $queue
 * @property string $payload
 * @property string $exception
 * @property string $failed_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FailedJob newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FailedJob newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FailedJob query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FailedJob whereConnection($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FailedJob whereException($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FailedJob whereFailedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FailedJob whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FailedJob wherePayload($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FailedJob whereQueue($value)
 * @mixin \Eloquent
 */
class FailedJob extends Model
{
    //
}
