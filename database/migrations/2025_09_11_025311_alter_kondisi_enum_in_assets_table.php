<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('assets', function (Blueprint $table) {
        DB::statement("ALTER TABLE assets MODIFY kondisi ENUM('Baik','Rusak','Perlu Perbaikan','Dalam Perbaikan')");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('assets', function (Blueprint $table) {
        DB::statement("ALTER TABLE assets MODIFY kondisi ENUM('Baik','Tidak Baik')");
        });
    }
};
