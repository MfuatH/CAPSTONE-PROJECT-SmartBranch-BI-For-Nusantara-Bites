<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Shuchkin\SimpleXLSX;
use App\Models\User;
use App\Models\Store;
use App\Models\Product;
use App\Models\Transaction;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Membuat akun Super Admin...');
        
        User::updateOrCreate(
            ['email' => 'admin@nusantara.com'],
            [
                'name'      => 'Admin Pusat',
                'password'  => bcrypt('password123'),
                'role'      => 'Super_Admin',
                'is_active' => true,
            ]
        );
        $this->command->info('Akun Admin berhasil dibuat! (admin@nusantara.com / password123)');

        $this->command->info('Memuat 5.000 data dari Excel');

        $file = storage_path('app/private/Coffee_Shop_Sales_Nusantara_Clean.xlsx');

        if (!file_exists($file)) {
            $this->command->error("File Excel tidak ditemukan di: $file. Pastikan file sudah ada dan coba lagi.");
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
            $this->command->info("Selesai memuat $count data transaksi dari Excel!");

            $this->command->info('Menyiapkan Master Bahan Baku & Resep...');

            $rawMaterials = [
                ['name' => 'Biji Kopi Arabica', 'sku' => 'BB-001', 'unit' => 'Gram', 'price_per_unit' => 150.00, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Biji Kopi Robusta', 'sku' => 'BB-002', 'unit' => 'Gram', 'price_per_unit' => 120.00, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Susu Fresh Milk', 'sku' => 'BB-003', 'unit' => 'Mililiter', 'price_per_unit' => 25.00, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Gula Aren Cair', 'sku' => 'BB-004', 'unit' => 'Mililiter', 'price_per_unit' => 15.00, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Cup Plastik + Sedotan', 'sku' => 'BB-005', 'unit' => 'Pcs', 'price_per_unit' => 800.00, 'created_at' => now(), 'updated_at' => now()],
            ];
            DB::table('raw_materials')->insert($rawMaterials);
            $materialIds = DB::table('raw_materials')->pluck('id')->toArray();

            $this->command->info('Mendistribusikan stok gudang ke tiap cabang...');
            $stores = Store::all();
            foreach ($stores as $store) {
                foreach ($materialIds as $mId) {
                    DB::table('raw_material_store')->insert([
                        'store_id'        => $store->id,
                        'raw_material_id' => $mId,
                        'current_stock'   => rand(1000, 5000),
                        'minimum_stock'   => 500,
                        'created_at'      => now(),
                        'updated_at'      => now(),
                    ]);
                }
            }

            $this->command->info('Menyusun takaran buku resep untuk tiap menu...');
            $products = Product::all();
            foreach ($products as $product) {
                $usedMaterials = (array) array_rand(array_flip($materialIds), rand(2, 3));
                foreach ($usedMaterials as $mId) {
                    DB::table('product_raw_material')->insert([
                        'product_id'      => $product->id,
                        'raw_material_id' => $mId,
                        'qty_needed'      => rand(10, 150),
                        'created_at'      => now(),
                        'updated_at'      => now(),
                    ]);
                }
            }

            $this->command->info('Seed data bahan baku dan resep berhasil disiapkan!');

        } else {
            $this->command->error(SimpleXLSX::parseError());
        }
    }
}