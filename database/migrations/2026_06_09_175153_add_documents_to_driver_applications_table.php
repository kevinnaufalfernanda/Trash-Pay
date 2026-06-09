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
        Schema::table('driver_applications', function (Blueprint $table) {
            $table->string('sim_photo')->nullable()->after('ktp_photo');
            $table->string('stnk_photo')->nullable()->after('sim_photo');
            $table->string('skck_photo')->nullable()->after('stnk_photo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('driver_applications', function (Blueprint $table) {
            $table->dropColumn(['sim_photo', 'stnk_photo', 'skck_photo']);
        });
    }
};
