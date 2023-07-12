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
        if (!Schema::hasTable('acc_stm_ucs_excel'))
        {
            Schema::connection('mysql')->create('acc_stm_ucs_excel', function (Blueprint $table) {
                $table->bigIncrements('acc_stm_ucs_excel_id');  
                $table->string('rep',100)->nullable();//  rep    
                $table->string('repno',100)->nullable();//  rep    
                $table->string('tranid')->nullable();//  เลขที่หนังสือ            
                $table->string('hn')->nullable();//    
                $table->string('an')->nullable();//  
                $table->string('cid')->nullable();//
                $table->string('fullname')->nullable();//ชื่อ-สกุล             
                $table->dateTime('vstdate')->nullable();//วันที่เข้ารับบริการ
                $table->dateTime('dchdate')->nullable();//วันที่จำหน่าย
                $table->string('maininscl')->nullable();//
                $table->string('projectcode')->nullable();//
                $table->double('debit', 12, 4)->nullable();//เรียกเก็บ
                $table->double('debit_prb', 12, 4)->nullable();//พรบ.
                $table->string('adjrw')->nullable();//adjrw
                $table->string('ps1')->nullable();//ล่าช้า (PS)
                $table->string('ps2')->nullable();//ล่าช้า (PS)
                $table->string('ccuf')->nullable();//ccuf
                $table->string('adjrw2')->nullable();//AdjRW2             
                $table->double('pay_money', 12, 4)->nullable();//อัตราจ่าย
                $table->double('pay_slip', 12, 4)->nullable();//เงินเดือน
                $table->double('pay_after', 12, 4)->nullable();//จ่ายชดเชยหลังหัก พรบ.และเงินเดือน
                $table->double('op', 12, 4)->nullable();//OP

                $table->double('ip_pay1', 12, 4)->nullable();//
                $table->double('ip_paytrue', 12, 4)->nullable();//
                $table->double('hc', 12, 4)->nullable();//
                $table->double('hc_drug', 12, 4)->nullable();//
                $table->double('ae', 12, 4)->nullable();//
                $table->double('ae_drug', 12, 4)->nullable();//
                $table->double('inst', 12, 4)->nullable();// 
                $table->double('dmis_money1', 12, 4)->nullable();//
                $table->double('dmis_money2', 12, 4)->nullable();//
                $table->double('dmis_drug', 12, 4)->nullable();// 
                $table->double('palliative_care', 12, 4)->nullable();//Palliative care
                $table->double('dmishd', 12, 4)->nullable();//DMISHD 
                $table->double('pp', 12, 4)->nullable();//PP                
                $table->double('fs', 12, 4)->nullable();//FS
                $table->double('opbkk', 12, 4)->nullable();//OPBKK
                $table->double('total_approve', 12, 4)->nullable();//ยอดชดเชยทั้งสิ้น
                $table->double('va', 12, 4)->nullable();//va
                $table->double('covid', 12, 4)->nullable();//covid 
                $table->date('date_save')->nullable();// 
                $table->string('STMdoc')->nullable();//    
                
                $table->enum('active', ['REP','APPROVE','CANCEL','FINISH','PULL'])->default('PULL')->nullable(); 
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
        Schema::dropIfExists('acc_stm_ucs_excel');
    }
};
