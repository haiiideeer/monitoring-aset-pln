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
        Schema::create('asets', function (Blueprint $table) {
        $table->id();
        $table->foreignId('bidang_id')->constrained()->onDelete('cascade');
        $table->string('nama_aset');
        $table->string('kode_aset')->unique();
        $table->string('lokasi');
        $table->text('keterangan')->nullable();
        $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('asets');
    }
};
