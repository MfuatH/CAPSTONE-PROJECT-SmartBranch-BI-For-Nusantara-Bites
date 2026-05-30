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
            ->orderBy('store_id', 'asc')
            ->orderBy('name', 'asc')
            ->paginate(10);

        $totalItem = RawMaterial::count();
        $totalStokFisik = RawMaterial::sum('stock');
        
        $stokMenipis = RawMaterial::where('stock', '<=', 10)->count(); 

        return view('stock', compact('rawMaterials', 'totalItem', 'totalStokFisik', 'stokMenipis'));
    }
}