<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TechDeletionReason extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function deletionTechnician()
    {
        return $this->hasOne(Technician::class, 'tech_id');
    }
}
