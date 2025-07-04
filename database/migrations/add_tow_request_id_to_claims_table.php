<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('claims', function (Blueprint $table) {
            $table->unsignedBigInteger('tow_request_id')->nullable()->after('tow_service_accepted');
            $table->foreign('tow_request_id')->references('id')->on('tow_requests')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('claims', function (Blueprint $table) {
            $table->dropForeign(['tow_request_id']);
            $table->dropColumn('tow_request_id');
        });
    }
};