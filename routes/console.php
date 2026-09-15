<?php

use App\Actions\ProcessDeliveryNotifications;
use app\Actions\ProcessReservationExpires;
use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Schedule::command('clean-vehicles')->dailyAt('23:50');

Schedule::call(function (ProcessReservationExpires $action) {
    $action->handle();
})->weekdays()->dailyAt('23:55')->name('processReservationExpires')->withoutOverlapping();

Schedule::call(function (ProcessDeliveryNotifications $action) {
    $action->handle();
})->weekdays()->dailyAt('8:00')->name('processDeliveryNotifications')->withoutOverlapping();
