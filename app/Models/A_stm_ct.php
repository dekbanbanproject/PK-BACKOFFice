<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\support\Facades\Http;
use Illuminate\Contracts\Auth\MustVerifyEmail; 
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class A_stm_ct extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'a_stm_ct'; 
    // protected $primaryKey = 'a_stm_ct_id';
    protected $fillable = [
        'ct_no',
        'ct_date', 
        'ct_timein',
        'hn',
        'ptname', 
        'hname', 
        'pttypename',
        'ward',
        'doctor',
        'doctor_read',
        'check' ,
        'price_check',
        'price_drug',
        'qty_drug',
        'remain',
        'user_id',
        'STMDoc',
        'vn',
        'an',
        'cid'
    ];
    // public $timestamps = false; 

  
}
