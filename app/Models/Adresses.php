<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'user_id', 'address_type', 'street_address', 'city',
        'state', 'postal_code', 'country', 'is_default'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

