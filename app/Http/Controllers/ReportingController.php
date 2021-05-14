<?php

namespace App\Http\Controllers;

use App\OrderLegacy;
use App\Vehicle;
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

        foreach ($monthly_sales as $vehicle) {
		    $month_max[] = $vehicle->data;
	    }

        foreach ($weekly_sales as $vehicle) {
        	$weekly_max[] = $vehicle->data;
        }

        foreach ($quarterly_sales as $vehicle) {
        	$quarterly_max[] = $vehicle->data;
        }

	    $monthmax = ( isset ($month_max) ? max($month_max) + 1 : 5);

	    $weekmax = ( isset ($weekly_max) ? max($weekly_max) + 1 : 5);

	    $quartermax = ( isset ($quarterly_max) ? max($quarterly_max) + 1 : 5);

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
            'month_max' => $monthmax,
            'weekly_sales' => $weekly_sales,
            'weekly_max' => $weekmax,
            'quarterly_sales' => $quarterly_sales,
            'quarterly_max' => $quartermax,
        ]);
    }

    public static function getRecordsByMonth() {

	    $vehicles = Vehicle::select(
		    \Illuminate\Support\Facades\DB::raw('count(id) as `data`'),
		    DB::raw("DATE_FORMAT(created_at, '%M') month_label"),
		    DB::raw('YEAR(created_at) year, MONTH(created_at) month'))
		    ->groupby('year', 'month')
		    ->where('vehicle_status', '<>', 1)
		    ->where('created_at', '>', Carbon::now()->subMonths(6))
		    ->get();

        return $vehicles;
    }

    public static function getRecordsByWeek() {

	    $vehicles = Vehicle::select(
		    \Illuminate\Support\Facades\DB::raw('count(id) as `data`'),
		    DB::raw("DATE_FORMAT(created_at, 'week %v %Y') label"),
		    DB::raw('WEEK(created_at) week, Year(created_at) year'))
		    ->groupby('year', 'week')
		    ->where('vehicle_status', '<>', 1)
		    ->where('created_at', '>', Carbon::now()->subWeeks(6))
		    ->get();

        return $vehicles;
    }

    public static function getRecordsByQuarter() {

	    $vehicles = Vehicle::select(
		    \Illuminate\Support\Facades\DB::raw('count(id) as `data`'),
		    DB::raw('QUARTER(created_at) quarter, YEAR(created_at) year'))
		    ->groupby('quarter', 'year')
		    ->where('vehicle_status', '<>', 1)
		    ->where('created_at', '>', Carbon::now()->subYear(1))
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


        $orders = OrderLegacy::whereIn('vehicle_status', [2,7])->get();
        $orderItems = [];

        /** @var OrderLegacy $order */
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
