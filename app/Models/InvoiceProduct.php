<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceProduct extends Model
{
    use HasFactory;
    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class, 'wo_id');
    }
}
