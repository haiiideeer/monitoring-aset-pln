<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('bidangs', function (Blueprint $table) {
        $table->id();
        $table->string('nama_bidang');
        $table->string('kode_bidang')->unique();
        $table->string('slug')->unique();
        $table->text('qr_code_path')->nullable(); // Path untuk menyimpan file QR code
        $table->boolean('has_qr_code')->default(false);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bidangs');
    }
};
