<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Dashboard;
use Helper;
use Auth;
use DB;

class ReportingController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    public function showReporting() {

        $weekly_sales = $this->getRecordsByWeek();
        $monthly_sales = $this->getRecordsByMonth();
        $quarterly_sales = $this->getRecordsByQuarter();


        $month_values = [];
        $weekly_values = [];
        $quarterly_values = [];
        $month_max = 0;
        $weekly_max = 0;
        $quarterly_max = 0;

        if (!empty($monthly_sales)) {
            foreach ($monthly_sales as $m_sales) {
                array_push($month_values, $m_sales->orders);
            }

            $month_max = (ceil(max($month_values) / 10) * 10) + 10;
        }
        if (!empty($weekly_sales)) {
            foreach ($weekly_sales as $w_sales) {

                array_push($weekly_values, $w_sales->orders);
            }

            $weekly_max = (ceil(max($weekly_values) / 10) * 10) + 10;
        }
        if (!empty($quarterly_sales)) {
            foreach ($quarterly_sales as $q_sales) {
                array_push($quarterly_values, $q_sales->orders);
            }

            $quarterly_max = (ceil(max($quarterly_values) / 10) * 10) + 10;
        }

        return view('report-track', [
            'in_stock' => Dashboard::GetOrdersById(1),
            'orders_placed' => Dashboard::GetOrdersById(2),
            'ready_for_delivery' => Dashboard::GetOrdersById(3),
            'factory_order' => Dashboard::GetOrdersById(4),
            'delivered' => Dashboard::GetOrdersById(6),
            'completed_orders' => Dashboard::GetOrdersById(7),
            'europe_vhc' => Dashboard::GetOrdersById(10),
            'uk_vhc' => Dashboard::GetOrdersById(11),
            'monthly_sales' => $monthly_sales,
            'month_max' => $month_max,
            'weekly_sales' => $weekly_sales,
            'weekly_max' => $weekly_max,
            'quarterly_sales' => $quarterly_sales,
            'quarterly_max' => $quarterly_max,
        ]);
    }

    public function showCustomReports() {
        return view('dashboard');
    }

    public static function getRecordsByMonth() {
        $vehicles = DB::table('order')
            ->select(DB::raw('MONTHNAME(created_at) as month, COUNT(id) as orders'))
            ->where('vehicle_status', '!=', 1)
            ->where("created_at",">", Carbon::now()->subMonths(6))
            ->groupByRaw("DATE_FORMAT(created_at, '%Y-%m')")
            ->orderBy('created_at', 'asc')
            ->get();

        return $vehicles;
    }

    public static function getRecordsByWeek() {
        $vehicles = DB::table('order')
            ->select(DB::raw('WEEK(created_at) as week, YEAR(created_at) as year, COUNT(id) as orders'))
            ->where('vehicle_status', '!=', 1)
            ->where("created_at",">", Carbon::now()->subMonths(6))
            ->groupByRaw("WEEK(created_at)")
            ->orderBy('created_at', 'asc')
            ->get();

        return $vehicles;
    }

    public static function getRecordsByQuarter() {
        $vehicles = DB::table('order')
            ->select(DB::raw('QUARTER(created_at) as quarter, YEAR(created_at) as year, COUNT(id) as orders'))
            ->where('vehicle_status', '!=', 1)
            ->where("created_at",">", Carbon::now()->subMonths(6))
            ->groupByRaw("QUARTER(created_at)")
            ->orderBy('created_at', 'asc')
            ->get();

        return $vehicles;
    }

    public function executeReportDownload(){
        $headers = [
            'MANUFACTURER',
            'MODEL',
            'CAR OR COMMERCIAL',
            'REGISTRATION NUMBER',
            'CHASSIS NUMBER',
            'REGISTRATION DATE',
            'BROKER',
            'DEALERSHIP',
            'LIST PRICE',
            'DELIVERY DATE',
            'FORD DEAL ID',
            'FINANCE COMPANY',
        ];

        //dump($headers);


        $orders = Order::whereIn('vehicle_status', [2,7])->get();
        $orderItems = [];

        /** @var Order $order */
        foreach ($orders as $order) {
            $orderItems[$order->id] = [
                $order->vehicle_make, //0
                $order->vehicle_model, //1
                $order->vehicle_type, //2
                $order->vehicle_reg, //3
                $order->chassis, //4
                $order->vehicle_registered_on ? $order->vehicle_registered_on->format('d/m/Y') : '', //5
                $order->broker ? \App\Helpers\Helper::getCompanyName($order->broker) : '', //6
                $order->dealership ? Helper::getCompanyName($order->dealership) : '', //7
                $order->list_price,
                $order->delivery_date ? $order->delivery_date->format('d/m/Y') : '',
                $order->holding_code,
                $order->invoice_company ? $order->invoice_company_details->name : '',//$order->
            ];
        }

        $callback = function() use ($headers, $orderItems ) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);
            foreach ($orderItems as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=report_download-" . date('Y-m-d') . ".csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        return response()->stream($callback, 200, $headers);
    }
}
