<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RawMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sku',
        'unit',
        'price_per_unit',
    ];

    public function stores()
    {
        return $this->belongsToMany(Store::class, 'raw_material_store')
                    ->withPivot('current_stock', 'minimum_stock')
                    ->withTimestamps();
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_raw_material')
                    ->withPivot('qty_needed')
                    ->withTimestamps();
    }
}