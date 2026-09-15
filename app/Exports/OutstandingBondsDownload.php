<?php

namespace App\Exports;

use App\Traits\FormatsExportData;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OutstandingBondsDownload implements FromQuery, WithHeadings, WithMapping
{
    use FormatsExportData;

    protected Builder $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function query(): Builder
    {
        return $this->query->with([
            'order', 'order.dealer', 'order.vehicle.tacticalOffers'
        ]);
    }


    public function headings(): array
    {
        return [
            'Order #',
            'Registration Number',
            'Tactical Offers',
            'Customer Name',
            'Delivery Date',
            'Bond Value',
            'Payment Date',
            'Dealer'
        ];
    }

    public function map($row): array
    {
        return [
            $row->order?->id,
            $row->order?->vehicle?->reg ?? 'Not found',
            $this->formatTacticalOffers($row->order?->vehicle?->tacticalOffers),
            $row->order?->customer?->customer_name,
            $this->formatDate($row->order?->delivery_date),
            $row->bond_value,
            $this->formatDate($row->payment_date),
            $row->order?->dealer?->company_name
        ];
    }
}
