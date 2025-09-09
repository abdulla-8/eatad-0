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
        Schema::table('service_centers', function (Blueprint $table) {
            if (!Schema::hasColumn('service_centers', 'classification')) {
                $table->enum('classification', ['classified', 'unclassified'])->default('unclassified')->after('has_tow_service');
            }
            if (!Schema::hasColumn('service_centers', 'classification_photo')) {
                $table->string('classification_photo')->nullable()->after('classification');
            }
            if (!Schema::hasColumn('service_centers', 'classification_rating')) {
                $table->integer('classification_rating')->nullable()->after('classification_photo');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_centers', function (Blueprint $table) {
            if (Schema::hasColumn('service_centers', 'classification_rating')) {
                $table->dropColumn('classification_rating');
            }
            if (Schema::hasColumn('service_centers', 'classification_photo')) {
                $table->dropColumn('classification_photo');
            }
            if (Schema::hasColumn('service_centers', 'classification')) {
                $table->dropColumn('classification');
            }
        });
    }
}; 