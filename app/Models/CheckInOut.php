<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Searchable;

class CheckInOut extends Model
{
    use HasFactory;
    use searchable;
    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class, 'work_order_id', 'id');
    }
    public function technician()
    {
        return $this->belongsTo(Technician::class, 'tech_id', 'id');
    }

    public function engineer()
    {
        return $this->belongsTo(Engineer::class, 'tech_id');
    }
}
