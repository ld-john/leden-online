<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property mixed $content
 * @property int|mixed|string|null $user_id
 * @property mixed $commentable_id
 * @property mixed $commentable_type
 * @property boolean $private
 * @property bool|mixed $dealer_comment
 */
class Comment extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }
}
