<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UniversalExport implements FromQuery, WithHeadings, WithMapping
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
            'Order Number',
            'Customer Name',
            'Ford Order Number',
            'Orbit Number',
            'Vehicle Type',
            'Make',
            'Model',
            'Derivative',
            'Engine',
            'Transmission',
            'Colour',
            'Fuel Type',
            'Chassis Prefix',
            'Chassis',
            'Registration',
            'Planned Build Date',
            'Due Date',
            'Status',
            'Broker',
            'Finance Broker',
            'Dealer',
            'Broker Reference',
            'Factory Fit Options',
            'Dealer Fit Options',
            'Desired Delivery Month',
            'Website Location',
            'Exclusion',
        ];
    }

    public function map($vehicle): array
    {
        if (
            empty($vehicle->build_date) ||
            $vehicle->build_date === '0000-00-00'
        ) {
            $build_date = '';
        } else {
            $build_date = Carbon::parse($vehicle->build_date)->format('d/m/Y');
        }

        if (empty($vehicle->due_date) || $vehicle->due_date === '0000-00-00') {
            $due_date = '';
        } else {
            $due_date = Carbon::parse($vehicle->due_date)->format('d/m/Y');
        }

        $website_location = $vehicle->websiteLocation();

        if ($vehicle->order?->exception) {
            $exception = 'Yes';
        } else {
            $exception = 'No';
        }

        return [
            $vehicle->order?->id ?? '',
            $vehicle->order?->customer->name() ?? '',
            $vehicle->ford_order_number,
            $vehicle->orbit_number,
            $vehicle->type,
            $vehicle->manufacturer?->name,
            $vehicle->model,
            $vehicle->derivative,
            $vehicle->engine,
            $vehicle->transmission,
            $vehicle->colour,
            $vehicle->fuel_type,
            $vehicle->chassis_prefix,
            $vehicle->chassis,
            $vehicle->reg,
            $build_date,
            $due_date,
            $vehicle->status(),
            $vehicle->broker?->company_name,
            $vehicle->order?->finance_broker?->company_name,
            $vehicle->dealer?->company_name,
            $vehicle->order?->broker_ref,
            implode(
                ', ',
                $vehicle
                    ->factoryFitOptions()
                    ->pluck('option_name')
                    ->toArray(),
            ),
            implode(
                ', ',
                $vehicle
                    ->dealerFitOptions()
                    ->pluck('option_name')
                    ->toArray(),
            ),
            $vehicle->order?->delivery_month ?? '',
            $website_location['location'],
            $exception,
        ];
    }
}
