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
        if (!Schema::hasTable('d_export'))
        {
            Schema::connection('mysql7')->create('d_export', function (Blueprint $table) {
                $table->bigIncrements('d_export_id');  
                $table->string('vn',255)->nullable(); 
                $table->string('hn',255)->nullable();   
                $table->string('an',255)->nullable(); 
                $table->string('cid',255)->nullable(); 
                $table->enum('active', ['Y','N'])->default('N')->nullable();  
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
        Schema::dropIfExists('d_export');
    }
};
