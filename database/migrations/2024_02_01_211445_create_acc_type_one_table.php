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
       
        if (!Schema::hasTable('acc_type_one'))
        {
            Schema::connection('mysql')->create('acc_type_one', function (Blueprint $table) { 
                $table->bigIncrements('acc_type_one_id');//  
                $table->string('plan_control_id')->nullable();//   
                $table->string('plan_sub_name')->nullable();//         / 
                $table->string('plan_sub_price')->nullable();//   
                $table->date('plan_sub_starttime')->nullable();//     ระยะเวลา 
                $table->date('plan_sub_endtime')->nullable();//       ระยะเวลา  
                $table->string('plan_sub_price')->nullable();//       งบประมาณ  
                $table->string('department')->nullable();//       กลุ่มงาน
                $table->string('hos_group')->nullable();//   รพ /  รพสต
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
        Schema::dropIfExists('acc_type_one');
    }
};
