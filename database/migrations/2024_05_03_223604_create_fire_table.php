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
        if (!Schema::hasTable('fire'))
        {
            Schema::create('fire', function (Blueprint $table) {
                $table->bigIncrements('fire_id'); 
                $table->string('fire_num')->nullable();  //           
                $table->string('fire_name')->nullable(); //  
                $table->string('fire_size')->nullable(); //  
                $table->string('fire_color')->nullable(); //  
                $table->string('fire_location')->nullable(); //  
                $table->string('fire_qty')->nullable(); //
                $table->string('fire_unit')->nullable(); //
                $table->string('fire_comment')->nullable(); //   
                $table->binary('fire_img')->nullable(); //                 
                $table->string('fire_imgname')->nullable(); //
                $table->enum('active', ['N','R','Y'])->default('Y');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fire');
    }
};
