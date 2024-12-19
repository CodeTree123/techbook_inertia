<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignedEngineer extends Model
{
    use HasFactory;
    public function engineer()
    {
        return $this->belongsTo(Engineer::class, 'tech_id');
    }
}