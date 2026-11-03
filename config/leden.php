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
    'handover' => [
        'address' => env('EMAIL_HANDOVER', 'handovers@leden.co.uk'),
    ],
    'delivery' => [
        'address' => env('EMAIL_DELIVERY', 'deliveries@leden.co.uk'),
    ],
];
