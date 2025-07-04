<?php 

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tow_requests', function (Blueprint $table) {
            $table->integer('completed_cycles')->default(0)->after('status');
        });
    }

    public function down()
    {
        Schema::table('tow_requests', function (Blueprint $table) {
            $table->dropColumn('completed_cycles');
        });
    }
};
