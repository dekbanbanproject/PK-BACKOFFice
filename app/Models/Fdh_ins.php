<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Fdh_ins extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $connection = 'mysql';
    protected $table = 'fdh_ins';
    protected $primaryKey = 'fdh_ins_id';
    protected $fillable = [
        'HN','INSCL','SUBTYPE','CID','HCODE', 'DATEEXP', 'HOSPMAIN','HOSPSUB','GOVCODE', 
        'GOVNAME','PERMITNO','DOCNO','OWNRPID','OWNRNAME','AN','SEQ','SUBINSCL','RELINSCL','HTYPE'     
    ];

  
}
