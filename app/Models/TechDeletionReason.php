<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TechDeletionReason extends Model
{
    use HasFactory;
    protected $fillable = [
        'wo_id',
        'tech_id',
        'reason',
    ];
    public function technician()
    {
        return $this->belongsTo(Technician::class, 'tech_id');
    }
}
