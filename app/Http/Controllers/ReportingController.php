<?php

namespace App\Http\Controllers;

use App\Exports\CompletedBondsExport;
use App\Exports\FinanceExports;
use App\Exports\OutstandingBondsDownload;
use App\Exports\RegisteredExports;
use App\Exports\UniversalExport;
use App\Models\Bond;
use App\Models\Order;
use App\Models\Vehicle;
use Carbon\Carbon;
use Dashboard;
use DateTime;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
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
        $outstanding_bonds = Bond::whereNull('refund_date')
            ->whereNull('refund_value')
            ->exists();

        $groupedBonds = Bond::whereNotNull('refund_value')
            ->whereNotNull('refund_date')
            ->latest('payment_date')
            ->get()
            ->groupBy(function (Bond $bond) {
                return $bond->payment_date->format('Y');
            });

        return view('reporting.reporting', [
            'outstanding_bonds' => $outstanding_bonds,
            'grouped_bonds' => $groupedBonds,
            'in_stock' => Dashboard::GetOrdersByVehicleStatus(1),
            'orders_placed' => Dashboard::GetOrdersByVehicleStatus(2),
            'ready_for_delivery' => Dashboard::GetOrdersByVehicleStatus(3),
            'factory_order' => Dashboard::GetOrdersByVehicleStatus(4),
            'delivered' => Dashboard::GetOrdersByVehicleStatus(6),
            'completed_orders' => Dashboard::GetOrdersByVehicleStatus(7),
            'europe_vhc' => Dashboard::GetOrdersByVehicleStatus(10),
            'uk_vhc' => Dashboard::GetOrdersByVehicleStatus(11),
            'monthly_sales' => $this->getRecordsByMonth(),
            'monthly_registered' => $this->getRegisteredByMonth(),
            'monthly_completed' => $this->getCompletedByMonth(),
            'weekly_sales' => $this->getRecordsByWeek(),
            'weekly_registered' => $this->getRegisteredByWeekly(),
            'weekly_completed' => $this->getCompletedByWeekly(),
            'quarterly_sales' => $this->getRecordsByQuarter(),
            'quarterly_registered' => $this->getRegisteredByQuarter(),
            'quarterly_completed' => $this->getCompletedByQuarter(),
            'registeredMonths' => $this->registeredMonths(),
            'registeredQuarters' => $this->registeredQuarters(),
            'financeMonths' => $this->financeMonths(),
        ]);
    }

    private function getAggregateByMonth(string $modelClass, string $dateColumn) : Collection
    {
        $months = $this->monthNames();
        $startDate = now()->subMonths(5)->startOfMonth()->toDateTimeString();
        $endDate = now()->endOfMonth()->toDateTimeString();

        $counts = $modelClass::query()
            ->selectRaw("DATE_FORMAT({$dateColumn}, '%Y-%m') as ym, COUNT(*) as aggregate")
            ->whereBetween($dateColumn, [$startDate, $endDate])
            ->groupBy('ym')
            ->pluck('aggregate', 'ym');

        return collect($months)->map(function ($month) use ($counts) {
            $key = sprintf('%04d-%02d', $month['year'], $month['month']);
            $month['data'] = $counts->get($key, 0);
            return $month;
        });

    }

    /**
     * Get a collection of orders that were created in the last 6 months and count them.
     * @return Collection
     */
    public function getRecordsByMonth(): Collection
    {
        return $this->getAggregateByMonth(Order::class, 'created_at');
    }

    /**
     * Get the Vehicles which have been registered in the last 6 months
     * @return Collection
     */
    public function getRegisteredByMonth(): Collection
    {
        return $this->getAggregateByMonth(Vehicle::class, 'vehicle_registered_on');
    }

    /**
     * Get a count of the orders which have been completed in the last 6 months
     * @return Collection
     */
    public function getCompletedByMonth(): Collection
    {
        return $this->getAggregateByMonth(Order::class, 'completed_date');
    }

    /**
     * Get the orders which have been created in the last 6 weeks and count them
     * @return Collection
     * @throws Exception
     */
    public function getRecordsByWeek(): Collection
    {
        $weeks = $this->weekNumbers();
        $start = Carbon::now()->setISODate($weeks[0]['year'], $weeks[0]['week'])->startOfWeek();
        $end = Carbon::now()->setISODate(end($weeks)['year'], end($weeks)['week'])->endOfWeek();

        $orders = Order::whereBetween('created_at', [$start, $end])->select('created_at')->get();

        return collect($weeks)->map(function ($week) use ($orders) {
            $dates = $this->getStartAndEndDate($week['year'], $week['week']);
            $count = $orders->filter(function ($order) use ($dates) {
                return $order->created_at >= $dates['week_start'] && $order->created_at <= $dates['week_end'];
            })->count();

            $week['data'] = $count;
            $week['label'] = 'week ' . $week['week'] . ' ' . $week['year'];
            return $week;
        });

    }

    /**
     * Get the vehicles which have been registered in the last 6 weeks
     * @return Collection
     * @throws Exception
     */
    public function getRegisteredByWeekly(): Collection
    {
        $weeks = $this->weekNumbers();
        $start = Carbon::now()->setISODate($weeks[0]['year'], $weeks[0]['week'])->startOfWeek();
        $end = Carbon::now()->setISODate(end($weeks)['year'], end($weeks)['week'])->endOfWeek();

        $vehicles = Vehicle::whereBetween('vehicle_registered_on', [$start, $end])
            ->select('vehicle_registered_on')
            ->get();

        return collect($weeks)->map(function ($week) use ($vehicles) {
            $dates = $this->getStartAndEndDate($week['year'], $week['week']);
            $count = $vehicles->filter(function ($v) use ($dates) {
                return $v->vehicle_registered_on >= $dates['week_start'] && $v->vehicle_registered_on <= $dates['week_end'];
            })->count();

            $week['data'] = $count;
            $week['label'] = 'week ' . $week['week'] . ' ' . $week['year'];
            return $week;
        });
    }

    /**
     * Get a count of the Orders which have been completed in the last 6 weeks.
     * @return Collection
     * @throws Exception
     */
    public function getCompletedByWeekly(): Collection
    {
        $weeks = $this->weekNumbers();
        $start = Carbon::now()->setISODate($weeks[0]['year'], $weeks[0]['week'])->startOfWeek();
        $end = Carbon::now()->setISODate(end($weeks)['year'], end($weeks)['week'])->endOfWeek();

        $orders = Order::whereBetween('completed_date', [$start, $end])
            ->select('completed_date')
            ->get();

        return collect($weeks)->map(function ($week) use ($orders) {
            $dates = $this->getStartAndEndDate($week['year'], $week['week']);
            $count = $orders->filter(function ($order) use ($dates) {
                return $order->completed_date >= $dates['week_start'] && $order->completed_date <= $dates['week_end'];
            })->count();

            $week['data'] = $count;
            $week['label'] = 'week ' . $week['week'] . ' ' . $week['year'];
            return $week;
        });
    }

    /**
     * Get the orders which have been created in the last four annual quarters and count them
     * @throws Exception
     */
    public function getRecordsByQuarter(): Collection
    {
        return $this->getQuarterlyAggregates(Order::class, 'created_at');
    }

    /**
     * Get the vehicles which have been registered in the last four annual quarters
     * @throws Exception
     */
    public function getRegisteredByQuarter(): Collection
    {
        return $this->getQuarterlyAggregates(Vehicle::class, 'vehicle_registered_on');
    }

    /**
     * Get a count of the orders which have been completed in the last four annual quarters
     * @throws Exception
     */
    public function getCompletedByQuarter(): Collection
    {
       return $this->getQuarterlyAggregates(Order::class, 'completed_date');
    }

    /**
     * @throws Exception
     */
    private function getQuarterlyAggregates(string $modelClass, string $dateColumn) : Collection
    {
        $quarters = $this->quarters();
        $array = [];

        foreach ($quarters as $quarter) {
            $dates = $this->get_dates_of_quarter($quarter['quarter'], $quarter['year']);
            $count = $modelClass::whereBetween($dateColumn, [$dates['start'], $dates['end']])->count();

            $quarter['data'] = $count;
            $quarter['label'] = 'Q' . $quarter['quarter'] . ' ' . $quarter['year'];
            $array[] = $quarter;
        }

        return collect($array);
    }

    /**
     * Get the renewal dates report for 6 months before the renewal date
     * @return Collection
     */
    public function financeMonths()
    {
        return Cache::remember('finance_months_dropdown', now()->addDay(), function () {
            $months = DB::table('orders')
                ->whereNotNull('renewal_date')
                ->where('renewal_date', '>', '1000-01-01')
                ->selectRaw("DISTINCT DATE_FORMAT(DATE_SUB(renewal_date, INTERVAL 6 MONTH), '%Y-%m') as ym")
                ->orderBy('ym')
                ->pluck('ym');

            $result = [];
            foreach ($months as $ym) {
                [$year, $month] = explode('-', $ym);
                $date = Carbon::createFromDate((int)$year, (int)$month, 1);

                $result[$year][] = [
                    'label' => $date->format('F Y'),
                    'year'  => $year,
                ];
            }

            return $result;
        });
    }

    /**
     * Get the vehicles which have been registered each month since the website started collecting information.
     * @return array
     */
    public function registeredMonths()
    {
        return Cache::remember('registered_months_dropdown', now()->addDay(), function () {
            $registeredDates = Vehicle::whereIn('vehicle_status', [6, 7, 15])
                ->whereNotNull('vehicle_registered_on')
                ->where('vehicle_registered_on', '>', '1000-01-01')
                ->selectRaw("DATE_FORMAT(vehicle_registered_on, '%Y-%m-01') as formatted_date")
                ->distinct()
                ->pluck('formatted_date');

            $orderDeliveryDates = Order::whereHas('vehicle', function ($query) {
                $query->whereIn('vehicle_status', [6, 7, 15]);
            })
                ->whereNotNull('delivery_date')
                ->where('delivery_date', '>', '1000-01-01')
                ->selectRaw("DATE_FORMAT(delivery_date, '%Y-%m-01') as formatted_date")
                ->distinct()
                ->pluck('formatted_date');

            $dates = $registeredDates->merge($orderDeliveryDates)->unique()->sort();

            $grouped = [];
            foreach ($dates as $dateStr) {
                $year = substr($dateStr, 0, 4);
                $formatted = str_replace('-', ' ', $dateStr);
                $grouped[$year][] = $formatted;
            }

            return $grouped;
        });
    }


    /**
     * Get the vehicles which have been registered per quarter since the website started collecting information
     * @return array
     */
    public function registeredQuarters()
    {
        $registeredMonths = collect($this->registeredMonths())->flatten(1);

        if ($registeredMonths->isEmpty()) {
            return [];
        }

        $latestDateStr = $registeredMonths->last();
        $firstDateStr = $registeredMonths->first();

        $latestMonth = Carbon::createFromFormat('Y m d', $latestDateStr)->addMonths(3);
        $start = Carbon::createFromFormat('Y m d', $firstDateStr)->monthsUntil($latestMonth, 3);

        $data = [];
        foreach ($start as $date) {
            $data[] = [
                'quarter' => ceil($date->month / 3),
                'year'    => $date->year,
            ];
        }
        return $data;
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
     * Get the last four quarters based on the current date
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
     * @return Vehicle
     */
    public function returnDataForReports(string $type, array $dates)
    {
        $start = $dates['start'] ?? $dates['week_start'] ?? $dates['month_start'];
        $end = $dates['end'] ?? $dates['week_end'] ?? $dates['month_end'];

        if ($type === 'placed') {
            return Vehicle::whereHas('order', function ($query) use ($start, $end) {
                $query->whereBetween('created_at', [$start, $end]);
            });
        } elseif ($type === 'registered') {
            return Vehicle::whereBetween('vehicle_registered_on', [$start, $end]);
        } else {
            return Vehicle::whereHas('order', function ($query) use ($start, $end) {
                $query->whereBetween('completed_date', [$start, $end]);
            });
        }
    }

    /**
     * @return Factory|View
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
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function registeredMonth($month, $year)
    {
        $start = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $end = $start->copy()->endOfMonth();

        $registered = Vehicle::whereBetween('vehicle_registered_on', [$start, $end])->get();
        $delivery = Vehicle::whereHas('order', function ($query) use ($start, $end) {
            $query->whereBetween('delivery_date', [$start, $end]);
        })
            ->whereNull('vehicle_registered_on')
            ->get();

        return Excel::download(
            new RegisteredExports($registered->merge($delivery)),
            "monthly-registered-{$month}-{$year}.xls"
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
        $start = Carbon::createFromDate($year, $report_month, 1)->startOfMonth();
        $end = $start->copy()->endOfMonth();

        $orders = Order::whereBetween('renewal_date', [$start, $end])->get();

        return Excel::download(
            new FinanceExports($orders),
            "monthly-finance-{$month}-{$year}.xls"
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
        return Excel::download(new UniversalExport($data), "weekly-{$report}-week-{$week}-{$year}.xls");
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
        $quarter = $quarter ? (int)$quarter : 'current';
        $year = $year ? (int)$year : (int)Carbon::now()->format('Y');

        $dates = $this->get_dates_of_quarter($quarter, $year);

        $registered = Vehicle::whereBetween('vehicle_registered_on', [$dates['start'], $dates['end']])->get();
        $delivery = Vehicle::whereHas('order', function ($query) use ($dates) {
            $query->whereBetween('delivery_date', [$dates['start'], $dates['end']]);
        })->whereNull('vehicle_registered_on')->get();

        return Excel::download(
            new RegisteredExports($registered->merge($delivery)),
            "quarterly-registered-{$quarter}-{$year}.xls"
        );
    }

    /**
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function completedBondsDownload($year = null)
    {
        $year = $year ? (int) $year : (int)Carbon::now()->format('Y');
        $bonds = Bond::whereNotNull('refund_value')
            ->whereNotNull('refund_date')
            ->whereYear($year, '=', 'payment_date')
            ->latest('payment_date');

        return Excel::download(
            new CompletedBondsExport($bonds), "completed-bonds-{$year}.xls"
        );

    }

    /**
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function outstandingBondsDownload()
    {
        $bonds = Bond::whereNull('refund_date')
            ->whereNull('refund_value')
            ->latest('payment_date');

        return Excel::download(new OutstandingBondsDownload($bonds), "outstanding-bonds.xls");
    }
}

