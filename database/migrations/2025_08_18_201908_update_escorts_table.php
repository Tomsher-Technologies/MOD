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
        Schema::table('escorts', function (Blueprint $table) {
            $table->renameColumn('name', 'name_en');
            $table->string('name_ar')->after('name_en');
            $table->string('phone_number')->nullable()->after('delegation_id');
            $table->string('email')->nullable()->after('phone_number');
            $table->foreignId('gender_id')->nullable()->constrained('dropdown_options')->after('email');
            $table->foreignId('nationality_id')->nullable()->constrained('dropdown_options')->after('gender_id');
            $table->date('date_of_birth')->nullable()->after('nationality_id');
            $table->string('id_number')->nullable()->after('date_of_birth');
            $table->date('id_issue_date')->nullable()->after('id_number');
            $table->date('id_expiry_date')->nullable()->after('id_issue_date');
            $table->boolean('status')->default(true)->after('id_expiry_date');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('escorts', function (Blueprint $table) {
            $table->renameColumn('name_en', 'name');
            $table->dropColumn('name_ar');
            $table->dropColumn('phone_number');
            $table->dropColumn('email');
            $table->dropForeign(['gender_id']);
            $table->dropColumn('gender_id');
            $table->dropForeign(['nationality_id']);
            $table->dropColumn('nationality_id');
            $table->dropColumn('date_of_birth');
            $table->dropColumn('id_number');
            $table->dropColumn('id_issue_date');
            $table->dropColumn('id_expiry_date');
            $table->dropColumn('status');
            $table->dropSoftDeletes();
        });
    }
};
