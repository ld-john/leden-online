<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * App\Message
 *
 * @property int $id
 * @property string|null $message
 * @property int $sender_id
 * @property int $recipient_id
 * @property string|null $recipient_read_at
 * @property int $message_group_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Message newModelQuery()
 * @method static Builder|Message newQuery()
 * @method static Builder|Message query()
 * @method static Builder|Message whereCreatedAt($value)
 * @method static Builder|Message whereId($value)
 * @method static Builder|Message whereMessage($value)
 * @method static Builder|Message whereMessageGroupId($value)
 * @method static Builder|Message whereRecipientId($value)
 * @method static Builder|Message whereRecipientReadAt($value)
 * @method static Builder|Message whereSenderId($value)
 * @method static Builder|Message whereUpdatedAt($value)
 * @mixin Eloquent
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
    protected $guarded = [];

    public function sender(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'sender_id');
    }

    public function recipient(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'recipient_id');
    }
}
