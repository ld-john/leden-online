<?php

namespace App\Models;

use App\Notifications\DeliveryScheduledTomorrowEmailNotification;
use App\Notifications\DeliveryScheduledTomorrowNotification;
use App\Notifications\DeliveryScheduledYesterdayEmailNotification;
use App\Notifications\DeliveryScheduledYesterdayNotification;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property mixed|string $delivery_date
 * @property mixed $delivery_address1
 * @property mixed $delivery_address2
 * @property mixed $delivery_town
 * @property mixed $delivery_city
 * @property mixed $delivery_postcode
 * @property mixed $contact_name
 * @property mixed $contact_number
 * @property mixed $id
 * @property mixed $funder_confirmation
 */
class Delivery extends Model
{
    public function order(): HasOne
    {
        return $this->hasOne(Order::class, 'delivery_id', 'id');
    }

    public function checkDeliveries(): void
    {
        $tomorrow = Carbon::now()->addWeekday(1);
        $yesterday = Carbon::now()->subWeekday(1);
        $tomorrowsDeliveries = Delivery::where(
            'delivery_date',
            '=',
            $tomorrow,
        )->get();
        $yesterdaysDeliveries = Delivery::where(
            'delivery_date',
            '=',
            $yesterday,
        )->get();

        foreach ($tomorrowsDeliveries as $delivery) {
            $dealers = User::where(
                'company_id',
                $delivery->order->broker,
            )->get();
            $permission = Permission::where('name', 'receive-emails')->first();
            $mailDealers = $permission?->users
                ->where('company_id', $delivery->order->broker)
                ->all();
            foreach ($dealers as $dealer) {
                $dealer->notify(
                    new DeliveryScheduledTomorrowNotification($delivery),
                );
            }
            foreach ($mailDealers as $dealer) {
                $dealer->notify(
                    new DeliveryScheduledTomorrowEmailNotification($delivery),
                );
            }
            (new User())
                ->forceFill([
                    'name' => 'Deliveries',
                    'email' => 'deliveries@leden.co.uk',
                ])
                ->notify(
                    new DeliveryScheduledTomorrowEmailNotification($delivery),
                );
        }

        foreach ($yesterdaysDeliveries as $delivery) {
            $dealers = User::where(
                'company_id',
                $delivery->order->broker,
            )->get();
            $permission = Permission::where('name', 'receive-emails')->first();
            $mailDealers = $permission?->users
                ->where('company_id', $delivery->order->broker)
                ->all();
            foreach ($dealers as $dealer) {
                $dealer->notify(
                    new DeliveryScheduledYesterdayNotification($delivery),
                );
            }
            foreach ($mailDealers as $dealer) {
                $dealer->notify(
                    new DeliveryScheduledYesterdayEmailNotification($delivery),
                );
            }
            (new User())
                ->forceFill([
                    'name' => 'Handovers',
                    'email' => 'handovers@leden.co.uk',
                ])
                ->notify(
                    new DeliveryScheduledYesterdayEmailNotification($delivery),
                );
        }

        Message::create([
            'message' => 'Delivery Checker has run',
        ]);
    }
}
