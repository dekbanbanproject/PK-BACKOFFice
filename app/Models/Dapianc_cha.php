<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Dapianc_cha extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'dapianc_cha';
    protected $primaryKey = 'dapianc_cha_id';
    protected $fillable = [
        'blobName',
        'blobType',
        'blob'         
    ];

  
}
