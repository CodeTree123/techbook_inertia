<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerInvoice extends Model
{
    use HasFactory;
    protected $fillable = [
        'invoice_number',
        'work_order_id',
        'status',
        'date',
        'site_num',
        'job',
        'p_o',
        'terms',
        'wo_req',
        'wo_per',
        'tax',
        'shipping',
        'credit',
        'reference_code',
    ];

    public function workOrder()
    {
        return $this->hasOne(WorkOrder::class, 'id', 'work_order_id');
    }
}
