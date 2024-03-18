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
        if (!Schema::hasTable('fdh_opd'))
        {
            Schema::connection('mysql')->create('fdh_opd', function (Blueprint $table) {
                $table->bigIncrements('fdh_opd_id');

                $table->string('HN',length: 15)->nullable();//
                $table->string('CLINIC')->nullable();//
                $table->string('DATEOPD')->nullable();// 
                $table->string('TIMEOPD')->nullable();//  
                $table->string('SEQ')->nullable(); //             
                $table->string('UUC')->nullable(); // 
               
                $table->string('DETAIL')->nullable(); //  
                $table->decimal('BTEMP',3,1)->nullable(); // 
                $table->decimal('SBP',3)->nullable(); //  
                $table->decimal('DBP',3)->nullable(); //  
                $table->decimal('PR',3)->nullable(); // 
                $table->decimal('RR',3)->nullable(); //   
                $table->text('OPTYPE',2)->nullable(); // 
                $table->text('TYPEIN',1)->nullable(); // 
                $table->text('TYPEOUT',1)->nullable(); // 

                $table->string('d_anaconda_id')->nullable(); // 
                $table->string('user_id')->nullable(); //        
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fdh_opd');
    }
};
