<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RawMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id', 'name', 'sku', 'stock', 'unit', 'price_per_unit'
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}