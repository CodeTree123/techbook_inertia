<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorCareList extends Model
{
    use HasFactory;
    function site()
    {
        return $this->belongsTo(CustomerSite::class, 'site_id', 'id');
    }
    function tech()
    {
        return $this->belongsTo(Technician::class, 'technician_id', 'id');
    }
}
