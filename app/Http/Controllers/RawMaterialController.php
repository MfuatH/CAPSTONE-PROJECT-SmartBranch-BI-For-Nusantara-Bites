<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RawMaterial;
use App\Models\Store;

class RawMaterialController extends Controller
{
    public function index()
    {
        $rawMaterials = RawMaterial::with('store')
            ->when(request()->query('branch_id'), function ($query, $branchId) {
                $query->where('store_id', $branchId);
            })
            ->orderBy('store_id', 'asc')
            ->orderBy('name', 'asc')
            ->paginate(10)
            ->withQueryString();

        $totalItem = RawMaterial::when(request()->query('branch_id'), function ($query, $branchId) {
                $query->where('store_id', $branchId);
            })->count();
        $totalStokFisik = RawMaterial::when(request()->query('branch_id'), function ($query, $branchId) {
                $query->where('store_id', $branchId);
            })->sum('stock');
        $stokMenipis = RawMaterial::when(request()->query('branch_id'), function ($query, $branchId) {
                $query->where('store_id', $branchId);
            })->where('stock', '<=', 10)->count();

        return view('stock', compact('rawMaterials', 'totalItem', 'totalStokFisik', 'stokMenipis'));
    }
}