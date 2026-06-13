<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use App\Models\ForecastResult;

class ForecastController extends Controller
{
    public function generateMassal(Request $request)
    {
        $latestTransaction = DB::table('transactions')->max('transaction_date');
        $baseDate = $latestTransaction ? Carbon::parse($latestTransaction) : Carbon::now();

        $targetDate = $baseDate->copy()->addMonth();
        $targetBulan = $targetDate->month;
        $targetTahun = $targetDate->year;

        ForecastResult::where('target_month', $targetBulan)
                      ->where('target_year', $targetTahun)
                      ->delete();

        $stores = DB::table('stores')->get();
        $products = DB::table('products')->get();

        $berhasil = 0;
        $gagal = 0;

        DB::table('raw_material_store')->update(['forecast_qty' => 0]);

        foreach ($stores as $store) {
            foreach ($products as $product) {
                
                $historicalSales = [];
                for ($i = 1; $i <= 6; $i++) {
                    $pastDate = $targetDate->copy()->subMonths($i);

                    $totalQty = DB::table('transactions')
                        ->where('store_id', $store->id)
                        ->where('product_id', $product->id)
                        ->whereYear('transaction_date', $pastDate->year)
                        ->whereMonth('transaction_date', $pastDate->month)
                        ->sum('qty');

                    $historicalSales[$i] = $totalQty ?? 0;
                }

                $lag_1 = $historicalSales[1];
                $lag_2 = $historicalSales[2];
                $lag_3 = $historicalSales[3];
                $lag_4 = $historicalSales[4];
                $lag_5 = $historicalSales[5];
                $lag_6 = $historicalSales[6];

                $rolling_3 = ($lag_1 + $lag_2 + $lag_3) / 3;
                $rolling_6 = ($lag_1 + $lag_2 + $lag_3 + $lag_4 + $lag_5 + $lag_6) / 6;

                $payload = [
                    "store_id"   => $store->location,
                    "product_id" => $product->detail,
                    "category"   => $product->category,
                    "type"       => $product->type,
                    "unit_price" => (float) $product->unit_price,
                    "bulan"      => (int) $targetBulan,
                    "tahun"      => (int) $targetTahun,
                    "lag_1"      => (float) $lag_1,
                    "lag_3"      => (float) $lag_3,
                    "lag_6"      => (float) $lag_6,
                    "rolling_3"  => (float) round($rolling_3, 2),
                    "rolling_6"  => (float) round($rolling_6, 2)
                ];

                try {
                    $response = Http::post('http://127.0.0.1:8001/api/forecast', $payload);

                    if ($response->successful()) {
                        $hasilAI = $response->json();
                        
                        if ($hasilAI['status'] == 'success') {
                            $predictedQty = $hasilAI['prediction'];

                            ForecastResult::create([
                                'store_id'      => $store->id,
                                'product_id'    => $product->id,
                                'target_month'  => $targetBulan,
                                'target_year'   => $targetTahun,
                                'predicted_qty' => $predictedQty
                            ]);
                            $berhasil++;

                            if ($predictedQty > 0) {
                                $resep = DB::table('product_raw_material')
                                    ->where('product_id', $product->id)
                                    ->get();

                                foreach ($resep as $bahan) {
                                    $totalKebutuhanBahan = $predictedQty * $bahan->qty_needed;

                                    DB::table('raw_material_store')
                                        ->where('store_id', $store->id)
                                        ->where('raw_material_id', $bahan->raw_material_id)
                                        ->increment('forecast_qty', $totalKebutuhanBahan);
                                }
                            }

                        } else {
                            $gagal++;
                        }
                    } else {
                        $gagal++;
                    }
                } catch (\Exception $e) {
                    return back()->with('error', 'Koneksi ke server AI Python mati! Nyalain dulu uvicorn-nya wkwk.');
                }
            }
        }

        return back()->with('success', "Proses Prediksi AI Selesai! $berhasil menu berhasil dihitung dan bahan baku telah dikalkulasi. $gagal proses gagal.");
    }
}