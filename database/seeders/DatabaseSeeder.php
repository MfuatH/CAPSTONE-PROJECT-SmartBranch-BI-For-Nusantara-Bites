<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Shuchkin\SimpleXLSX;
use App\Models\Store;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\RawMaterial;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Mulai ritual narik 5.000 data dari Excel... Sabar ya!');

        $file = storage_path('app/private/Coffee_Shop_Sales_Nusantara_Clean.xlsx');

        if (!file_exists($file)) {
            $this->command->error("Waduh, file excel-nya ga ketemu di storage/app/");
            return;
        }

        if ($xlsx = SimpleXLSX::parse($file)) {
            $rows = $xlsx->rows();
            $header = array_shift($rows);
            
            $limit = 5000;
            $count = 0;

            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            foreach ($rows as $row) {
                if ($count >= $limit) break;
                if (empty(array_filter($row)) || count($header) !== count($row)) continue;

                $data = array_combine($header, $row);

                $store = Store::firstOrCreate(
                    ['id' => $data['store_id']],
                    ['location' => $data['store_location'], 'status' => 'Aktif']
                );

                Product::firstOrCreate(
                    ['id' => $data['product_id']],
                    [
                        'category'   => $data['product_category'],
                        'type'       => $data['product_type'],
                        'detail'     => $data['product_detail'],
                        'unit_price' => $data['unit_price'],
                    ]
                );

                Transaction::create([
                    'id'               => $data['transaction_id'],
                    'store_id'         => $data['store_id'],
                    'product_id'       => $data['product_id'],
                    'transaction_date' => $data['transaction_date'],
                    'transaction_time' => $data['transaction_time'],
                    'qty'              => $data['transaction_qty'],
                ]);

                $count++;
            }

            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            $this->command->info("Mantap! $count transaksi berhasil masuk.");

            $this->command->info('Sekarang ngisi bahan baku buat tiap cabang...');
            
            $stores = Store::all();
            foreach ($stores as $store) {
                RawMaterial::factory(rand(8, 15))->create([
                    'store_id' => $store->id
                ]);
            }

            $this->command->info('Semua data dummy berhasil disiapkan! Gas cek UI!');

        } else {
            $this->command->error(SimpleXLSX::parseError());
        }
    }
}