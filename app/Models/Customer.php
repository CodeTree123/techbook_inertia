<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $casts = [
        'address' => 'object',
    ];
    public function sites()
    {
        return $this->hasMany(CustomerSite::class, 'customer_id', 'id');
    }
    public function workOrder()
    {
        return $this->hasMany(WorkOrder::class, 'slug', 'id');
    }
    //scope
    public function scopeWithOrder($query)
    {
        $query->whereHas('workOrder', function ($order) {
            $order->select('slug');
        });
    }
}
