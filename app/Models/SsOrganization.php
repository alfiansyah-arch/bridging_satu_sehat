<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SsOrganization extends Model
{
    use HasFactory;

    protected $table = 'ss_organizations';
    public $incrementing = false;
    protected $keyType = 'uuid';

    protected $fillable = [
        'id',
        'id_organization',
        'name',
        'identifier_system',
        'identifier_value',
        'telecom_phone',
        'telecom_email',
        'telecom_url',
        'address_use',
        'address_type',
        'address_line',
        'address_city',
        'address_postal_code',
        'address_country',
        'address_province_code',
        'address_city_code',
        'address_district_code',
        'address_village_code',
        'part_of_id'
    ];
}
