<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use Searchable;
    use HasFactory;
    public function getQuantityAttribute($value)
    {
        return $value . ' Unit';
    }

    public function getRawQuantityAttribute()
    {
        return $this->attributes['quantity'];
    }

    public function getUnitCostAttribute($value)
    {
        return '$' . number_format($value, 2);
    }

    public function getRawUnitCostAttribute()
    {
        return $this->attributes['unit_cost'];
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
