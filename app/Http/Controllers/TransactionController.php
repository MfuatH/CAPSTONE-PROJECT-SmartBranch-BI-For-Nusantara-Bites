<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Store;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Shuchkin\SimpleXLSX;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $search    = $request->input('search');
        $branchId  = $request->input('branch_id');
        $menu      = $request->input('menu');
        $minPrice  = $request->input('min_price');
        $maxPrice  = $request->input('max_price');
        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');

        $query = Transaction::with(['store', 'product'])
            ->orderBy('transaction_date', 'desc')
            ->orderBy('transaction_time', 'desc');

        if ($branchId) {
            $query->where('store_id', $branchId);
        } elseif (session()->has('branch_id')) {
            $query->where('store_id', session('branch_id'));
        }

        if ($search || $menu) {
            $keyword = $search ?: $menu;
            $query->whereHas('product', function($q) use ($keyword) {
                $q->where('detail', 'like', '%' . $keyword . '%')
                  ->orWhere('category', 'like', '%' . $keyword . '%');
            });
        }

        if ($minPrice || $maxPrice) {
            $query->whereHas('product', function($q) use ($minPrice, $maxPrice) {
                if ($minPrice) $q->where('unit_price', '>=', $minPrice);
                if ($maxPrice) $q->where('unit_price', '<=', $maxPrice);
            });
        }

        if ($startDate) {
            $query->where('transaction_date', '>=', $startDate);
        }
        if ($endDate) {
            $query->where('transaction_date', '<=', $endDate);
        }

        $transactions = $query->paginate(10)->appends($request->all());

        return view('sales', compact('transactions'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'dataset' => 'required|mimes:xlsx,xls,csv,txt|max:10240',
        ]);

        $file = $request->file('dataset');
        $extension = $file->getClientOriginalExtension();
        
        $rowCount = 0;
        DB::beginTransaction();
        
        try {
            if ($extension === 'csv' || $extension === 'txt') {
                $handle = fopen($file->getRealPath(), 'r');
                $header = fgetcsv($handle);
                
                while (($row = fgetcsv($handle)) !== FALSE) {
                    if (count($header) !== count($row)) continue;
                    
                    $data = array_combine($header, $row);
                    $this->insertTransactionData($data);
                    $rowCount++;
                }
                fclose($handle);
            } 
            else {
                if ($xlsx = SimpleXLSX::parse($file->getRealPath())) {
                    $rows = $xlsx->rows();
                    $header = array_shift($rows);
                    
                    foreach ($rows as $row) {
                        if (empty(array_filter($row)) || count($header) !== count($row)) continue;
                        
                        $data = array_combine($header, $row);
                        $this->insertTransactionData($data);
                        $rowCount++;
                    }
                } else {
                    throw new \Exception(SimpleXLSX::parseError());
                }
            }

            DB::commit();
            return back()->with('success', "Mantap coy! $rowCount data berhasil diimport.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Waduh, gagal import: ' . $e->getMessage());
        }
    }

    private function insertTransactionData($data)
    {
        Store::firstOrCreate(
            ['id' => $data['store_id']],
            ['location' => $data['store_location']]
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
    }
}