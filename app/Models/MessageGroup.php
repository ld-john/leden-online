<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\MessageGroup
 *
 * @property int $id
 * @property string $subject
 * @property int|null $order_id
 * @property string $last_message_sent
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|MessageGroup newModelQuery()
 * @method static Builder|MessageGroup newQuery()
 * @method static Builder|MessageGroup query()
 * @method static Builder|MessageGroup whereCreatedAt($value)
 * @method static Builder|MessageGroup whereId($value)
 * @method static Builder|MessageGroup whereLastMessageSent($value)
 * @method static Builder|MessageGroup whereOrderId($value)
 * @method static Builder|MessageGroup whereSubject($value)
 * @method static Builder|MessageGroup whereUpdatedAt($value)
 * @mixin Eloquent
 */
class MessageGroup extends Model
{
    /**
     * The table associated with this model
     *
     * @var string
     */
    protected $table = 'message_group';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'subject',
        'last_message_sent',
        'created_at',
        'updated_at',
    ];
}
