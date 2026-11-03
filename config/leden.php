<?php
/*
|--------------------------------------------------------------------------
| Email Addresses Used by Leden
|--------------------------------------------------------------------------
|
|
*/

return [
    'otr_requests' => [
        'address' => env('OTR_REQUEST_EMAIL', 'availabilities@leden.co.uk'),
        'name' => env('OTR_REQUEST_NAME', 'Availabilities'),
    ],
    'email' => [
        'handover' => env('EMAIL_HANDOVER', 'handovers@leden.co.uk'),
        'delivery' => env('EMAIL_DELIVERY', 'deliveries@leden.co.uk'),
    ],
];
