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
        Schema::create('log_requests', function (Blueprint $table) {
            $table->uuid('log_id')->primary(); // Primary key UUID

            $table->uuid('document_id')->index(); // UUID foreign key
            $table->foreign('document_id')->references('document_id')->on('documents')->onDelete('cascade');

            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('session_id')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('device')->nullable();

            $table->string('method', 10)->nullable();
            $table->string('referer')->nullable();
            $table->enum('aksi', ['detail', 'download'])->index();
            $table->string('tipe_data')->nullable();
            $table->float('durasi_akses')->nullable();

            $table->json('data_json')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_requests');
    }
};
