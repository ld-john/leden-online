<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\MessageGroup
 *
 * @property int $id
 * @property string $subject
 * @property int|null $order_id
 * @property string $last_message_sent
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MessageGroup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MessageGroup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MessageGroup query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MessageGroup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MessageGroup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MessageGroup whereLastMessageSent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MessageGroup whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MessageGroup whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MessageGroup whereUpdatedAt($value)
 * @mixin \Eloquent
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
        'subject', 'last_message_sent', 'created_at', 'updated_at',
    ];
}
