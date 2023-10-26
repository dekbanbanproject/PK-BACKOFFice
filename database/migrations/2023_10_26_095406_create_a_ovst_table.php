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
        // if (!Schema::hasTable('a_ovst'))
        // {
        //     Schema::connection('mysql')->create('a_ovst', function (Blueprint $table) { 
        //         $table->string('hos_guid',38)->nullable();//  pri 1 
        //         $table->string('vn',13)->nullable();//   
        //         $table->string('hn',9)->nullable();// 
        //         $table->string('an',9)->nullable();// 
        //         $table->date('vstdate')->nullable();// 
        //         $table->time('vsttime')->nullable();//    
        //         $table->string('doctor',7)->nullable();// 
        //         $table->string('hospmain',5)->nullable();// 
        //         $table->string('hospsub',5)->nullable();// 
        //         $table->integer('oqueue',11)->nullable();// 
        //         $table->char('ovstist',2)->nullable();// 
        //         $table->string('ovstost',4)->nullable();// 
        //         $table->char('pttype',2)->nullable();// 
        //         $table->string('pttypeno',50)->nullable();// 
        //         $table->char('rfrics',1)->nullable();// 
        //         $table->string('pttypeno',50)->nullable();// 
        //         $table->string('pttypeno',50)->nullable();// 
        //         $table->string('pttypeno',50)->nullable();// 
        //         $table->string('pttypeno',50)->nullable();// 
        //         $table->string('pttypeno',50)->nullable();// 
               
        //         $table->char('hos_guid',38)->nullable();// 
        //         $table->string('hos_guid_ext',64)->nullable();// 
        //     }); 
            
        // }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('a_ovst');
    }
};
