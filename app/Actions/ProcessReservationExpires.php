<?php

namespace App\Actions;

use App\Models\Reservation;
use App\Models\User;
use App\Notifications\ReservationDeleted;
use App\Notifications\ReservationExpiryApproaching;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class ProcessReservationExpires
{
    public function handle() : void
    {
        $now = now();
        $tomorrow = now()->addWeekday();

        Log::info('ProcessReservationExpiries task started.');

        $this->processApproachingExpires($now, $tomorrow);
        $this->processLapsedReservations($now);
    }

    protected function processApproachingExpires(Carbon $now, Carbon $tomorrow): void
    {
        $statusMap = [
            'active' => 'deadline_approaching',
            'extended' => 'extended_deadline_approaching'
        ];

        foreach ($statusMap as $currentStatus => $newStatus) {
            Reservation::with('customer')
                ->where('expiry_date', '<=', $now)
                ->where('status', $currentStatus)
                ->lazy()
                ->each(function (Reservation $reservation) use ($tomorrow, $newStatus) {
                    $reservation->update([
                        'expiry_date' => $tomorrow,
                        'status' => $newStatus,
                    ]);

                    if ($reservation->customer) {
                        $reservation->customer->notify(new ReservationExpiryApproaching($reservation));
                    }
                });
        }
    }

    protected function processLapsedReservations(Carbon $now):void {
        // Eager load customer to prevent N+1 queries during notification sending
        $lapsedReservations = Reservation::with('customer')
            ->where('expiry_date', '<=', $now)
            ->whereIn('status', ['deadline_approaching', 'extended_deadline_approaching'])
            ->get();

        if ($lapsedReservations->isEmpty()) {
            return;
        }

        // Fetch company admins (consider moving company_id to a config file or scope)
        $admins = User::where('company_id', config('app.admin_company_id', 7))->get();

        foreach ($lapsedReservations as $reservation) {
            $reservation->update(['status' => 'lapsed']);
            $reservation->delete(); // Soft delete

            // Notify customer
            if ($reservation->customer) {
                $reservation->customer->notify(new ReservationDeleted($reservation));
            }

            // Notify admins in bulk using On-Demand / Collection notification sending
            if ($admins->isNotEmpty()) {
                Notification::send($admins, new ReservationDeleted($reservation));
            }
        }
    }
}
