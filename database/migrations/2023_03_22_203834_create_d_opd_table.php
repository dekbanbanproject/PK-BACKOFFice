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
        if (!Schema::hasTable('d_opd'))
        {
            Schema::connection('mysql7')->create('d_opd', function (Blueprint $table) {
                $table->bigIncrements('d_opd_id');

                $table->string('HN')->nullable();//
                $table->string('CLINIC')->nullable();//
                $table->date('DATEOPD')->nullable();// 
                $table->string('TIMEOPD')->nullable();//  
                $table->string('SEQ')->nullable(); //             
                $table->string('UUC')->nullable(); //           
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
        Schema::dropIfExists('d_opd');
    }
};
