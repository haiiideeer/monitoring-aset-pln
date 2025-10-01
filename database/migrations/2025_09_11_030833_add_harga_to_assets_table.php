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
   public function up(): void
{
    Schema::table('assets', function (Blueprint $table) {
        $table->decimal('harga', 15, 2)->after('jumlah_aset')->nullable();
    });
}

public function down(): void
{
    Schema::table('assets', function (Blueprint $table) {
        $table->dropColumn('harga');
    });
}

};
