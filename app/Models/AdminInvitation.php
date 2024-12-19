<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminInvitation extends Model
{
    use HasFactory;

    public function isExpired()
    {
        return $this->expires_at->isPast();
    }
}
