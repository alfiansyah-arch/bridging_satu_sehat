<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SsLocation extends Model
{
    use HasFactory;

    protected $table = 'ss_locations';

    protected $fillable = [
        'id_location',
        'identifier',
        'status',
        'name',
        'description',
        'mode',
        'telecom',
        'address',
        'physical_type',
        'position_altitude',
        'position_latitude',
        'position_longitude',
        'managing_organization',
    ];

    protected $casts = [
        'identifier' => 'json',
        'telecom' => 'json',
        'address' => 'json',
        'physical_type' => 'json',
        'position_altitude' => 'float',
        'position_latitude' => 'float',
        'position_longitude' => 'float',
    ];
}