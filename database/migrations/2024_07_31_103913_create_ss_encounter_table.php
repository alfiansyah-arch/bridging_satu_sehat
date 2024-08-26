<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSsEncounterTable extends Migration
{
    public function up()
    {
        Schema::create('ss_encounters', function (Blueprint $table) {
            $table->id();
            $table->string('id_encounter');
            $table->string('status');
            $table->string('class_code');
            $table->string('class_display');
            $table->string('class_system');
            $table->string('subject_reference');
            $table->string('subject_display');
            $table->string('participant_type_code')->nullable();
            $table->string('participant_type_display')->nullable();
            $table->string('participant_type_system')->nullable();
            $table->string('participant_individual_reference')->nullable();
            $table->string('participant_individual_display')->nullable();
            $table->timestamp('period_start')->nullable();
            $table->string('location_reference')->nullable();
            $table->string('location_display')->nullable();
            $table->string('service_provider_reference')->nullable();
            $table->string('identifier_system')->nullable();
            $table->string('identifier_value')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ss_encounters');
    }
}
