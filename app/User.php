<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * App\User
 *
 * @property int $id
 * @property string $firstname
 * @property string $lastname
 * @property string $email
 * @property string $role
 * @property string|null $phone
 * @property int|null $company_id
 * @property int|null $is_deleted
 * @property string|null $last_login
 * @property string $password
 * @property string|null $remember_token
 * @property-read int|null $notifications_count
 * @property boolean $reservation_allowed
 */
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function abilities(): string
    {
        return $this->role;
    }

    public function company()
    {
    	return $this->hasOne(Company::class, 'id', 'company_id');
    }
}
