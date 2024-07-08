<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Air_repaire_excel extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'air_repaire_excel';
    protected $primaryKey = 'air_repaire_excel_id';
    protected $fillable = [
        'repaire_date',
        'repaire_time' 
    ];

  
}
