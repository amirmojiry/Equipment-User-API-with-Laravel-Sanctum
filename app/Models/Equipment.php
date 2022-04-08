<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'internal_notes', 'quantity'];

    public function scopeWithQuantity($query, $quantity)
    {
        if (isset($quantity)) {
            return $query->where('quantity', $quantity);
        }
    }
}
