<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DashboardExports implements FromQuery, WithHeadings, WithMapping
{
    protected Builder $query;

    /**
     * @return void
     */
    public function __construct($query)
    {
        $this->query = $query;
    }

    public function query(): Builder
    {
        return $this->query;
    }

    public function headings(): array
    {
        return [
            'Leden Order Number',
            'Customer Name',
            'Ford Order Number',
            'Orbit Number',
            'Vehicle Type',
            'Chassis',
            'Registration',
            'Planned Build Date',
            'Due Date',
            'Desired Delivery Month',
            'Broker',
            'Broker Reference',
        ];
    }

    public function map($vehicle): array
    {
        if (Carbon::parse($vehicle->build_date)->format('y') === '-1') {
            $build_date = 'N/a';
        } elseif ($vehicle->build_date) {
            $build_date = Carbon::parse($vehicle->build_date)->format('d/m/y');
        } else {
            $build_date = 'N/a';
        }

        $broker = 'No broker found';

        if ($vehicle->order) {
            if (
                Carbon::parse($vehicle->order->due_date)->format('y') === '-1'
            ) {
                $due_date = 'N/a';
            } elseif ($vehicle->order->due_date) {
                $due_date = Carbon::parse($vehicle->order->due_date)->format(
                    'd/m/y',
                );
            } else {
                $due_date = 'N/a';
            }
            $broker = $vehicle->order->broker->company_name;
        } else {
            $due_date = 'N/a';
            $broker = $vehicle->broker?->company_name ?? 'No broker found';
        }

        return [
            $vehicle->order->id ?? '',
            $vehicle->order?->customer->name() ?? 'Vehicle Unsold',
            $vehicle->ford_order_number ?? '',
            $vehicle->orbit_number ?? '',
            $vehicle->type,
            $vehicle->chassis,
            $vehicle->reg,
            $build_date,
            $due_date,
            $vehicle->order?->delivery_month ?? '',
            $broker,
            $vehicle->order?->broker_ref ?? '',
        ];
    }
}
