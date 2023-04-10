<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calendar extends Model
{
    use HasFactory;

    protected $casts = [
        'checkin' => 'datetime:H:i',
        'checkout' => 'datetime:H:i',
        'checkin_origin' => 'datetime:H:i',
        'checkout_origin' => 'datetime:H:i'
    ];
}
