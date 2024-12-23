<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrderTimeLog extends Model
{
    use HasFactory;

    public function checkinout()
    {
        return $this->belongsTo(CheckInOut::class, 'checkinout_id');
    }
}
