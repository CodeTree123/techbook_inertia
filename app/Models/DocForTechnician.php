<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocForTechnician extends Model
{
    use HasFactory;

    public function technician()
    {
        return $this->belongsTo(Technician::class, 'technician_id');
    }
}
