<?php

namespace App\Traits;

use Carbon\Carbon;

trait FormatsExportData
{
    private function formatDate($date) : string
    {
        if (empty($date) || $date == '0000-00-00 00:00:00') {
            return '';
        }
        return Carbon::parse($date)->format('d/m/Y');
    }

    private function formatTacticalOffers($offers): string
    {
        if (!$offers) return '';

        return $offers->map(function ($offer) {
            return "{$offer->name} - £{$offer->value}";
        })->implode(', ');
    }
}
