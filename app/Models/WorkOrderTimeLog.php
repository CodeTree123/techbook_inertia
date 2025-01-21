<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrderTimeLog extends Model
{
    use HasFactory;

    public function wo()
    {
        return $this->belongsTo(WorkOrder::class, 'wo_id');
    }

    public function preLog()
    {
        return $this->belongsTo(WorkOrderTimeLog::class, 'pre_log_id');
    }
}
