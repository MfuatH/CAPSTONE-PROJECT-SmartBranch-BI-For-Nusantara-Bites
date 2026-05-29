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
}