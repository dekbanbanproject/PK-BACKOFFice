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
        if (!Schema::hasTable('d_oop'))
        {
            Schema::connection('mysql7')->create('d_oop', function (Blueprint $table) {
                $table->bigIncrements('d_oop_id');

                $table->string('HN')->nullable();// 
                $table->date('DATEOPD')->nullable();// 
                 
                $table->string('CLINIC')->nullable();//  
                $table->string('OPER')->nullable(); //             
                $table->string('DROPID')->nullable(); //   
                $table->string('PERSON_ID')->nullable(); // 
                $table->string('SEQ')->nullable(); // 
                 
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
        Schema::dropIfExists('d_oop');
    }
};
