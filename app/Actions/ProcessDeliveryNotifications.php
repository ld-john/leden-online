<?php

namespace App\Actions;

use App\Models\Company;
use App\Models\Delivery;
use App\Models\User;
use App\Notifications\DeliveryEtaRequestNotification;
use App\Notifications\DeliveryScheduledTomorrowEmailNotification;
use App\Notifications\DeliveryScheduledTomorrowNotification;
use App\Notifications\DeliveryScheduledYesterdayEmailNotification;
use App\Notifications\DeliveryScheduledYesterdayNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class ProcessDeliveryNotifications
{
    public function handle():void
    {
        $today = Carbon::today();
        $tomorrow = Carbon::now()->addWeekday()->startOfDay();
        $yesterday = Carbon::now()->subWeekday()->startOfDay();

        Log::info('ProcessDeliveryNotifications task started.');

        $this->requestEtasForToday($today);

        $this->processTomorrowsDeliveries($tomorrow);

        $this->processYesterdaysDeliveries($yesterday);
    }

    protected function requestEtasForToday(Carbon $today): void
    {
        $todaysDeliveries = Delivery::with('deliveryAgent')
            ->whereDate('delivery_date', $today)
            ->whereNotNull('deliveries.delivery_agent_id')
            ->get();

        foreach ($todaysDeliveries as $delivery) {
            if ($delivery->deliveryAgent) {

                $vehicle = $delivery->order->vehicle;

                Log::info($delivery->order);
                Log::info($vehicle);

                Notification::send($delivery->deliveryAgent, new DeliveryEtaRequestNotification($delivery, $vehicle));
            }
        }
    }

    protected function processTomorrowsDeliveries(Carbon $tomorrow): void
    {
        $deliveries = Delivery::with(['order'])->whereDate('delivery_date', $tomorrow)->get();

        foreach ($deliveries as $delivery) {
            $brokerId = $delivery->order?->broker;

            if ($brokerId) {
                $this->notifyDealersAndUsers($delivery, $brokerId, 'tomorrow');
            }

            // On-demand notification to internal team
            Notification::route('mail', 'deliveries@leden.co.uk')
                ->notify(new DeliveryScheduledTomorrowEmailNotification($delivery));
        }
    }

    private function processYesterdaysDeliveries(Carbon $yesterday): void
    {
        $deliveries = Delivery::with(['order'])->whereDate('delivery_date', $yesterday)->get();

        foreach ($deliveries as $delivery) {
            $brokerId = $delivery->order?->broker;

            if ($brokerId) {
                $this->notifyDealersAndUsers($delivery, $brokerId, 'yesterday');
            }

            // On-demand notification to internal team
            Notification::route('mail', 'handovers@leden.co.uk')
                ->notify(new DeliveryScheduledYesterdayEmailNotification($delivery));
        }
    }

    protected function notifyDealersAndUsers(mixed $delivery, Company $brokerId, string $timing): void
    {
        // Query users directly by company_id in the database
        $dealers = User::where('company_id', $brokerId)->get();

        // Query permission-scoped users directly in the database rather than in-memory
        $emailDealers = User::where('company_id', $brokerId)
            ->whereHas('permissions', function ($query) {
                $query->where('name', 'receive-emails');
            })
            ->get();

        if ($timing === 'tomorrow') {
            Notification::send($dealers, new DeliveryScheduledTomorrowNotification($delivery));
            Notification::send($emailDealers, new DeliveryScheduledTomorrowEmailNotification($delivery));
        } else {
            Notification::send($dealers, new DeliveryScheduledYesterdayNotification($delivery));
            Notification::send($emailDealers, new DeliveryScheduledYesterdayEmailNotification($delivery));
        }
    }

}
