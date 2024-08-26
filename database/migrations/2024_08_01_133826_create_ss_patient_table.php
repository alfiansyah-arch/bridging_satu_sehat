<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSsPatientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ss_patients', function (Blueprint $table) {
            $table->id();
            $table->string('resource_type')->default('Patient');
            $table->json('meta')->nullable();
            $table->json('identifier');
            $table->boolean('active')->default(true);
            $table->json('name');
            $table->json('telecom')->nullable();
            $table->string('gender');
            $table->date('birth_date');
            $table->boolean('deceased_boolean')->default(false);
            $table->json('address');
            $table->json('marital_status')->nullable();
            $table->integer('multiple_birth_integer')->nullable();
            $table->json('contact')->nullable();
            $table->json('communication')->nullable();
            $table->json('extension')->nullable();
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
        Schema::dropIfExists('ss_patients');
    }
}
