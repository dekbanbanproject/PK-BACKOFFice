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
        if (!Schema::hasTable('acc_debtor'))
        {
            Schema::create('acc_debtor', function (Blueprint $table) {
                $table->bigIncrements('acc_debtor_id'); 
                $table->enum('stamp', ['Y','N'])->default('N')->nullable();
                $table->string('vn')->nullable();// รหัส
                $table->string('an')->nullable();// 
                $table->string('hn')->nullable();// 
                $table->string('cid')->nullable();//  
                $table->string('ptname')->nullable();// 
                $table->date('vstdate')->nullable();//
                $table->Time('vsttime')->nullable();// 
                $table->date('dchdate')->nullable();//
                // $table->Time('dchtime')->nullable();// 
                $table->string('ptsubtype')->nullable();//  
                $table->string('pttype_eclaim_id')->nullable();// 
                $table->string('pttype_eclaim_name')->nullable();// 
                $table->string('pttype')->nullable();// 
                $table->string('pttypename')->nullable();// 
                $table->string('pttype_spsch')->nullable();//   
                
                $table->string('maininscl')->nullable();//  
                $table->string('hmain')->nullable();//  
                $table->string('subinscl')->nullable();//  
                $table->string('status')->nullable();//  
                $table->string('hsub')->nullable();//  
                $table->string('hmain_op')->nullable();//  
                $table->string('hmain_op_name')->nullable();//  
                $table->string('hsub_name')->nullable();//  
                $table->string('subinscl_name')->nullable();//  

                $table->string('gfmis')->nullable();// 
                $table->string('acc_code')->nullable();// 
                $table->string('account_code')->nullable();//  
                $table->string('account_name')->nullable();//  
                $table->string('income')->nullable();// 
                $table->string('uc_money')->nullable();// 
                $table->string('discount_money')->nullable();// 
                $table->string('paid_money')->nullable();// 
                $table->string('rcpt_money')->nullable();// 
                $table->string('rcpno')->nullable();//  
                $table->string('debit')->nullable();//  
                $table->string('max_debt_amount')->nullable();// 
                $table->string('acc_debtor_filename')->nullable();// 
                $table->string('acc_debtor_userid')->nullable();// 
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
        Schema::dropIfExists('acc_debtor');
    }
};
