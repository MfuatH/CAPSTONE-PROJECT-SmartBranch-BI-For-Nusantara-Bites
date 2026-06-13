<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Shuchkin\SimpleXLSX;
use App\Models\User;
use App\Models\Store;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\RawMaterial;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::disableQueryLog();

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
        $this->command->info('Akun Admin berhasil dibuat!');

        $this->command->info('Memuat SELURUH data dari Excel (Bulk Insert Mode)...');

        $file = storage_path('app/private/Coffee_Shop_Sales_Nusantara_Clean.xlsx');

        if (!file_exists($file)) {
            $this->command->error("File Excel tidak ditemukan di: $file.");
            return;
        }

        if ($xlsx = SimpleXLSX::parse($file)) {
            $rows = $xlsx->rows();
            $header = array_shift($rows);
            
            $count = 0;
            $chunkSize = 2500;
            $transactionsChunk = [];
            
            $storeCache = [];
            $productCache = [];

            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            DB::beginTransaction();

            try {
                foreach ($rows as $row) {
                    if (empty(array_filter($row)) || count($header) !== count($row)) continue;

                    $data = array_combine($header, $row);

                    if (!isset($storeCache[$data['store_id']])) {
                        Store::firstOrCreate(
                            ['id' => $data['store_id']],
                            ['location' => $data['store_location'], 'status' => 'Aktif']
                        );
                        $storeCache[$data['store_id']] = true; 
                    }

                    if (!isset($productCache[$data['product_id']])) {
                        Product::firstOrCreate(
                            ['id' => $data['product_id']],
                            [
                                'category'   => $data['product_category'],
                                'type'       => $data['product_type'],
                                'detail'     => $data['product_detail'],
                                'unit_price' => $data['unit_price'],
                            ]
                        );
                        $productCache[$data['product_id']] = true;
                    }

                    $transactionsChunk[] = [
                        'id'               => $data['transaction_id'],
                        'store_id'         => $data['store_id'],
                        'product_id'       => $data['product_id'],
                        'transaction_date' => $data['transaction_date'],
                        'transaction_time' => $data['transaction_time'],
                        'qty'              => $data['transaction_qty'],
                        'created_at'       => now(), 
                        'updated_at'       => now(),
                    ];

                    $count++;

                    if (count($transactionsChunk) >= $chunkSize) {
                        Transaction::insert($transactionsChunk);
                        $transactionsChunk = [];
                        $this->command->info("Mengeksekusi... $count baris diproses.");
                    }
                }

                if (!empty($transactionsChunk)) {
                    Transaction::insert($transactionsChunk);
                }

                DB::commit();
                $this->command->info("Selesai memuat total $count data transaksi!");

            } catch (\Exception $e) {
                DB::rollBack();
                $this->command->error("Terjadi kesalahan: " . $e->getMessage());
                return;
            } finally {
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            }

            $this->command->info('Menyiapkan data bahan baku untuk setiap kategori produk...');

            $rawMaterials = [
                ['name' => 'Biji Kopi Espresso Blend', 'sku' => 'BB-001', 'unit' => 'Gram', 'price_per_unit' => 150.00],
                ['name' => 'Biji Kopi Nusantara Blend', 'sku' => 'BB-002', 'unit' => 'Gram', 'price_per_unit' => 120.00],
                ['name' => 'Biji Kopi Single Origin', 'sku' => 'BB-003', 'unit' => 'Gram', 'price_per_unit' => 180.00],
                ['name' => 'Daun Teh Hitam Premium', 'sku' => 'BB-004', 'unit' => 'Gram', 'price_per_unit' => 50.00],
                ['name' => 'Daun Teh Spesial (Earl Grey/Peppermint)', 'sku' => 'BB-005', 'unit' => 'Gram', 'price_per_unit' => 75.00],
                ['name' => 'Bubuk Kakao Jawa & Dark Choco', 'sku' => 'BB-006', 'unit' => 'Gram', 'price_per_unit' => 80.00],
                ['name' => 'Susu Fresh Milk (UHT)', 'sku' => 'BB-007', 'unit' => 'Mililiter', 'price_per_unit' => 25.00],
                ['name' => 'Gula Aren Cair', 'sku' => 'BB-008', 'unit' => 'Mililiter', 'price_per_unit' => 15.00],
                ['name' => 'Gula Pasir & Gula Batu', 'sku' => 'BB-009', 'unit' => 'Gram', 'price_per_unit' => 12.00],
                ['name' => 'Sirup Premium (Vanilla/Karamel/Pandan)', 'sku' => 'BB-010', 'unit' => 'Mililiter', 'price_per_unit' => 30.00],
                ['name' => 'Rempah Tradisional (Jahe/Serai)', 'sku' => 'BB-011', 'unit' => 'Gram', 'price_per_unit' => 45.00],
                ['name' => 'Tepung Terigu & Mentega', 'sku' => 'BB-012', 'unit' => 'Gram', 'price_per_unit' => 20.00],
                ['name' => 'Bahan Isian (Cokelat/Keju/Ayam)', 'sku' => 'BB-013', 'unit' => 'Gram', 'price_per_unit' => 50.00],
                ['name' => 'Roti Tawar & Singkong Mentah', 'sku' => 'BB-014', 'unit' => 'Porsi', 'price_per_unit' => 3000.00],
                ['name' => 'Beras & Mie Mentah', 'sku' => 'BB-015', 'unit' => 'Gram', 'price_per_unit' => 15.00],
                ['name' => 'Daging Ayam & Sapi Segar', 'sku' => 'BB-016', 'unit' => 'Gram', 'price_per_unit' => 120.00],
                ['name' => 'Bumbu Dapur & Sayuran', 'sku' => 'BB-017', 'unit' => 'Gram', 'price_per_unit' => 10.00],
                ['name' => 'Paper Cup (Small)', 'sku' => 'BB-018', 'unit' => 'Pcs', 'price_per_unit' => 300.00],
                ['name' => 'Paper Cup (Regular)', 'sku' => 'BB-018', 'unit' => 'Pcs', 'price_per_unit' => 500.00],
                ['name' => 'Paper Cup (Large)', 'sku' => 'BB-019', 'unit' => 'Pcs', 'price_per_unit' => 800.00],
            ];

            $materialMap = [];
            foreach ($rawMaterials as $rm) {
                $inserted = RawMaterial::firstOrCreate(
                    ['sku' => $rm['sku']],
                    [
                        'name' => $rm['name'], 
                        'unit' => $rm['unit'], 
                        'price_per_unit' => $rm['price_per_unit']
                    ]
                );
                $materialMap[$rm['name']] = $inserted->id;
            }

            $this->command->info('Mendistribusikan stok gudang ke tiap cabang...');
            $stores = Store::all();
            foreach ($stores as $store) {
                foreach ($materialMap as $name => $mId) {
                    DB::table('raw_material_store')->updateOrInsert(
                        ['store_id' => $store->id, 'raw_material_id' => $mId],
                        [
                            'current_stock'   => str_contains($name, 'Cup') ? rand(5000, 10000) : rand(2000, 8000),
                            'minimum_stock'   => str_contains($name, 'Cup') ? 1000 : 500,
                            'created_at'      => now(),
                            'updated_at'      => now(),
                        ]
                    );
                }
            }

            $this->command->info('Menyusun resep menu berdasarkan kategori produk...');
            $products = Product::all();
            
            foreach ($products as $product) {
                $attachData = [];
                $namaMenu = strtolower($product->name ?? $product->detail ?? '');

                switch ($product->type) {
                    case 'Barista Espresso':
                        $attachData[$materialMap['Biji Kopi Espresso Blend']] = ['qty_needed' => 18];
                        if (str_contains($namaMenu, 'latte') || str_contains($namaMenu, 'cappuccino')) {
                            $attachData[$materialMap['Susu Fresh Milk (UHT)']] = ['qty_needed' => 150];
                        }
                        break;
                    
                    case 'Kopi Lokal Nusantara':
                        $attachData[$materialMap['Biji Kopi Nusantara Blend']] = ['qty_needed' => 20];
                        if (str_contains($namaMenu, 'aren')) {
                            $attachData[$materialMap['Gula Aren Cair']] = ['qty_needed' => 30];
                        } else {
                            $attachData[$materialMap['Gula Pasir & Gula Batu']] = ['qty_needed' => 15];
                        }
                        if (str_contains($namaMenu, 'susu') || str_contains($namaMenu, 'sanger') || str_contains($namaMenu, 'tarik')) {
                            $attachData[$materialMap['Susu Fresh Milk (UHT)']] = ['qty_needed' => 80];
                        }
                        break;

                    case 'Single Origin Indonesia':
                        $attachData[$materialMap['Biji Kopi Single Origin']] = ['qty_needed' => 20];
                        break;

                    case 'Teh Nusantara & Klasik':
                        if (str_contains($namaMenu, 'earl grey') || str_contains($namaMenu, 'peppermint')) {
                            $attachData[$materialMap['Daun Teh Spesial (Earl Grey/Peppermint)']] = ['qty_needed' => 10];
                        } elseif (str_contains($namaMenu, 'lemon')) {
                            $attachData[$materialMap['Daun Teh Hitam Premium']] = ['qty_needed' => 10];
                            $attachData[$materialMap['Rempah Tradisional (Jahe/Serai)']] = ['qty_needed' => 15];
                        } else {
                            $attachData[$materialMap['Daun Teh Hitam Premium']] = ['qty_needed' => 15];
                        }
                        
                        if (!str_contains($namaMenu, 'tawar')) {
                            $attachData[$materialMap['Gula Pasir & Gula Batu']] = ['qty_needed' => 20];
                        }
                        if (str_contains($namaMenu, 'tarik')) {
                            $attachData[$materialMap['Susu Fresh Milk (UHT)']] = ['qty_needed' => 100];
                        }
                        break;
                    
                    case 'Hot chocolate':
                        $attachData[$materialMap['Bubuk Kakao Jawa & Dark Choco']] = ['qty_needed' => 30];
                        $attachData[$materialMap['Susu Fresh Milk (UHT)']] = ['qty_needed' => 150];
                        $attachData[$materialMap['Gula Pasir & Gula Batu']] = ['qty_needed' => 10];
                        break;

                    case 'Wedang & Jamu':
                        $attachData[$materialMap['Rempah Tradisional (Jahe/Serai)']] = ['qty_needed' => 25];
                        $attachData[$materialMap['Gula Pasir & Gula Batu']] = ['qty_needed' => 20];
                        if (str_contains($namaMenu, 'bajigur')) {
                            $attachData[$materialMap['Susu Fresh Milk (UHT)']] = ['qty_needed' => 50];
                        }
                        break;

                    case 'Regular syrup':
                        $attachData[$materialMap['Sirup Premium (Vanilla/Karamel/Pandan)']] = ['qty_needed' => 15];
                        $attachData[$materialMap['Gula Aren Cair']] = ['qty_needed' => 10];
                        break;

                    case 'Jajanan Pasar':
                    case 'Pastry':
                        $attachData[$materialMap['Tepung Terigu & Mentega']] = ['qty_needed' => 80];
                        $attachData[$materialMap['Bahan Isian (Cokelat/Keju/Ayam)']] = ['qty_needed' => 30];
                        break;
                    
                    case 'Roti & Gorengan':
                        $attachData[$materialMap['Roti Tawar & Singkong Mentah']] = ['qty_needed' => 1];
                        if (str_contains($namaMenu, 'coklat') || str_contains($namaMenu, 'keju')) {
                            $attachData[$materialMap['Bahan Isian (Cokelat/Keju/Ayam)']] = ['qty_needed' => 25];
                        } else {
                            $attachData[$materialMap['Tepung Terigu & Mentega']] = ['qty_needed' => 20];
                        }
                        break;

                    case 'Berkuah':
                        $attachData[$materialMap['Daging Ayam & Sapi Segar']] = ['qty_needed' => 100];
                        $attachData[$materialMap['Bumbu Dapur & Sayuran']] = ['qty_needed' => 50];
                        break;

                    case 'Nasi & Mie':
                        $attachData[$materialMap['Beras & Mie Mentah']] = ['qty_needed' => 150];
                        $attachData[$materialMap['Daging Ayam & Sapi Segar']] = ['qty_needed' => 60];
                        $attachData[$materialMap['Bumbu Dapur & Sayuran']] = ['qty_needed' => 30];
                        break;

                    default:
                        $attachData[$materialMap['Bumbu Dapur & Sayuran']] = ['qty_needed' => 20];
                        break;
                }

                $isBeverage = in_array($product->type, [
                    'Barista Espresso', 'Kopi Lokal Nusantara', 'Single Origin Indonesia',
                    'Teh Nusantara & Klasik', 'Hot chocolate', 'Wedang & Jamu'
                ]);

                if ($isBeverage) {
                    if (str_contains($namaMenu, 'lg')) {
                        $attachData[$materialMap['Paper Cup (Large)']] = ['qty_needed' => 1];
                    } elseif (str_contains($namaMenu, 'sm')) {
                        $attachData[$materialMap['Paper Cup (Small)']] = ['qty_needed' => 1];
                    } else {
                        $attachData[$materialMap['Paper Cup (Regular)']] = ['qty_needed' => 1];
                    }
                }

                foreach ($attachData as $mId => $pivotData) {
                    DB::table('product_raw_material')->updateOrInsert(
                        ['product_id' => $product->id, 'raw_material_id' => $mId],
                        [
                            'qty_needed' => $pivotData['qty_needed'],
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }
            }

            $this->command->info('Seeding berhasil total! Semua 150K data dan logika bahan baku telah dimuat.');

        } else {
            $this->command->error(SimpleXLSX::parseError());
        }
    }
}