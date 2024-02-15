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
        if (!Schema::hasTable('d_dru'))
        {
            Schema::connection('mysql')->create('d_dru', function (Blueprint $table) {
                $table->bigIncrements('d_dru_id');
                $table->string('HCODE',5)->nullable();// 
                $table->string('HN',15)->nullable();// 
                $table->string('AN',9)->nullable();// 
                $table->string('CLINIC',5)->nullable();// 
                $table->string('PERSON_ID',13)->nullable();// 
                $table->string('DATE_SERV')->nullable();//                  
                $table->string('DID',30)->nullable();//  
                $table->string('DIDNAME',200)->nullable(); //   
                $table->string('AMOUNT',12)->nullable(); // 
                $table->string('DRUGPRIC',14)->nullable(); // 
                $table->string('DRUGCOST',14)->nullable(); //
                $table->string('DIDSTD',24)->nullable(); //
                $table->string('UNIT',20)->nullable(); //
                $table->string('UNIT_PACK',20)->nullable(); //
                $table->string('SEQ',15)->nullable(); //
                $table->string('DRUGREMARK',2)->nullable(); //
                $table->string('PA_NO',9)->nullable(); //
                $table->string('TOTCOPAY', 12, 2)->nullable(); //
                $table->string('USE_STATUS',1)->nullable(); //
                // $table->string('STATUS1')->nullable(); //
                $table->string('TOTAL', 12, 2)->nullable(); //
                $table->string('SIGCODE',50)->nullable(); //
                $table->string('SIGTEXT',200)->nullable(); // 
                $table->string('PROVIDER')->nullable(); //                
                $table->string('SP_ITEM',5)->nullable(); // 
                $table->string('d_anaconda_id')->nullable(); // 
                $table->date('vstdate')->nullable(); // 
                $table->string('user_id')->nullable(); //  
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
        Schema::dropIfExists('d_dru');
    }
};
