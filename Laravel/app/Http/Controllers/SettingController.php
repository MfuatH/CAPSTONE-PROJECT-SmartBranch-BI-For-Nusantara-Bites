<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;

class SettingController extends Controller
{
    public function index()
    {
        $stores = Store::orderBy('location', 'asc')->get();
        
        return view('/settings', compact('stores'));
    }
}