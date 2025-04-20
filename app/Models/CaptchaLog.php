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
        'captcha_text',
        'entered_captcha',
        'status',
    ];
}
