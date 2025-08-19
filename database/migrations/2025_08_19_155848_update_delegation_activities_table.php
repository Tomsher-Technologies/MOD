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
        Schema::table('delegation_activities', function (Blueprint $table) {
            $table->string('submodule')->nullable()->after('module');
            $table->unsignedBigInteger('submodule_id')->nullable()->after('submodule');
            $table->unsignedBigInteger('delegation_id')->nullable()->after('submodule_id');
            $table->dropColumn('module_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('delegation_activities', function (Blueprint $table) {
            $table->string('module_id')->nullable();
            $table->dropColumn('submodule');
            $table->dropColumn('submodule_id');
            $table->dropColumn('delegation_id');
        });
    }
};