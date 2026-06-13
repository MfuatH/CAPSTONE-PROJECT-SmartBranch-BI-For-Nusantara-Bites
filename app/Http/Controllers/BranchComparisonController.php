<?php

namespace App\Http\Controllers;

use App\Models\ForecastResult;
use App\Models\Store;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BranchComparisonController extends Controller
{
    public function index(Request $request)
    {
        $branchId = session('branch_id');
        $branch = $branchId ? Store::find($branchId) : null;

        if (! $branch) {
            $emptyCollection = collect();

            return view('branch-comparison', [
                'branch' => null,
                'isBranchSelected' => false,
                'totalRevenue' => 0,
                'forecastRevenue' => 0,
                'forecastTrend' => 0,
                'chartCategories' => [],
                'historySeries' => [],
                'forecastSeries' => [],
                'topMenu' => $emptyCollection,
                'bottomMenu' => $emptyCollection,
                'miniChartCategories' => [],
                'miniChartData' => [],
                'expandedMenuData' => [],
                'topProductPrediction' => 'Belum ada AI Forecast',
            ]);
        }

        $lastTx = DB::table('transactions')
            ->where('store_id', $branchId)
            ->selectRaw('YEAR(transaction_date) as year, MONTH(transaction_date) as month')
            ->orderBy('transaction_date', 'desc')
            ->first();

        if ($lastTx) {
            $bulanIni = $lastTx->month;
            $tahunIni = $lastTx->year;
            $baseDate = Carbon::create($tahunIni, $bulanIni, 1);
        } else {
            $baseDate = Carbon::now()->startOfMonth();
            $bulanIni = $baseDate->month;
            $tahunIni = $baseDate->year;
        }

        $nextDate = $baseDate->copy()->addMonth();
        $bulanDepan = $nextDate->month;
        $tahunDepan = $nextDate->year;

        $totalRevenue = DB::table('transactions')
            ->join('products', 'transactions.product_id', '=', 'products.id')
            ->where('transactions.store_id', $branchId)
            ->whereMonth('transaction_date', $bulanIni)
            ->whereYear('transaction_date', $tahunIni)
            ->sum(DB::raw('transactions.qty * products.unit_price')) ?? 0;

        $forecastRevenue = DB::table('forecast_results')
            ->join('products', 'forecast_results.product_id', '=', 'products.id')
            ->where('forecast_results.store_id', $branchId)
            ->where('forecast_results.target_month', $bulanDepan)
            ->where('forecast_results.target_year', $tahunDepan)
            ->sum(DB::raw('forecast_results.predicted_qty * products.unit_price')) ?? 0;

        $forecastTrend = 0;
        if ($totalRevenue > 0) {
            $forecastTrend = round((($forecastRevenue - $totalRevenue) / $totalRevenue) * 100, 1);
        }

        $topMenu = DB::table('products')
            ->leftJoin('transactions', function ($join) use ($branchId, $bulanIni, $tahunIni) {
                $join->on('products.id', '=', 'transactions.product_id')
                    ->where('transactions.store_id', $branchId)
                    ->whereMonth('transactions.transaction_date', $bulanIni)
                    ->whereYear('transactions.transaction_date', $tahunIni);
            })
            ->select('products.id', 'products.detail as name', 'products.unit_price', DB::raw('COALESCE(SUM(transactions.qty), 0) as total_sold'))
            ->groupBy('products.id', 'products.detail', 'products.unit_price')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        $bottomMenu = DB::table('products')
            ->leftJoin('transactions', function ($join) use ($branchId, $bulanIni, $tahunIni) {
                $join->on('products.id', '=', 'transactions.product_id')
                    ->where('transactions.store_id', $branchId)
                    ->whereMonth('transactions.transaction_date', $bulanIni)
                    ->whereYear('transactions.transaction_date', $tahunIni);
            })
            ->select('products.id', 'products.detail as name', 'products.unit_price', DB::raw('COALESCE(SUM(transactions.qty), 0) as total_sold'))
            ->groupBy('products.id', 'products.detail', 'products.unit_price')
            ->orderBy('total_sold', 'asc')
            ->limit(5)
            ->get();

        $chartCategories = [];
        $historySeries = [];
        $forecastSeries = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = $baseDate->copy()->subMonths($i);
            $chartCategories[] = $date->translatedFormat('M y');

            $monthlyRevenue = DB::table('transactions')
                ->join('products', 'transactions.product_id', '=', 'products.id')
                ->where('transactions.store_id', $branchId)
                ->whereMonth('transaction_date', $date->month)
                ->whereYear('transaction_date', $date->year)
                ->sum(DB::raw('transactions.qty * products.unit_price')) ?? 0;

            $historySeries[] = (int) $monthlyRevenue;
            $forecastSeries[] = null;
        }

        $forecastSeries[5] = $historySeries[5];
        $chartCategories[] = $nextDate->translatedFormat('M y') . ' (AI)';
        $historySeries[] = null;
        $forecastSeries[] = (int) $forecastRevenue;

        $forecastByProduct = ForecastResult::join('products', 'forecast_results.product_id', '=', 'products.id')
            ->where('forecast_results.store_id', $branchId)
            ->where('forecast_results.target_month', $bulanDepan)
            ->where('forecast_results.target_year', $tahunDepan)
            ->select('products.detail as name', 'forecast_results.predicted_qty', 'products.unit_price')
            ->get()
            ->keyBy('name');

        $miniChartCategories = [];
        $miniChartWindow = [];
        for ($i = 4; $i >= 0; $i--) {
            $date = $baseDate->copy()->subMonths($i);
            $miniChartCategories[] = $date->translatedFormat('M');
            $miniChartWindow[] = $date;
        }

        $startWindow = $miniChartWindow[0]->copy()->startOfMonth();
        $endWindow = end($miniChartWindow)->copy()->endOfMonth();

        $monthlySales = DB::table('transactions')
            ->selectRaw('product_id, YEAR(transaction_date) as year, MONTH(transaction_date) as month, SUM(qty) as total_qty')
            ->where('store_id', $branchId)
            ->whereBetween('transaction_date', [$startWindow->toDateString(), $endWindow->toDateString()])
            ->whereIn('product_id', $topMenu->pluck('id')->all())
            ->groupBy('product_id', 'year', 'month')
            ->get()
            ->mapWithKeys(function ($item) {
                return ["{$item->product_id}_{$item->year}_{$item->month}" => $item];
            });

        $miniChartData = [];
        foreach ($topMenu as $product) {
            $series = [];
            foreach ($miniChartWindow as $date) {
                $key = "{$product->id}_{$date->year}_{$date->month}";
                $series[] = isset($monthlySales[$key]) ? (int) $monthlySales[$key]->total_qty : 0;
            }

            $miniChartData[] = [
                'name' => $product->name,
                'series' => $series,
            ];
        }

        $expandedMenuData = [];
        $expandedCategories = array_merge($miniChartCategories, [$nextDate->translatedFormat('M y')]);

        foreach ($miniChartData as $item) {
            $forecast = $forecastByProduct->get($item['name']);
            $predictedQty = $forecast ? (int) $forecast->predicted_qty : 0;
            $unitPrice = $forecast ? (int) $forecast->unit_price : 0;

            $expandedMenuData[$item['name']] = [
                'categories' => $expandedCategories,
                'actual' => array_merge($item['series'], [null]),
                'forecast' => array_merge(array_fill(0, count($item['series']), null), [$predictedQty]),
                'predicted_qty' => $predictedQty,
                'predicted_revenue' => $predictedQty * $unitPrice,
                'unit_price' => $unitPrice,
            ];
        }

        $topProductPrediction = $forecastByProduct->sortByDesc('predicted_qty')->keys()->first() ?? 'Belum ada AI Forecast';

        $isBranchSelected = true;

        return view('branch-comparison', compact(
            'branch',
            'isBranchSelected',
            'totalRevenue',
            'forecastRevenue',
            'forecastTrend',
            'chartCategories',
            'historySeries',
            'forecastSeries',
            'topMenu',
            'bottomMenu',
            'miniChartCategories',
            'miniChartData',
            'expandedMenuData',
            'topProductPrediction'
        ));
    }
}
