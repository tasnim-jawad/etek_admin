<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Modules\SalesManagement\SalesOrder\Models\Model as SalesOrderModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('admin-login', [App\Http\Controllers\Auth\LoginController::class, 'login']);


Route::get('/monthly-sales',function(){
    $currentMonth = Carbon::now()->month;
    $currentYear = Carbon::now()->year;
    $lastMonth = $currentMonth - 1;
    $current_month_sales = SalesOrderModel::whereMonth('date',$currentMonth)
                                            ->whereYear('date',$currentYear)
                                            ->sum('total');
    $last_month_sales = SalesOrderModel::whereMonth('date',$lastMonth)
                                            ->whereYear('date',$currentYear)
                                            ->sum('total');

    if ($last_month_sales > 0) {
        $percentage_change = (($current_month_sales - $last_month_sales) / $last_month_sales) * 100;
    } else {
        $percentage_change = $current_month_sales > 0 ? 100 : 0;
    }

    $sales_before_current_month = SalesOrderModel::whereMonth('date','<',$currentMonth)
                                            ->whereYear('date','<=',$currentYear)
                                            ->sum('total');
    return response()->json([
        'status' => 'success',
        'current_month_sales' => $current_month_sales,
        'last_month_sales' => $last_month_sales,
        'percentage_change' => $percentage_change,
        'sales_before_current_month' => $sales_before_current_month,
    ]);
});


Route::get('/daily-sales',function(){
    $today = Carbon::now()->toDateString();
    $yesterday = Carbon::yesterday()->toDateString();

    $today_sales = SalesOrderModel::where('date',$today)->sum('total');
    $yesterday_sales = SalesOrderModel::where('date',$yesterday)->sum('total');

    if ($yesterday_sales > 0) {
        $percentage_change = (($today_sales - $yesterday_sales) / $yesterday_sales) * 100;
    } else {
        $percentage_change = $today_sales > 0 ? 100 : 0;
    }

    dd($today_sales,$yesterday_sales,$percentage_change."% sales incres");

    return response()->json([
        'status' => 'success',
        'today_sales' => $today_sales,
        'yesterday_sales' => $yesterday_sales,
        'percentage_change' => $percentage_change,
    ]);
});

Route::get('/daily-profit',function(){
    $today = Carbon::now()->toDateString();
    $yesterday = Carbon::yesterday()->toDateString();

    $today_sales_purchase_price = DB::table('sales_order_products')->whereDate('created_at',$today)->sum('purchase_price');
    $today_sales_total = DB::table('sales_order_products')->whereDate('created_at',$today)->sum('total');
    $today_profit = $today_sales_total -$today_sales_purchase_price;

    $yesterday_sales_purchase_price = DB::table('sales_order_products')->whereDate('created_at',$yesterday)->sum('purchase_price');
    $yesterday_sales_total = DB::table('sales_order_products')->whereDate('created_at',$yesterday)->sum('total');
    $yesterday_profit = $yesterday_sales_total -$yesterday_sales_purchase_price;

    if ($yesterday_profit > 0) {
        $percentage_change = (($today_profit - $yesterday_profit) / $yesterday_profit) * 100;
    } else {
        $percentage_change = $today_profit > 0 ? 100 : 0;
    }

    return response()->json([
        'status' => 'success',
        'today_profit' => $today_profit,
        'yesterday_profit' => $yesterday_profit,
        'percentage_change' => $percentage_change,
    ]);
});

Route::get('/daily-product-sales',function(){
    $today = Carbon::now()->toDateString();
    $yesterday = Carbon::yesterday()->toDateString();

    $today_product_sales = DB::table('sales_order_products')->whereDate('created_at',$today)->sum('qty');
    $yesterday_product_sales = DB::table('sales_order_products')->whereDate('created_at',$yesterday)->sum('qty');

    if ($yesterday_product_sales > 0) {
        $percentage_change = (($today_product_sales - $yesterday_product_sales) / $yesterday_product_sales) * 100;
    } else {
        $percentage_change = $today_product_sales > 0 ? 100 : 0;
    }
    dd($today_product_sales,$yesterday_product_sales,$percentage_change);
    return response()->json([
        'status' => 'success',
        'today_product_sales' => $today_product_sales,
        'yesterday_product_sales' => $yesterday_product_sales,
        'percentage_change' => $percentage_change,
    ]);
});
