<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class, 'auth_id');
    }

    public function subNotes()
    {
        return $this->hasMany(Note::class, 'related_note');
    }
}
