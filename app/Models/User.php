<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Traits\Searchable;
use App\Constants\Status;
use App\Models\Transaction;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Searchable;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token','ver_code',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'address' => 'object',
        'kyc_data' => 'object',
        'ver_code_send_at' => 'datetime'
    ];
    public function fullname() : Attribute
    {
        return new Attribute(
            get: fn () => $this->firstname . ' ' . $this->lastname ,
        );
    }

    public function loginLogs()
    {
        return $this->hasMany(UserLogin::class);
    }
    public function deposits()
    {
        return $this->hasMany(Deposit::class)->where('status','!=',Status::PAYMENT_INITIATE);
    }
    public function transactions()
    {
        return $this->hasMany(Transaction::class)->orderBy('id','desc');
    }

     // SCOPES
     public function scopeActive($query)
     {
         return $query->where('status', Status::USER_ACTIVE)->where('ev',Status::VERIFIED)->where('sv',Status::VERIFIED);
     }
 
     public function scopeBanned($query)
     {
         return $query->where('status', Status::USER_BAN);
     }
 
     public function scopeEmailUnverified($query)
     {
         return $query->where('ev', Status::UNVERIFIED);
     }
 
     public function scopeMobileUnverified($query)
     {
         return $query->where('sv', Status::UNVERIFIED);
     }
 
     public function scopeKycUnverified($query)
     {
         return $query->where('kv', Status::KYC_UNVERIFIED);
     }
 
     public function scopeKycPending($query)
     {
         return $query->where('kv', Status::KYC_PENDING);
     }
 
     public function scopeEmailVerified($query)
     {
         return $query->where('ev', Status::VERIFIED);
     }
 
     public function scopeMobileVerified($query)
     {
         return $query->where('sv', Status::VERIFIED);
     }
 
     public function scopeWithBalance($query)
     {
         return $query->where('balance','>', 0);
     }
}
