<?php

namespace App\Models;

use App\Notifications\ReservationDeleted;
use App\Notifications\ReservationExpiryApproaching;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property mixed|string $expiry_date
 * @property mixed $vehicle_id
 * @property mixed $broker_id
 * @property mixed $customer_id
 * @property mixed $id
 * @property mixed $company
 * @property mixed $customer
 */
class Reservation extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    public function checkExpiry(): void
    {
        $date = Carbon::now();
        $tomorrow = Carbon::now()->addWeekday(1);
        $admin = User::where('company_id', '7')->get();
        $expiringActive = Reservation::where('expiry_date', '<=', $date)
            ->where('status', 'active')
            ->get();
        $message = new Message();
        $message->message = 'CheckExpiry has run';
        $message->save();
        foreach ($expiringActive as $item) {
            $item->update([
                'expiry_date' => $tomorrow,
                'status' => 'deadline_approaching',
            ]);
            $item->customer->notify(new ReservationExpiryApproaching($item));
        }
        $expiryExtended = Reservation::where('expiry_date', '<=', $date)
            ->where('status', 'extended')
            ->get();
        foreach ($expiryExtended as $item) {
            $item->update([
                'expiry_date' => $tomorrow,
                'status' => 'extended_deadline_approaching',
            ]);
            $item->customer->notify(new ReservationExpiryApproaching($item));
        }
        $expiryDeadline = Reservation::where('expiry_date', '<=', $date)
            ->where(function ($q) {
                $q->where('status', 'deadline_approaching')->orWhere(
                    'status',
                    'extended_deadline_approaching',
                );
            })
            ->get();
        foreach ($expiryDeadline as $item) {
            $item->update([
                'status' => 'lapsed',
            ]);
            $item->delete();
            $item->customer->notify(new ReservationDeleted($item));
            foreach ($admin as $user) {
                $user->notify(new ReservationDeleted($item));
            }
        }
    }

    public function customer(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'customer_id');
    }

    public function company(): HasOne
    {
        return $this->hasOne(Company::class, 'id', 'broker_id');
    }
    public function vehicle(): HasOne
    {
        return $this->hasOne(Vehicle::class, 'id', 'vehicle_id');
    }
    public function leden_user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'leden_user_id');
    }
}
