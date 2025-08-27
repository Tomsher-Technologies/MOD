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
        Schema::create('room_assignments', function (Blueprint $table) {
            $table->engine('InnoDB');
            $table->charset = 'utf8mb4';
            $table->id();
            $table->morphs('assignable'); // delegate, escort, or driver
            $table->unsignedBigInteger('hotel_id');
            $table->unsignedBigInteger('room_type_id');
            $table->string('room_number');
            $table->unsignedBigInteger('assigned_by'); // admin/staff id
            $table->timestamps();

            $table->foreign('hotel_id')->references('id')->on('accommodations');
            $table->foreign('room_type_id')->references('id')->on('accommodation_rooms');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_assignments');
    }
};
