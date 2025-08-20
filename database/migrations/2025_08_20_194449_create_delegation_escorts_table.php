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
        Schema::create('delegation_escorts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delegation_id')->constrained()->onDelete('cascade');
            $table->foreignId('escort_id')->constrained()->onDelete('cascade');
            $table->boolean('status')->default(1);
            $table->foreignId('assigned_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delegation_escorts');
    }
};
