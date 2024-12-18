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
        Schema::table('attendancelogs', function (Blueprint $table) {
            $table->dropForeign(['student_id']);
            $table->dropForeign(['event_id']);

            $table->foreign('student_id')
                ->references('id')->on('students')
                ->onDelete('cascade');
            $table->foreign('event_id')
                ->references('id')->on('events')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('attendancelogs', function (Blueprint $table) {
            $table->dropForeign(['student_id']);
            $table->dropForeign(['event_id']);
        });
    }
};
