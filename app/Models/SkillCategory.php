<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkillCategory extends Model
{
    use HasFactory;
    public $fillable = ['skill_name'];
    public function technicians()
    {
        return $this->belongsToMany(Technician::class, 'technician_skills');
    }
}
