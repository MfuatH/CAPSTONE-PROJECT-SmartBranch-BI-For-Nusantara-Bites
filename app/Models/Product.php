<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id', 'category', 'type', 'detail', 'unit_price'
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}