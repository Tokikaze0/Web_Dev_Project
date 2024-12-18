<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name');            // Name of the student
            $table->string('email')->unique(); // Email (unique for each student)
            $table->string('rfid')->unique();  // RFID (unique for each student)
            $table->timestamps();              // created_at and updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('students');
    }
};
