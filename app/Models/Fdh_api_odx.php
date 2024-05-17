<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Fdh_api_odx extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'fdh_api_odx';
    protected $primaryKey = 'fdh_api_odx_id';
    protected $fillable = [
        'type',
        'file',      
    ];

  
}
