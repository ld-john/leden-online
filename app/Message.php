<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Message
 *
 * @property int $id
 * @property string|null $message
 * @property int $sender_id
 * @property int $recipient_id
 * @property string|null $recipient_read_at
 * @property int $message_group_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Message newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Message newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Message query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Message whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Message whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Message whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Message whereMessageGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Message whereRecipientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Message whereRecipientReadAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Message whereSenderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Message whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Message extends Model
{

    /**
     * The table associated with this model
     * 
     * @var string
     */
    protected $table = 'message';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'message', 'sender_id', 'recipient_id', 'recipient_read_at', 'message_group_id', 'order_id', 'created_at', 'updated_at',
    ];
}
