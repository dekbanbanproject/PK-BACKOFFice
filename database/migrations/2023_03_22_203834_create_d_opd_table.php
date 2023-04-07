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
                $table->string('DATEOPD')->nullable();// 
                $table->string('TIMEOPD')->nullable();//  
                $table->string('SEQ')->nullable(); //             
                $table->string('UUC')->nullable(); // 
                $table->string('DETAIL')->nullable(); //  
                $table->string('BTEMP')->nullable(); // 
                $table->string('SBP')->nullable(); //  
                $table->string('DBP')->nullable(); //  
                $table->string('PR')->nullable(); // 
                $table->string('RR')->nullable(); //   
                $table->string('OPTYPE')->nullable(); // 
                $table->string('TYPEIN')->nullable(); // 
                $table->string('TYPEOUT')->nullable(); //        
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
