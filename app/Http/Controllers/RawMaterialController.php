<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RawMaterial;
use Illuminate\Support\Facades\DB;

class RawMaterialController extends Controller
{
    public function index(Request $request)
    {
        $branchId = $request->input('branch_id') ?? session('branch_id');

        $query = RawMaterial::with(['stores' => function($q) use ($branchId) {
            $q->withPivot('current_stock', 'minimum_stock', 'forecast_qty');
            
            if ($branchId) {
                $q->where('stores.id', $branchId);
            }
        }]);

        if ($branchId) {
            $query->whereHas('stores', function($q) use ($branchId) {
                $q->where('stores.id', $branchId);
            });
        } else {
            $query->has('stores');
        }

        $rawMaterials = $query->orderBy('name', 'asc')->paginate(10)->appends($request->all());

        $statsQuery = DB::table('raw_material_store');
        if ($branchId) {
            $statsQuery->where('store_id', $branchId);
        }

        $totalItem = $statsQuery->count();
        $totalStokFisik = $statsQuery->sum('current_stock');
        $stokMenipis = (clone $statsQuery)->whereRaw('current_stock <= minimum_stock')->count();

        return view('stock', compact('rawMaterials', 'totalItem', 'totalStokFisik', 'stokMenipis'));
    }
}