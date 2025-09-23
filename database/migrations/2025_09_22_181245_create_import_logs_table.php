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
        if (!Schema::hasTable('import_logs')) {
            Schema::create('import_logs', function (Blueprint $table) {
                $table->id();
                $table->string('import_type'); // drivers, escorts, delegations, delegates
                $table->string('file_name');
                $table->integer('row_number')->nullable();
                $table->text('error_message')->nullable();
                $table->json('row_data')->nullable();
                $table->string('status')->default('failed'); // success, failed
                $table->timestamps();
            });
        } else {
            // Add status column if it doesn't exist
            Schema::table('import_logs', function (Blueprint $table) {
                if (!Schema::hasColumn('import_logs', 'status')) {
                    $table->string('status')->default('failed');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('import_logs');
    }
};
