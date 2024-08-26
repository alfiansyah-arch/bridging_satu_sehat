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
        Schema::create('ss_locations', function (Blueprint $table) {
            $table->id();
            $table->string('id_location')->nullable();
            $table->string('description')->nullable();
            $table->json('identifier')->nullable();
            $table->string('managing_organization')->nullable();
            $table->timestamp('meta_last_updated')->nullable();
            $table->string('meta_version_id')->nullable();
            $table->string('mode')->nullable();
            $table->string('name')->nullable();
            $table->json('physical_type')->nullable();
            $table->float('position_altitude')->nullable();
            $table->float('position_latitude')->nullable();
            $table->float('position_longitude')->nullable();
            $table->string('resource_type')->nullable();
            $table->string('status')->nullable();
            $table->json('telecom')->nullable();
            $table->json('address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ss_locations');
    }
};
