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
        Schema::table('assets', function (Blueprint $table) {
            // Add missing columns if they don't exist
            if (!Schema::hasColumn('assets', 'kode_aset')) {
                $table->string('kode_aset')->unique()->after('id')->nullable();
            }
            
            if (!Schema::hasColumn('assets', 'unit')) {
                $table->string('unit')->after('bidang_id')->nullable();
            }
            
            if (!Schema::hasColumn('assets', 'harga')) {
                $table->decimal('harga', 15, 2)->after('jumlah_aset')->nullable()->default(0);
            }
            
            if (!Schema::hasColumn('assets', 'qr_code_path')) {
                $table->string('qr_code_path')->after('foto_aset')->nullable();
            }
            
            if (!Schema::hasColumn('assets', 'has_qr_code')) {
                $table->boolean('has_qr_code')->after('qr_code_path')->default(false);
            }
            
            // Update existing columns if needed
            if (Schema::hasColumn('assets', 'foto_aset')) {
                $table->text('foto_aset')->change(); // Change to TEXT to store JSON array
            }
            
            // Add indexes for better performance
            $table->index('kode_aset');
            $table->index('unit');
            $table->index('kondisi');
            $table->index(['bidang_id', 'kondisi']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            // Remove added columns
            $table->dropColumn([
                'kode_aset',
                'unit', 
                'harga',
                'qr_code_path',
                'has_qr_code'
            ]);
            
            // Remove indexes
            $table->dropIndex(['assets_kode_aset_index']);
            $table->dropIndex(['assets_unit_index']);
            $table->dropIndex(['assets_kondisi_index']);
            $table->dropIndex(['assets_bidang_id_kondisi_index']);
            $table->dropIndex(['assets_created_at_index']);
        });
    }
};