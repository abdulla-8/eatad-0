<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('service_centers', function (Blueprint $table) {
            $table->boolean('has_tow_service')->default(true)->after('is_approved');
        });
    }

    public function down()
    {
        Schema::table('service_centers', function (Blueprint $table) {
            $table->dropColumn('has_tow_service');
        });
    }
};