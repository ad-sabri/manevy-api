<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class ProviderAccount extends Model
{
    use HasApiTokens, HasFactory;

    protected $fillable = ['email', 'password'];

    protected $hidden = ['password'];

    public function provider() 
    {
        return $this->hasOne(Provider::class);
    }  
}
