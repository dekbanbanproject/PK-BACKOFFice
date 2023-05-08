<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    { 
        if (!Schema::hasTable('check_sit_auto'))
        {
            // Schema::connection('mysql7')->create('check_sit_auto', function (Blueprint $table) {
            Schema::connection('mysql')->create('check_sit_auto', function (Blueprint $table) {
                $table->bigIncrements('check_sit_auto_id'); 
                $table->string('vn')->nullable();// รหัส
                $table->string('hn')->nullable();// 
                $table->string('cid')->nullable();// 
                $table->date('vstdate')->nullable();//  
                $table->Time('vsttime')->nullable();// 

                $table->string('hospmain')->nullable();// 
                $table->string('hospsub')->nullable();// 

                $table->string('pttype')->nullable();// 
                $table->string('fullname')->nullable();// 
                $table->string('staff')->nullable();// 

                
                $table->string('maininscl')->nullable();// 
                $table->string('startdate')->nullable();// 
                $table->string('hmain')->nullable();// 
                $table->string('hsub')->nullable();// 
                $table->string('hsub_name')->nullable();// 
                $table->string('subinscl_name')->nullable();// 
                $table->string('subinscl')->nullable();// 
                $table->string('person_id_nhso')->nullable();//  

                $table->string('hmain_op')->nullable();// 
                $table->string('hmain_op_name')->nullable();// 
                $table->string('status')->nullable();// 
                $table->date('upsit_date')->nullable();//  
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('check_sit_auto');
    }
};
