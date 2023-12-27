<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    { 
        if (!Schema::hasTable('d_ofc_402'))
        {
            Schema::connection('mysql')->create('d_ofc_402', function (Blueprint $table) { 
                $table->bigIncrements('d_ofc_402_id');//  
                $table->string('vn')->nullable();//   
                $table->string('an')->nullable();//  
                $table->string('hn')->nullable();//  
                $table->string('pttype')->nullable();// 
                $table->date('dchdate')->nullable();// 
                $table->string('Apphos')->nullable();// 
                $table->string('Appktb')->nullable();// 
                $table->string('price_ofc')->nullable();// 
                $table->timestamps();
            }); 
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('d_ofc_402');
    }
};
