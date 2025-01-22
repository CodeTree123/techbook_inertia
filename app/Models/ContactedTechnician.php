<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactedTechnician extends Model
{
    use HasFactory;

    public function tech()
    {
        return $this->belongsTo(Technician::class, 'tech_id');
    }
}
