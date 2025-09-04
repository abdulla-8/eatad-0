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
        Schema::table('vehicle_location_requests', function (Blueprint $table) {
            $table->foreignId('insurance_user_id')->nullable()->constrained('insurance_users')->onDelete('set null');
            $table->index('insurance_user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicle_location_requests', function (Blueprint $table) {
            //
        });
    }
};
