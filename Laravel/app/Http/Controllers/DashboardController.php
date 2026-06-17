<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ForecastResult;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $lastTx = DB::table('transactions')
            ->selectRaw('YEAR(transaction_date) as year, MONTH(transaction_date) as month')
            ->orderBy('transaction_date', 'desc')
            ->first();

        if ($lastTx) {
            $bulanIni = $lastTx->month;
            $tahunIni = $lastTx->year;
            $baseDate = Carbon::create($tahunIni, $bulanIni, 1);
        } else {
            $bulanIni = 6;
            $tahunIni = 2023;
            $baseDate = Carbon::create(2023, 6, 1);
        }
        
        $bulanDepan = $baseDate->copy()->addMonth()->month;
        $tahunDepan = $baseDate->copy()->addMonth()->year;

        $totalRevenue = DB::table('transactions')
            ->join('products', 'transactions.product_id', '=', 'products.id')
            ->whereMonth('transaction_date', $bulanIni)
            ->whereYear('transaction_date', $tahunIni)
            ->sum(DB::raw('transactions.qty * products.unit_price')) ?? 0;

        $forecastRevenue = DB::table('forecast_results')
            ->join('products', 'forecast_results.product_id', '=', 'products.id')
            ->where('target_month', $bulanDepan)
            ->where('target_year', $tahunDepan)
            ->sum(DB::raw('forecast_results.predicted_qty * products.unit_price')) ?? 0;

        $forecastTrend = 0;
        if($totalRevenue > 0) {
            $forecastTrend = round((($forecastRevenue - $totalRevenue) / $totalRevenue) * 100, 1);
        }

        $branchSales = DB::table('transactions')
            ->join('products', 'transactions.product_id', '=', 'products.id')
            ->select('transactions.store_id', DB::raw('SUM(transactions.qty * products.unit_price) as total_sales'))
            ->whereMonth('transactions.transaction_date', $bulanIni)
            ->whereYear('transactions.transaction_date', $tahunIni)
            ->groupBy('transactions.store_id')
            ->get();

        $stores = DB::table('stores')->get();
        $branches = [];
        foreach ($stores as $store) {
            $salesData = $branchSales->firstWhere('store_id', $store->id);
            
            $branches[] = (object) [
                'id' => $store->id,
                'location' => $store->location,
                'total_sales' => $salesData ? (float) $salesData->total_sales : 0
            ];
        }
        $dataTokoSales = collect($branches)->sortByDesc('total_sales')->values();

        $bestBranchName = !empty($dataTokoSales) && $dataTokoSales[0]->total_sales > 0 
        ? "Cabang " . $dataTokoSales[0]->location 
        : "Belum ada transaksi";

        $lowMaterials = DB::table('raw_material_store')
            ->join('raw_materials', 'raw_material_store.raw_material_id', '=', 'raw_materials.id')
            ->join('stores', 'raw_material_store.store_id', '=', 'stores.id')
            ->select('raw_materials.name', 'stores.location as store', 'raw_material_store.current_stock', 'raw_material_store.minimum_stock')
            ->whereColumn('raw_material_store.current_stock', '<=', 'raw_material_store.minimum_stock')
            ->orderBy('raw_material_store.current_stock', 'asc')
            ->get();
            
        $stockAlertCount = $lowMaterials->count();

        $topMenu = DB::table('products')
            ->leftJoin('transactions', function($join) use ($bulanIni, $tahunIni) {
                $join->on('products.id', '=', 'transactions.product_id')
                     ->whereMonth('transactions.transaction_date', '=', $bulanIni)
                     ->whereYear('transactions.transaction_date', '=', $tahunIni);
            })
            ->select('products.detail as name', 'products.unit_price', DB::raw('COALESCE(SUM(transactions.qty), 0) as total_sold'))
            ->groupBy('products.id', 'products.detail', 'products.unit_price')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        $bottomMenu = DB::table('products')
            ->leftJoin('transactions', function($join) use ($bulanIni, $tahunIni) {
                $join->on('products.id', '=', 'transactions.product_id')
                     ->whereMonth('transactions.transaction_date', '=', $bulanIni)
                     ->whereYear('transactions.transaction_date', '=', $tahunIni);
            })
            ->select('products.detail as name', 'products.unit_price', DB::raw('COALESCE(SUM(transactions.qty), 0) as total_sold'))
            ->groupBy('products.id', 'products.detail', 'products.unit_price')
            ->orderBy('total_sold', 'asc')
            ->limit(5)
            ->get();

        $topPrediction = ForecastResult::join('products', 'forecast_results.product_id', '=', 'products.id')
            ->where('target_month', $bulanDepan)
            ->where('target_year', $tahunDepan)
            ->orderByDesc('predicted_qty')
            ->first();
        
        $topProductPrediction = $topPrediction ? $topPrediction->detail : 'Belum ada AI Forecast';

        $bundlePromos = [];
        $promoNames = ['Paket Nusantara Hemat', 'Combo Anti Boncos', 'Bundling Spesial'];
        for ($i = 0; $i < 3; $i++) {
            if(isset($topMenu[$i]) && isset($bottomMenu[$i])) {
                $hargaNormal = $topMenu[$i]->unit_price + $bottomMenu[$i]->unit_price;
                $hargaBundle = $hargaNormal * 0.90;
                
                $bundlePromos[] = [
                    'title'  => $promoNames[$i] ?? 'Paket Hemat',
                    'pair'   => $topMenu[$i]->name . ' + ' . $bottomMenu[$i]->name,
                    'normal' => 'Rp ' . number_format($hargaNormal, 0, ',', '.'),
                    'bundle' => 'Rp ' . number_format($hargaBundle, 0, ',', '.'),
                    'uplift' => '+' . rand(12, 25) . '% Potensi Sales'
                ];
            }
        }

        $chartCategories = [];
        $historySeries = [];
        $forecastSeries = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = $baseDate->copy()->subMonths($i);
            $chartCategories[] = $date->translatedFormat('M y');

            $rev = DB::table('transactions')
                ->join('products', 'transactions.product_id', '=', 'products.id')
                ->whereMonth('transaction_date', $date->month)
                ->whereYear('transaction_date', $date->year)
                ->sum(DB::raw('transactions.qty * products.unit_price')) ?? 0;
            
            $historySeries[] = (int) $rev;
            $forecastSeries[] = null;
        }

        $forecastSeries[5] = $historySeries[5]; 

        $nextDate = $baseDate->copy()->addMonth();
        $chartCategories[] = $nextDate->translatedFormat('M y') . ' (AI)';
        $historySeries[] = null;
        $forecastSeries[] = (int) $forecastRevenue;

        return view('dashboard', array_merge(
            compact(
                'totalRevenue', 
                'forecastTrend', 
                'forecastRevenue', 
                'bestBranchName', 
                'stockAlertCount', 
                'lowMaterials', 
                'topMenu', 
                'bottomMenu', 
                'topProductPrediction', 
                'bundlePromos',
                'chartCategories', 
                'historySeries', 
                'forecastSeries'
            ),
            ['dataTokoSales' => $dataTokoSales]
        ));
    }
}