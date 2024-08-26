<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SsPatient extends Model
{
    use HasFactory;

    protected $table = 'ss_patients';

    protected $fillable = [
        'resource_type',
        'meta',
        'identifier',
        'active',
        'name',
        'telecom',
        'gender',
        'birth_date',
        'deceased_boolean',
        'address',
        'marital_status',
        'multiple_birth_integer',
        'contact',
        'communication',
        'extension',
    ];

    protected $casts = [
        'meta' => 'array',
        'identifier' => 'array',
        'name' => 'array',
        'telecom' => 'array',
        'address' => 'array',
        'marital_status' => 'array',
        'contact' => 'array',
        'communication' => 'array',
        'extension' => 'array',
    ];
}
