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
        if (!Schema::hasTable('d_lvd'))
        {
            Schema::connection('mysql7')->create('d_lvd', function (Blueprint $table) {
                $table->bigIncrements('d_lvd_id');
                $table->string('SEQLVD')->nullable();//  
                $table->string('AN')->nullable();//  

                $table->date('DATEOUT')->nullable();// 
                $table->Text('TIMEOUT')->nullable();// 
                $table->date('DATEIN')->nullable();//  
                $table->Text('TIMEIN')->nullable();//  
                $table->Text('QTYDAY')->nullable();//  
 
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
        Schema::dropIfExists('d_lvd');
    }
};
