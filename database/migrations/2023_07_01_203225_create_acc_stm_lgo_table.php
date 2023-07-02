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
        if (!Schema::hasTable('acc_stm_lgo'))
        {
            Schema::connection('mysql')->create('acc_stm_lgo', function (Blueprint $table) {
                $table->bigIncrements('acc_stm_lgo_id'); 
                $table->string('rep',100)->nullable();//   
                $table->string('no')->nullable();// 
                $table->string('tranid')->nullable();// 
                $table->string('hn')->nullable();//   
                $table->string('an')->nullable();//  
                $table->string('cid')->nullable();//
                $table->string('fullname')->nullable();//ชื่อ-สกุล 
                $table->string('type')->nullable();//ประเภทผู้ป่วย
                $table->date('vstdate')->nullable();//วันที่เข้ารับบริการ  
                $table->date('dchdate')->nullable();// 
                $table->string('price1')->nullable();// 
                $table->string('pp_spsch')->nullable();//   
                $table->string('errorcode')->nullable();//  
                $table->string('kongtoon',255)->nullable();//     
                $table->string('typeservice')->nullable();//  
                $table->string('refer')->nullable();//  
                $table->string('pttype_have')->nullable();//    
                $table->string('pttype_true')->nullable();// 
                $table->string('mian_pttype')->nullable();// 
                $table->string('secon_pttype')->nullable();// 
                $table->string('href')->nullable();// 
                $table->string('HCODE')->nullable();// 
                $table->string('prov1')->nullable();//  
                $table->string('code_dep')->nullable();//  
                $table->string('name_dep')->nullable();//  
                $table->string('proj')->nullable();// 
                $table->string('pa')->nullable();// 
                $table->string('drg')->nullable();// 
                $table->string('rw')->nullable();// 
                $table->string('income')->nullable();// 
                $table->string('pp_gep')->nullable();// 
                $table->string('claim_true')->nullable();// 
                $table->string('claim_false')->nullable();// 
                $table->string('cash_money')->nullable();//
                $table->string('pay')->nullable();//
                $table->string('ps')->nullable();//
                $table->string('ps_percent')->nullable();//
                $table->string('ccuf')->nullable();//
                $table->string('AdjRW')->nullable();//
                $table->string('plb')->nullable();//
                $table->string('IPLG')->nullable();//
                $table->string('OPLG')->nullable();//
                $table->string('PALG')->nullable();//
                $table->string('INSTLG')->nullable();//
                $table->string('OTLG')->nullable();//
                $table->string('PP')->nullable();//
                $table->string('DRUG')->nullable();//
                $table->string('IPLG2')->nullable();//
                $table->string('OPLG2')->nullable();//
                $table->string('PALG2')->nullable();//
                $table->string('INSTLG2')->nullable();//
                $table->string('OTLG2')->nullable();//
                $table->string('ORS')->nullable();//
                $table->string('VA')->nullable();//
                $table->string('STMdoc')->nullable();//  
                $table->enum('active', ['REP','APPROVE','CANCEL','FINISH'])->default('REP')->nullable(); 
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
        Schema::dropIfExists('acc_stm_lgo');
    }
};
