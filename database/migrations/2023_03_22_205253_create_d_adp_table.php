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
        if (!Schema::hasTable('d_adp'))
        {
            Schema::connection('mysql7')->create('d_adp', function (Blueprint $table) {
                $table->bigIncrements('d_adp_id');

                $table->string('HN')->nullable();// 
                $table->string('AN')->nullable();// 

                $table->string('DATEOPD')->nullable();// 
                 
                $table->string('TYPE')->nullable();//  
                $table->string('CODE')->nullable(); //   
                $table->string('QTY')->nullable(); // 
                $table->string('RATE')->nullable(); // 
                $table->string('SEQ')->nullable(); //

                $table->string('a1')->nullable(); //
                $table->string('a2')->nullable(); //
                $table->string('a3')->nullable(); //
                $table->string('a4')->nullable(); //
                $table->string('a5')->nullable(); //
                $table->string('a6')->nullable(); //
                $table->string('a7')->nullable(); //
                $table->string('TMLTCODE')->nullable(); //
                $table->string('STATUS1')->nullable(); //
                $table->string('BI')->nullable(); //
                $table->string('CLINIC')->nullable(); //
                $table->string('ITEMSRC')->nullable(); //
                $table->string('PROVIDER')->nullable(); //
                $table->string('GLAVIDA')->nullable(); //
                $table->string('GA_WEEK')->nullable(); //
                $table->string('DCIP')->nullable(); //
                $table->string('LMP')->nullable(); // 

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
        Schema::dropIfExists('d_adp');
    }
};
