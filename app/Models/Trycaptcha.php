<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trycaptcha extends Model
{
    use HasFactory;
    protected $table = 'trycaptcha';

    // If your table is named "trycaptcha" instead of the plural "trycaptchas",
    // uncomment the next line:
    // protected $table = 'trycaptcha';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int,string>
     */
    protected $fillable = [
        'user_id',
        'status',
        'earned',
    ];
}
