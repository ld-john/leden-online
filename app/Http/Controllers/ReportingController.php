<?php

namespace App\Http\Controllers;

use App\Exports\FactoryOrderExports;
use App\Exports\DashboardExports;
use App\Order;
use App\Vehicle;
use DateTime;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Dashboard;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use LaravelIdea\Helper\App\_IH_Order_C;
use Maatwebsite\Excel\Facades\Excel;

class ReportingController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function showReporting()
    {
        $weekly_sales = $this->getRecordsByWeek();
        $monthly_sales = $this->getRecordsByMonth();
        $quarterly_sales = $this->getRecordsByQuarter();
        $weekly_registered = $this->getRegisteredByWeekly();
        $monthly_registered = $this->getRegisteredByMonth();
        $quarterly_registered = $this->getRegisteredByQuarter();
        $monthly_completed = $this->getCompletedByMonth();
        $weekly_completed = $this->getCompletedByWeekly();
        $quarterly_completed = $this->getCompletedByQuarter();

        return view('reporting.index', [
            'in_stock' => Dashboard::GetOrdersByVehicleStatus(1),
            'orders_placed' => Dashboard::GetOrdersByVehicleStatus(2),
            'ready_for_delivery' => Dashboard::GetOrdersByVehicleStatus(3),
            'factory_order' => Dashboard::GetOrdersByVehicleStatus(4),
            'delivered' => Dashboard::GetOrdersByVehicleStatus(6),
            'completed_orders' => Dashboard::GetOrdersByVehicleStatus(7),
            'europe_vhc' => Dashboard::GetOrdersByVehicleStatus(10),
            'uk_vhc' => Dashboard::GetOrdersByVehicleStatus(11),
            'monthly_sales' => $monthly_sales,
            'monthly_registered' => $monthly_registered,
            'monthly_completed' => $monthly_completed,
            'weekly_sales' => $weekly_sales,
            'weekly_registered' => $weekly_registered,
            'weekly_completed' => $weekly_completed,
            'quarterly_sales' => $quarterly_sales,
            'quarterly_registered' => $quarterly_registered,
            'quarterly_completed' => $quarterly_completed,
        ]);
    }

    public function getRecordsByMonth(): Collection
    {
        $months = $this->monthNames();

        $month_array = [];

        foreach ($months as $month) {
            $data = Order::whereMonth('created_at', '=', $month)->get();
            $month['data'] = count($data);
            $month_array[] = $month;
        }

        return collect($month_array);
    }

    public function getRecordsByWeek(): Collection
    {
        $weeks = $this->weekNumbers();

        $week_array = [];

        foreach ($weeks as $week) {
            $dates = $this->getStartAndEndDate($week['week'], $week['year']);
            $data = Order::whereBetween('created_at', $dates)->get();
            $week['data'] = count($data);
            $week['label'] = 'week ' . $week['week'] . ' ' . $week['year'];
            $week_array[] = $week;
        }

        return collect($week_array);
    }

    /**
     * @throws Exception
     */
    public function getRecordsByQuarter(): Collection
    {
        $quarters = $this->quarters();

        $quarter_array = [];

        foreach ($quarters as $quarter) {
            $dates = $this->get_dates_of_quarter(
                $quarter['quarter'],
                $quarter['year'],
            );
            $data = Order::whereBetween('created_at', $dates)->get();
            $quarter['data'] = count($data);
            $quarter['label'] =
                'Q' . $quarter['quarter'] . ' ' . $quarter['year'];
            $quarter_array[] = $quarter;
        }

        return collect($quarter_array);
    }

    public function getRegisteredByMonth(): Collection
    {
        $months = $this->monthNames();

        $month_array = [];

        foreach ($months as $month) {
            $data = Vehicle::whereMonth('vehicle_registered_on', '=', $month)
                ->whereYear('vehicle_registered_on', '=', $month['year'])
                ->get();
            $month['data'] = count($data);
            $month_array[] = $month;
        }

        return collect($month_array);
    }

    public function getRegisteredByWeekly(): Collection
    {
        $weeks = $this->weekNumbers();

        $week_array = [];

        foreach ($weeks as $week) {
            $dates = $this->getStartAndEndDate($week['week'], $week['year']);
            $data = Vehicle::whereBetween(
                'vehicle_registered_on',
                $dates,
            )->get();
            $week['data'] = count($data);
            $week['label'] = 'week ' . $week['week'] . ' ' . $week['year'];
            $week_array[] = $week;
        }

        return collect($week_array);
    }

    /**
     * @throws Exception
     */
    public function getRegisteredByQuarter(): Collection
    {
        $quarters = $this->quarters();

        $quarter_array = [];

        foreach ($quarters as $quarter) {
            $dates = $this->get_dates_of_quarter(
                $quarter['quarter'],
                $quarter['year'],
            );
            $data = Vehicle::whereBetween(
                'vehicle_registered_on',
                $dates,
            )->get();
            $quarter['data'] = count($data);
            $quarter['label'] =
                'Q' . $quarter['quarter'] . ' ' . $quarter['year'];
            $quarter_array[] = $quarter;
        }

        return collect($quarter_array);
    }

    public function getCompletedByMonth(): Collection
    {
        $months = $this->monthNames();

        $month_array = [];

        foreach ($months as $month) {
            $data = Order::whereMonth('completed_date', '=', $month)->get();
            $month['data'] = count($data);
            $month_array[] = $month;
        }

        return collect($month_array);
    }

    public function getCompletedByWeekly(): Collection
    {
        $weeks = $this->weekNumbers();

        $week_array = [];

        foreach ($weeks as $week) {
            $dates = $this->getStartAndEndDate($week['week'], $week['year']);
            $data = Order::whereBetween('completed_date', $dates)->get();
            $week['data'] = count($data);
            $week['label'] = 'week ' . $week['week'] . ' ' . $week['year'];
            $week_array[] = $week;
        }

        return collect($week_array);
    }

    /**
     * @throws Exception
     */
    public function getCompletedByQuarter(): Collection
    {
        $quarters = $this->quarters();

        $quarter_array = [];

        foreach ($quarters as $quarter) {
            $dates = $this->get_dates_of_quarter(
                $quarter['quarter'],
                $quarter['year'],
            );
            $data = Order::whereBetween('completed_date', $dates)->get();
            $quarter['data'] = count($data);
            $quarter['label'] =
                'Q' . $quarter['quarter'] . ' ' . $quarter['year'];
            $quarter_array[] = $quarter;
        }

        return collect($quarter_array);
    }

    public function monthNames(): array
    {
        $period = now()
            ->subMonths(5)
            ->monthsUntil(now());

        $data = [];
        foreach ($period as $date) {
            $data[] = [
                'month' => $date->month,
                'month_label' => $date->monthName,
                'year' => $date->year,
            ];
        }

        return $data;
    }

    public function weekNumbers(): array
    {
        $period = now()
            ->subWeeks(5)
            ->weeksUntil(now());

        $data = [];
        foreach ($period as $date) {
            $data[] = [
                'week' => $date->weekOfYear,
                'year' => $date->year,
            ];
        }

        return $data;
    }

    public function quarters(): array
    {
        $period = now()
            ->subMonths(12)
            ->monthsUntil(now(), 3);

        $data = [];
        foreach ($period as $date) {
            $data[] = [
                'quarter' => ceil($date->month / 3),
                'year' => $date->year,
            ];
        }

        array_shift($data);

        return $data;
    }

    /**
     * @throws Exception
     */
    public function get_dates_of_quarter(
        $quarter = 'current',
        $year = null,
        $format = null,
    ): array {
        if (!is_int($year)) {
            $year = (new DateTime())->format('Y');
        }
        $current_quarter = ceil((new DateTime())->format('n') / 3);
        $quarter =
            !is_int($quarter) || $quarter < 1 || $quarter > 4
                ? $current_quarter
                : $quarter;

        $start = new DateTime($year . '-' . (3 * $quarter - 2) . '-1 00:00:00');
        $end = new DateTime(
            $year .
                '-' .
                3 * $quarter .
                '-' .
                ($quarter == 1 || $quarter == 4 ? 31 : 30) .
                ' 23:59:59',
        );

        return [
            'start' => $format ? $start->format($format) : $start,
            'end' => $format ? $end->format($format) : $end,
        ];
    }

    public function getStartAndEndDate($week, $year): array
    {
        $dto = new DateTime();
        $dto->setISODate($year, $week);
        $ret['week_start'] = $dto->format('Y-m-d');
        $dto->modify('+6 days');
        $ret['week_end'] = $dto->format('Y-m-d');
        return $ret;
    }

    public function monthlyDownload($type, $year, $month)
    {
        if ($type === 'placed') {
            $query = 'created_at';
            $data = Order::whereYear($query, '=', $year)
                ->whereMonth($query, '=', $month)
                ->with('vehicle:id,make,orbit_number,model')
                ->with('vehicle.manufacturer:id,name')
                ->get();
        } elseif ($type === 'registered') {
            $query = 'vehicle_registered_on';
            $vehicle = Vehicle::whereYear($query, '=', $year)
                ->whereMonth($query, '=', $month)
                ->select('id')
                ->get();

            $data = Order::whereIn('vehicle_id', $vehicle)->get();
        } else {
            $data = Order::whereYear('completed_date', '=', $year)
                ->whereMonth('completed_date', '=', $month)
                ->with('vehicle:id,make,orbit_number,model')
                ->with('vehicle.manufacturer:id,name')
                ->get();
        }

        return Excel::download(
            new DashboardExports($data, $type),
            'monthly-' . $type . '-' . $month . '-' . $year . '.xlsx',
        );
    }

    /**
     * @throws Exception
     */
    public function quarterlyDownload($type, $year, $quarter)
    {
        $dates = $this->get_dates_of_quarter(intval($quarter), intval($year));

        $data = $this->returnDataForReports($type, $dates);

        return Excel::download(
            new DashboardExports($data, $type),
            'quarterly-' . $type . '-' . $quarter . '-' . $year . '.xlsx',
        );
    }

    public function weeklyDownload($type, $year, $week)
    {
        $dates = $this->getStartAndEndDate($week, $year);

        $data = $this->returnDataForReports($type, $dates);

        return Excel::download(
            new DashboardExports($data, $type),
            'weekly-' . $type . '-week-' . $week . '-' . $year . '.xlsx',
        );
    }

    /**
     * @param $type
     * @param array $dates
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection|_IH_Order_C|\LaravelIdea\Helper\App\_IH_Order_QB[]|Order[]
     */
    public function returnDataForReports($type, array $dates)
    {
        if ($type === 'placed') {
            $query = 'created_at';
            $data = Order::whereBetween($query, $dates)
                ->with('vehicle:id,make,orbit_number,model')
                ->with('vehicle.manufacturer:id,name')
                ->get();
        } elseif ($type === 'registered') {
            $query = 'vehicle_registered_on';
            $vehicle = Vehicle::whereBetween($query, $dates)
                ->select('id')
                ->get();

            $data = Order::whereIn('vehicle_id', $vehicle)->get();
        } else {
            $data = Order::whereBetween('completed_date', $dates)
                ->with('vehicle:id,make,orbit_number,model')
                ->with('vehicle.manufacturer:id,name')
                ->get();
        }

        return $data;
    }
}
