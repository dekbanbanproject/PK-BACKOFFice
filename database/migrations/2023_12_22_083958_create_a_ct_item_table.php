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
        if (!Schema::hasTable('a_ct_item'))
        {
            Schema::create('a_ct_item', function (Blueprint $table) {
                $table->bigIncrements('a_ct_item_id');   
                $table->string('vn')->nullable();//  
                $table->string('hn')->nullable();// 
                $table->string('cid')->nullable();//    
                $table->date('vstdate')->nullable();//     
                // $table->string('ptname')->nullable();//  
                
                $table->string('xray_items_code')->nullable();//   

                $table->string('icode')->nullable();//  
                $table->string('ctname')->nullable();//  
                $table->string('qty')->nullable();// 
                $table->string('unitprice')->nullable();//  
                $table->string('sum_price')->nullable();//  
                $table->string('user_id')->nullable();//  
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('a_ct_item');
    }
};
