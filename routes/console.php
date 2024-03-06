<?php

use App\Models\Customer;
use App\Models\Delivery;
use App\Models\Reservation;
use App\Models\Vehicle;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Foundation\Inspiring;

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

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

Artisan::command('cleanVehicle', function () {
    $date = Carbon::now()->subMonth();
    $deleted = Vehicle::where('deleted_at', '<', $date)
        ->onlyTrashed()
        ->get();

    if (count($deleted) !== 0) {
        $this->line('Clearing vehicles deleted more than a month ago...');
        $bar = $this->output->createProgressBar(count($deleted));
        $bar->start();
        foreach ($deleted as $item) {
            $item->forceDelete();
            $bar->advance();
        }
        $bar->finish();
    } else {
        $this->line('No Vehicles require cleaning');
    }
})->purpose(
    'Remove Vehicles if they are still in the recycle bin after a month',
);

Artisan::command('cleanOrders', function () {
    $date = Carbon::now()->subMonth();
    $deleted = Order::where('deleted_at', '<', $date)
        ->onlyTrashed()
        ->get();
    if (count($deleted) !== 0) {
        $this->line('Clearing orders deleted more than a month ago...');
        $bar = $this->output->createProgressBar(count($deleted));
        $bar->start();
        foreach ($deleted as $item) {
            $item->forceDelete();
            $bar->advance();
        }
        $bar->finish();
    } else {
        $this->line('No Orders require cleaning');
    }
})->purpose('Remove orders if they are still in the recycle bin after a month');

Artisan::command('cleanCustomers', function () {
    $date = Carbon::now()->subMonth();
    $deleted = Customer::where('deleted_at', '<', $date)
        ->onlyTrashed()
        ->get();
    if (count($deleted) !== 0) {
        $this->line('Clearing customers deleted more than a month ago...');
        $bar = $this->output->createProgressBar(count($deleted));
        $bar->start();
        foreach ($deleted as $item) {
            $item->forceDelete();
            $bar->advance();
        }
        $bar->finish();
    } else {
        $this->line('No Customers require cleaning');
    }
})->purpose('Remove orders if they are still in the recycle bin after a month');

Artisan::command('databaseClean', function () {
    $this->call('cleanOrders');
    $this->call('cleanVehicle');
    $this->call('cleanCustomers');
});

Artisan::command('checkReservationExpiry', function () {
    (new Reservation())->checkExpiry();
});

Artisan::command('checkDeliveries', function () {
    (new Delivery())->checkDeliveries();
});
