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
        if (!Schema::hasTable('air_repaire'))
        {
            Schema::create('air_repaire', function (Blueprint $table) {
                $table->bigIncrements('air_repaire_id');
                $table->date('repaire_date')->nullable();  //
                $table->char('air_list_id', length: 10)->nullable();  //   
                $table->char('air_list_num', length: 200)->nullable();  //           
                $table->char('air_list_name', length: 200)->nullable(); //   
                $table->char('btu', length: 200)->nullable(); //
                $table->char('serial_no', length: 200)->nullable(); //
                $table->char('air_location_id', length: 200)->nullable(); //   
                $table->char('air_location_name', length: 200)->nullable();  // 
                
                $table->char('air_problems_a', length: 200)->nullable();  //  แอร์ไม่เย็น
                $table->char('air_problems_b', length: 200)->nullable();  // แอร์เสียงดัง
                $table->char('air_problems_c', length: 200)->nullable();  // แอร์มีน้ำหยด
                $table->char('air_problems_d', length: 200)->nullable();  // แอร์เปิดไม่ติด
                $table->char('air_problems_e', length: 200)->nullable();  // 
                $table->char('air_problems_f', length: 200)->nullable();  // 

                $table->enum('air_status_techout', ['N','R','Y'])->default('Y');   //    พร้อมใช้งาน /ไม่พร้อมใช้งาน
                $table->char('air_techout_name', length: 200)->nullable();          //     ช่างนอก
                $table->longText('air_techout_base')->nullable();  

                $table->enum('air_status_staff', ['N','R','Y'])->default('Y');   //    พร้อมใช้งาน /ไม่พร้อมใช้งาน
                $table->char('air_staff_id', length: 200)->nullable();          //     เจ้าหน้าที่หน้างานรับทราบ
                $table->longText('air_staff_base')->nullable();                //      ลายเซนเจ้าหน้าที่

                $table->enum('air_status_tech', ['N','R','Y'])->default('Y');   //    พร้อมใช้งาน /ไม่พร้อมใช้งาน
                $table->char('air_tech_id', length: 200)->nullable();          //     เจ้าหน้าที่หน้างานรับทราบ
                $table->longText('air_tech_base')->nullable();                //      ลายเซนเจ้าหน้าที่
                
                 
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('air_repaire');
    }
};
