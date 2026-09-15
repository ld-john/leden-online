<?php

namespace App\Exports;

use App\Traits\FormatsExportData;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UniversalExport implements FromQuery, WithMapping, WithHeadings
{

    use FormatsExportData;
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
        return $this->query->with([
            'order.customer',
            'order.finance_broker',
            'tacticalOffers',
            'manufacturer',
            'broker',
            'dealer',
            'fitOptions'
        ]);
    }

    public function headings(): array
    {
        return [
            'Order Number', 'Customer Name', 'Ford Order Number', 'Orbit Number',
            'Vehicle Type', 'Make', 'Model', 'Derivative', 'Engine',
            'Transmission', 'Colour', 'Fuel Type', 'Chassis Prefix',
            'Chassis', 'Registration', 'Planned Build Date', 'Due Date',
            'Status', 'Reason for Recall', 'In Stock For', 'Broker', 'Finance Broker', 'Dealer', 'Broker Reference',
            'Factory Fit Options', 'Dealer Fit Options', 'Desired Delivery Month',
            'Website Location', 'Exclusion', 'Tactical Offers',
        ];
    }

    public function map($row): array
    {
        return [
            $row->order?->id,
            $row->order?->customer?->name(),
            $row->ford_order_number,
            $row->orbit_number,
            $row->type,
            $row->manufacturer?->name,
            $row->model,
            $row->derivative,
            $row->engine,
            $row->transmission,
            $row->colour,
            $row->fuel_type,
            $row->chassis_prefix,
            $row->chassis,
            $row->reg,
            $this->formatDate($row->build_date),
            $this->formatDate($row->due_date),
            $row->vehicle_status?->label(),
            $row->recall_reason,
            $row->in_stock_at?->diffForHumans(syntax: CarbonInterface::DIFF_ABSOLUTE) ?? 'n/a',
            $row->broker?->company_name,
            $row->order?->finance_broker?->company_name,
            $row->dealer?->company_name,
            $row->order?->broker_ref,
            $row->fitOptions->where('option_type', '=', 'factory')->pluck('option_name')->implode(', '),
            $row->fitOptions->where('option_type', '=', 'dealer')->pluck('option_name')->implode(', '),
            $row->order?->delivery_month,
            $row->websiteLocation()['location'] ?? '',
            $row->order?->exception ? 'Yes' : 'No',
            $this->formatTacticalOffers($row->tacticalOffers),
        ];
    }
}
