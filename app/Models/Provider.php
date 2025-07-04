<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Provider extends Model
{
    protected $fillable = [
        'provider_account_id',
        'name',
        'address',
        'postal_code',
        'city',
        'phone',
        'status',
    ];

    public function account()
    {
        return $this->belongsTo(ProviderAccount::class, 'provider_account_id');
    }
}
