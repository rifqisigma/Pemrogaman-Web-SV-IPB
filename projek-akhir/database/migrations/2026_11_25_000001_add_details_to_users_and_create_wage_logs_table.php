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
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->nullable()->after('name');
            $table->enum('role', ['admin', 'user'])->default('user')->after('password');
            $table->foreignId('provinsi_id')->nullable()->after('role')->constrained('provinsis')->nullOnDelete();
            $table->string('bidang_kerja')->nullable()->after('provinsi_id');
            $table->string('jabatan')->nullable()->after('bidang_kerja');
            $table->string('status_pekerjaan')->nullable()->after('jabatan');
            $table->decimal('gaji_pokok', 15, 2)->nullable()->after('status_pekerjaan');
        });

        Schema::create('wage_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('provinsi_id')->constrained('provinsis')->onDelete('cascade');
            $table->string('bidang_kerja');
            $table->string('jabatan');
            $table->string('status_pekerjaan')->nullable();
            $table->decimal('gaji_nominal', 15, 2);
            $table->decimal('gaji_sebelumnya', 15, 2)->nullable();
            $table->text('alasan_pembaruan');
            $table->unsignedSmallInteger('tahun');
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wage_logs');

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['provinsi_id']);
            $table->dropColumn([
                'username',
                'role',
                'provinsi_id',
                'bidang_kerja',
                'jabatan',
                'status_pekerjaan',
                'gaji_pokok',
            ]);
        });
    }
};
