<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('nama_aset');
            $table->foreignId('bidang_id')->constrained()->onDelete('cascade');
            $table->integer('jumlah_aset');
            $table->string('lokasi');
            $table->enum('kondisi', ['Baik', 'Rusak', 'Dalam Perbaikan', 'Perlu Perbaikan']);
            $table->date('tanggal_perolehan');
            $table->string('foto_aset')->nullable();
            $table->text('qr_code_path')->nullable(); // Path untuk menyimpan file QR code
            $table->boolean('has_qr_code')->default(false);
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('assets');
    }
};
