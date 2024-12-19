<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerInvoice extends Model
{
    use HasFactory;
    public function workOrder()
    {
        return $this->hasOne(WorkOrder::class, 'id', 'work_order_id');
    }
}
