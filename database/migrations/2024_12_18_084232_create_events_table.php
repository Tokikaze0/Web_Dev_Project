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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name');            // Event name
            $table->date('date');              // Event date
            $table->string('location');        // Event location
            $table->timestamps();              // created_at and updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('events');
    }
};
