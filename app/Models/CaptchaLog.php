<?php

// app/Models/CaptchaLog.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaptchaLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',       
        'status',
        'work_started'
    ];
}
