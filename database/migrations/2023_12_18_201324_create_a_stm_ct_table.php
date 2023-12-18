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
        if (!Schema::hasTable('a_stm_ct'))
        {
            Schema::create('a_stm_ct', function (Blueprint $table) {
                $table->bigIncrements('a_stm_ct_id'); 
                $table->string('ct_no')->nullable();//    
                $table->date('ct_date')->nullable();//   
                $table->Time('ct_timein')->nullable();//       
                $table->string('hn')->nullable();//        
                $table->string('ptname')->nullable();//   
                $table->string('hname')->nullable();// 
                $table->string('pttypename')->nullable();// 
                $table->string('ward')->nullable();// 
                $table->string('doctor')->nullable();// 
                $table->string('doctor_read')->nullable();// 
                $table->string('check')->nullable();// 
                $table->string('price_check')->nullable();// 
                $table->string('price_drug')->nullable();// 
                $table->string('qty_drug')->nullable();// 
                $table->string('remain')->nullable();// 
                $table->string('user_id')->nullable();// 
                $table->string('STMDoc')->nullable();// 
                $table->string('vn')->nullable();// 
                $table->string('an')->nullable();// 
                $table->string('cid')->nullable();// 
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('a_stm_ct');
    }
};
