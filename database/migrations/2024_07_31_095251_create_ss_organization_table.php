<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSsOrganizationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ss_organizations', function (Blueprint $table) {
            $table->id();
            $table->string('id_organization')->nullable();
            $table->string('name');
            $table->string('identifier_system');
            $table->string('identifier_value');
            $table->string('telecom_phone')->nullable();
            $table->string('telecom_email')->nullable();
            $table->string('telecom_url')->nullable();
            $table->string('address_use')->nullable();
            $table->string('address_type')->nullable();
            $table->string('address_line')->nullable();
            $table->string('address_city')->nullable();
            $table->string('address_postal_code')->nullable();
            $table->string('address_country')->nullable();
            $table->string('address_province_code')->nullable();
            $table->string('address_city_code')->nullable();
            $table->string('address_district_code')->nullable();
            $table->string('address_village_code')->nullable();
            $table->uuid('part_of_id')->nullable();
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
        Schema::dropIfExists('ss_organizations');
    }
}
