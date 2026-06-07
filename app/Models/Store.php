<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'location'];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function rawMaterials()
    {
        return $this->belongsToMany(RawMaterial::class, 'raw_material_store')
                    ->withPivot('current_stock', 'minimum_stock')
                    ->withTimestamps();
    }
}