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
        Schema::create('kitchens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('code_id')->constrained('codes');
            $table->foreignId('unit_id')->constrained('units');
            $table->enum('type', ['in', 'out']);
            $table->string('kode_barang');
            $table->string('nama_barang');
            $table->integer('initial_stock');
            $table->integer('last_stock');
            $table->integer('qty')->nullable();
            $table->integer('in');
            $table->integer('out');
            $table->date('tanggal');
            $table->enum('priority', ['low', 'high']);
            $table->integer('minimum')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kitchens');
    }
};
