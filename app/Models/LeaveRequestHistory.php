<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeaveRequestHistory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "leave_request_histories";

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s'
    ];
}
