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
        if (!Schema::hasTable('acc_ucep_24'))
        {
            Schema::connection('mysql')->create('acc_ucep_24', function (Blueprint $table) { 
                $table->bigIncrements('acc_ucep_24_id');//  
                $table->string('vn')->nullable();//    
                $table->string('an')->nullable();//   
                $table->Date('vstdate')->nullable();//       
                $table->Date('dchdate')->nullable();//          
                $table->Date('rxdate')->nullable();//           
                $table->Time('rxtime')->nullable();//   
                $table->decimal('sum_price',12,2)->nullable();//   
                $table->timestamps();
            }); 
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acc_ucep_24');
    }
};
