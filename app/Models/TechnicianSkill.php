<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TechnicianSkill extends Model
{
    use HasFactory;

    public function skill()
    {
        return $this->belongsTo(SkillCategory::class,  'skill_id');
    }
}
