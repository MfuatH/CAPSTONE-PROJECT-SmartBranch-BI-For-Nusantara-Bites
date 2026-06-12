<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\RawMaterialController;
use App\Models\Product;
use App\Models\Store;
use App\Models\RawMaterial;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->middleware('throttle:5,1');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/', function () {
        $topMenu = Product::leftJoin('transactions', 'products.id', '=', 'transactions.product_id')
            ->select('products.id', 'products.detail as name', 'products.unit_price', DB::raw('COALESCE(SUM(transactions.qty), 0) as total_sold'))
            ->groupBy('products.id', 'products.detail', 'products.unit_price')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        $bottomMenu = Product::leftJoin('transactions', 'products.id', '=', 'transactions.product_id')
            ->select('products.id', 'products.detail as name', 'products.unit_price', DB::raw('COALESCE(SUM(transactions.qty), 0) as total_sold'))
            ->groupBy('products.id', 'products.detail', 'products.unit_price')
            ->orderBy('total_sold')
            ->limit(5)
            ->get();

        $branches = Store::leftJoin('transactions', 'stores.id', '=', 'transactions.store_id')
            ->leftJoin('products', 'transactions.product_id', '=', 'products.id')
            ->select('stores.id', 'stores.location', DB::raw('COALESCE(SUM(transactions.qty * products.unit_price), 0) as total_sales'))
            ->groupBy('stores.id', 'stores.location')
            ->orderByDesc('total_sales')
            ->get();

        $lowMaterials = DB::table('raw_material_store')
            ->join('raw_materials', 'raw_material_store.raw_material_id', '=', 'raw_materials.id')
            ->join('stores', 'raw_material_store.store_id', '=', 'stores.id')
            ->select('raw_materials.name', 'stores.location as store', 'raw_material_store.current_stock', 'raw_material_store.minimum_stock')
            ->orderBy('raw_material_store.current_stock')
            ->limit(5)
            ->get();

        $totalRevenue = DB::table('transactions')
            ->join('products', 'transactions.product_id', '=', 'products.id')
            ->select(DB::raw('COALESCE(SUM(transactions.qty * products.unit_price), 0) as revenue'))
            ->value('revenue');

        $forecastRevenue = round($totalRevenue * 1.1);
        $forecastTrend = $totalRevenue ? round((($forecastRevenue - $totalRevenue) / $totalRevenue) * 100, 1) : 0;
        $stockAlertCount = DB::table('raw_material_store')
            ->whereColumn('current_stock', '<=', 'minimum_stock')
            ->count();

        $bestBranch = $branches->first();
        $bestBranchName = $bestBranch ? $bestBranch->location : 'Tidak ada data';
        $topProductPrediction = $topMenu->first()?->name ?? 'N/A';

        $monthlyData = DB::table('transactions')
            ->join('products', 'transactions.product_id', '=', 'products.id')
            ->selectRaw('YEAR(transaction_date) as year, MONTH(transaction_date) as month, SUM(transactions.qty * products.unit_price) as total_sales')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $chartCategories = [];
        $historySeries = [];
        $forecastSeries = [];

        if ($monthlyData->isNotEmpty()) {
            foreach ($monthlyData as $row) {
                $chartCategories[] = Carbon::create($row->year, $row->month, 1)->format('M');
                $historySeries[] = (int) round($row->total_sales);
            }

            $forecastSeries = array_fill(0, count($historySeries), null);
            $lastRow = $monthlyData->last();
            $forecastBase = (int) round($lastRow->total_sales);

            for ($i = 1; $i <= 2; $i++) {
                $nextDate = Carbon::create($lastRow->year, $lastRow->month, 1)->addMonths($i);
                $chartCategories[] = $nextDate->format('M');
                $forecastSeries[] = (int) round($forecastBase * (1 + 0.08 * $i));
            }
        } else {
            $chartCategories = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags'];
            $historySeries = [4000, 4500, 5200, 5100, 6100, 6800, null, null];
            $forecastSeries = [null, null, null, null, null, 6800, 7400, 8100];
        }

        $bundlePromos = [];
        $bundleCount = min(3, $topMenu->count(), $bottomMenu->count());
        for ($i = 0; $i < $bundleCount; $i++) {
            $top = $topMenu[$i];
            $bottom = $bottomMenu[$i];
            $normalPrice = $top->unit_price + $bottom->unit_price;
            $bundlePrice = round($normalPrice * 0.85, 2);

            $bundlePromos[] = [
                'title' => "Promo {$top->name} + {$bottom->name}",
                'pair' => "{$top->name} + {$bottom->name}",
                'normal' => 'Rp ' . number_format($normalPrice, 0, ',', '.'),
                'bundle' => 'Rp ' . number_format($bundlePrice, 0, ',', '.'),
                'uplift' => '+' . (int) round(100 - ($bundlePrice / $normalPrice) * 100) . '% Potensi Penjualan',
            ];
        }

        return view('dashboard', compact(
            'topMenu',
            'bottomMenu',
            'branches',
            'lowMaterials',
            'bundlePromos',
            'totalRevenue',
            'forecastRevenue',
            'forecastTrend',
            'stockAlertCount',
            'bestBranchName',
            'topProductPrediction',
            'chartCategories',
            'historySeries',
            'forecastSeries'
        ));
    });

    Route::get('/set-branch/{id?}', function ($id = null) {
        if ($id) {
            session(['branch_id' => $id]); 
        } else {
            session()->forget('branch_id'); 
        }
        return back(); 
    })->name('set.branch');

    Route::get('/riwayat-penjualan', [TransactionController::class, 'index'])->name('transactions.index');
    Route::post('/import-dataset', [TransactionController::class, 'import'])->name('import.dataset');

    Route::get('/stok-inventaris', [RawMaterialController::class, 'index'])->name('inventory.index');

    Route::get('/comparison', function () {
        return view('branch-comparison');
    });

    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
});