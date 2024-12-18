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
        Schema::create('attendancelogs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')
                ->constrained('students') // Link to the students table
                ->onDelete('cascade');    // Optionally delete attendance logs if student is deleted
            $table->foreignId('event_id')
                ->constrained('events')   // Link to the events table
                ->onDelete('cascade');    // Optionally delete attendance logs if event is deleted
            $table->timestamp('attended_at')->useCurrent();  // Timestamp for attendance
            $table->timestamps();                            // created_at and updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('attendancelogs');
    }
};
