<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerSite extends Model
{
    use HasFactory;
    function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }
    function vendorList()
    {
        return $this->hasMany(VendorCareList::class, 'site_id', 'id');
    }
    function workOrder()
    {
        return $this->hasOne(WorkOrder::class, 'site_id', 'id');
    }
}
