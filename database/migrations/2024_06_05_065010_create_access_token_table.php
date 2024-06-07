<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('access_token', function ($table) {
            $table->id();
            $table->string('token')->nullable();
            $table->string('expired')->nullable();
            $table->string('time_request')->nullable();
            $table->timestamps();
        });

        // Tambahkan satu baris data dengan kolom token yang berisi NULL
        DB::table('access_token')->insert([
            'token' => null,
            'expired' => null,
            'time_request' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('access_token');
    }
};
