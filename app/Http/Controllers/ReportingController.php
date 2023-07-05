<?php

namespace App\Http\Controllers;

use App\Exports\FinanceExports;
use App\Exports\RegisteredExports;
use App\Exports\UniversalExport;
use App\Models\Order;
use App\Models\Vehicle;
use Carbon\Carbon;
use Dashboard;
use DateTime;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use LaravelIdea\Helper\App\_IH_Vehicle_C;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

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

    /**
     * Show the reporting page
     * @throws Exception
     */
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

        return view('reporting.reporting', [
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
            'registeredMonths' => $this->registeredMonths(),
            'registeredQuarters' => $this->registeredQuarters(),
            'financeMonths' => $this->financeMonths(),
        ]);
    }

    /**
     * Get a collection of orders that were created in the last 6 months and count them.
     * @return Collection
     */
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

    /**
     * Get the orders which have been created in the last 6 weeks and count them
     * @return Collection
     * @throws Exception
     */
    public function getRecordsByWeek(): Collection
    {
        $weeks = $this->weekNumbers();

        $week_array = [];

        foreach ($weeks as $week) {
            $dates = $this->getStartAndEndDate($week['year'], $week['week']);
            $data = Order::whereBetween('created_at', $dates)->get();
            $week['data'] = count($data);
            $week['label'] = 'week ' . $week['week'] . ' ' . $week['year'];
            $week_array[] = $week;
        }

        return collect($week_array);
    }

    /**
     * Get the orders which have been created in the last 4 annual quarters and count them
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

    /**
     * Get the Vehicles which have been registered in the last 6 months
     * @return Collection
     */
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

    /**
     * Get the vehicles which have been registered in the last 6 weeks
     * @return Collection
     * @throws Exception
     */
    public function getRegisteredByWeekly(): Collection
    {
        $weeks = $this->weekNumbers();

        $week_array = [];

        foreach ($weeks as $week) {
            $dates = $this->getStartAndEndDate($week['year'], $week['week']);
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
     * Get the vehicles which have been registered in the last 4 annual quarters
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

    /**
     * Get a count of the orders which have been completed in the last 6 months
     * @return Collection
     */
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

    /**
     * Get a count of the Orders which have been completed in the last 6 weeks.
     * @return Collection
     * @throws Exception
     */
    public function getCompletedByWeekly(): Collection
    {
        $weeks = $this->weekNumbers();

        $week_array = [];

        foreach ($weeks as $week) {
            $dates = $this->getStartAndEndDate($week['year'], $week['week']);
            $data = Order::whereBetween('completed_date', $dates)->get();
            $week['data'] = count($data);
            $week['label'] = 'week ' . $week['week'] . ' ' . $week['year'];
            $week_array[] = $week;
        }

        return collect($week_array);
    }

    /**
     * Get a count of the orders which have been completed in the last 4 annual quarters
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

    /**
     * Get the vehicles which have been registered per quarter since the website started collecting information
     * @return array
     */
    public function registeredQuarters()
    {
        $start_date = $this->registeredMonths()[2021][0]['label'];
        $start = Carbon::createFromFormat('F Y', $start_date)->monthsUntil(
            now(),
            3,
        );
        $data = [];
        foreach ($start as $date) {
            $data[] = [
                'quarter' => ceil($date->month / 3),
                'year' => $date->year,
            ];
        }
        return $data;
    }

    /**
     * Get the renewal dates report for 6 months before the renewal date
     * @return Collection
     */
    public function financeMonths()
    {
        $data = Order::all();
        return $data
            ->pluck('renewal_date')
            ->map(function ($item) {
                return Carbon::parse($item)
                    ->subMonths(6)
                    ->format('Y m');
            })
            ->sort()
            ->filter(function ($value) {
                return $value !== '-0001 11';
            })
            ->map(function ($item) {
                return ['label' => Carbon::createFromFormat('Y m', $item)->format('F Y'), 'year' => Carbon::createFromFormat('Y m', $item)->format('Y')];
            })
            ->unique()
            ->groupBy('year')
            ->toArray();
    }

    /**
     * Get the vehicles which have been registered each month since the website started collecting information.
     * @return mixed
     */
    public function registeredMonths()
    {
        $data = Vehicle::where(function ($query) {
            $query
                ->where('vehicle_status', '7')
                ->orWhere('vehicle_status', '6')
                ->orWhere('vehicle_status', '15');
        })->get();
        $data1 = $data->pluck('vehicle_registered_on');
        $data2 = $data->pluck('order.delivery_date');
        $data = $data1->merge($data2);
        return $data
            ->map(function ($item) {
                return Carbon::parse($item)->format('Y m');
            })
            ->sort()
            ->filter(function ($value) {
                return $value !== '-0001 11';
            })
            ->map(function ($item) {
                return ['label' => Carbon::createFromFormat('Y m', $item)->format('F Y'), 'year' => Carbon::createFromFormat('Y m', $item)->format('Y')];
            })
            ->unique()
            ->groupBy('year')
            ->toArray();
    }

    /**
     * Get the names of the last 6 months with the year
     * @return array
     */
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

    /**
     * Get the number of the last 6 weeks, along with the year
     * @return array
     */
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

    /**
     * Get the last 4 quarters based on the current date
     * @return array
     */
    public function quarters(): array
    {
        $period = now()
            ->subMonths(12)
            ->monthsUntil(now(), 3);

        $data = [];
        foreach ($period as $date) {
            $data[] = [
                'quarter' => intval(ceil($date->month / 3)),
                'year' => $date->year,
            ];
        }

        array_shift($data);

        return $data;
    }

    /**
     * Get the start and end date of a quarter when given a number and a year.
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
        switch (strtolower($quarter)) {
            case 'this':
            case 'current':
                $quarter = ceil((new DateTime())->format('n') / 3);
                break;

            case 'previous':
                $year = (new DateTime())->format('Y');
                if ($current_quarter == 1) {
                    $quarter = 4;
                    $year--;
                } else {
                    $quarter = $current_quarter - 1;
                }
                break;

            case 'first':
                $quarter = 1;
                break;

            case 'last':
                $quarter = 4;
                break;

            default:
                $quarter =
                    !is_int($quarter) || $quarter < 1 || $quarter > 4
                        ? $current_quarter
                        : $quarter;
                break;
        }
        if ($quarter === 'this') {
            $quarter = ceil((new DateTime())->format('n') / 3);
        }
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

    /**
     * Get the start and end date of a period of time, either a week, month or quarter
     * @throws Exception
     */
    public function getStartAndEndDate(
        $year,
        $period,
        $type = 'week',
    ): array|null {
        if ($type === 'week') {
            $dto = Carbon::now();
            $dto->setISODate($year, $period);
            $ret['week_start'] = $dto->startOfWeek()->format('Y-m-d');
            $ret['week_end'] = $dto->endOfWeek()->format('Y-m-d');
            return $ret;
        } elseif ($type === 'month') {
            $dto = Carbon::createFromFormat('m Y', $period . ' ' . $year);
            $ret['month_start'] = $dto->startOfMonth()->format('Y-m-d');
            $ret['month_end'] = $dto->endOfMonth()->format('Y-m-d');
            return $ret;
        } elseif ($type === 'quarter') {
            return $this->get_dates_of_quarter(intval($period), intval($year));
        }
        return null;
    }

    /**
     * When given a report type and a selection of dates, prepare the data for a report.
     * @param string $type
     * @param array $dates
     * @return _IH_Vehicle_C|Builder|\Illuminate\Database\Eloquent\Collection|Vehicle
     */
    public function returnDataForReports(string $type, array $dates)
    {
        if ($type === 'placed') {
            $data = Vehicle::whereHas('order', function ($query) use ($dates) {
                $query->whereBetween('created_at', $dates);
            })->get();
        } elseif ($type === 'registered') {
            $data = Vehicle::whereBetween(
                'vehicle_registered_on',
                $dates,
            )->get();
        } else {
            $data = Vehicle::whereHas('order', function ($query) use ($dates) {
                $query->whereBetween('completed_date', $dates);
            })->get();
        }
        return $data;
    }

    /**
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('reporting.index');
    }

    /**
     * Get the vehicle information for the Registered - Month report.
     * @param $month
     * @param $year
     * @return BinaryFileResponse
     */
    public function registeredMonth($month, $year)
    {
        $registered = Vehicle::where(function ($query) use ($month, $year) {
            $query
                ->whereMonth('vehicle_registered_on', $month)
                ->whereYear('vehicle_registered_on', $year);
        })->get();
        $delivery = Vehicle::whereHas('order', function ($query) use (
            $month,
            $year,
        ) {
            $query->where(function ($query) use ($month, $year) {
                $query
                    ->whereMonth('delivery_date', $month)
                    ->whereYear('delivery_date', $year);
            });
        })
            ->whereNull('vehicle_registered_on')
            ->get();
        $vehicles = $registered->merge($delivery);
        return Excel::download(
            new RegisteredExports($vehicles),
            'monthly-registered-' . $month . '-' . $year . '.xls',
        );
    }

    /**
     * When given a month and a year, collect the information for the renewal report.
     * @param $month
     * @param $year
     * @return BinaryFileResponse
     */
    public function financeMonth($month, $year)
    {
        $report_month = $month + 6;
        $orders = Order::where(function ($query) use ($report_month, $year) {
            $query
                ->whereMonth('renewal_date', $report_month)
                ->whereYear('renewal_date', $year);
        })->get();
        return Excel::download(
            new FinanceExports($orders),
            'monthly-finance-' . $month . '-' . $year . '.xls',
        );
    }

    /**
     * When given the type of report and dates, prepare the weekly report for downloading
     * @throws Exception
     */
    public function weeklyDownload($report, $year, $week)
    {
        $dates = $this->getStartAndEndDate($year, $week);
        $data = $this->returnDataForReports($report, $dates);
        return Excel::download(
            new UniversalExport($data),
            'weekly-' . $report . '-week-' . $week . '-' . $year . '.xls',
        );
    }

    /**
     * When given the type of report and dates, prepare the monthly report for downloading
     * @throws Exception
     */
    public function monthlyDownload($report, $year, $month)
    {
        $dates = $this->getStartAndEndDate($year, $month, 'month');
        $data = $this->returnDataForReports($report, $dates);
        return Excel::download(
            new UniversalExport($data),
            'monthly-' . $report . '-month-' . $month . '-' . $year . '.xls',
        );
    }

    /**
     * When given the type of report and dates, prepare the quarterly report for downloading
     * @throws Exception
     */
    public function quarterlyDownload($report, $year, $quarter)
    {
        $dates = $this->getStartAndEndDate($year, $quarter, 'quarter');
        $data = $this->returnDataForReports($report, $dates);
        return Excel::download(
            new UniversalExport($data),
            'quarterly-' . $report . '-Q' . $quarter . '-' . $year . '.xls',
        );
    }

    /**
     * Get the vehicle information for the Registered - Quarters report.
     * @throws Exception
     */
    public function registeredQuarter($quarter = null, $year = null)
    {
        if ($quarter) {
            $quarter = intval($quarter);
        } else {
            $quarter = 'current';
        }
        if ($year) {
            $year = intval($year);
        } else {
            $year = Carbon::now()->format('Y');
        }

        $dates = $this->get_dates_of_quarter($quarter, $year);

        $registered = Vehicle::where(function ($query) use ($dates) {
            $query->whereBetween('vehicle_registered_on', $dates);
        })->get();

        $delivery = Vehicle::whereHas('order', function ($query) use ($dates) {
            $query->where(function ($query) use ($dates) {
                $query->whereBetween('delivery_date', $dates);
            });
        })
            ->whereNull('vehicle_registered_on')
            ->get();

        $vehicles = $registered->merge($delivery);

        return Excel::download(
            new RegisteredExports($vehicles),
            'quarterly-registered-' . $quarter . '-' . $year . '.xls',
        );
    }
}
