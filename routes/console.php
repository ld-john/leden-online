<?php

use App\Models\Vehicle;
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
    $date = Carbon::now()->subDays(30);
    $deleted = Vehicle::where('deleted_at', '>', $date)->onlyTrashed()->get();

    var_dump(count($deleted));

    foreach ($deleted as $item) {
        $item->forceDelete();
    }

});

Artisan::command('checkReservationExpiry', function () {
   (new \App\Models\Reservation)->checkExpiry();
});
