<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SsEncounter extends Model
{
    use HasFactory;

    protected $table = 'ss_encounters';

    protected $fillable = [
        'id_encounter',
        'status',
        'class_code',
        'class_display',
        'class_system',
        'subject_reference',
        'subject_display',
        'participant_type_code',
        'participant_type_display',
        'participant_type_system',
        'participant_individual_reference',
        'participant_individual_display',
        'period_start',
        'location_reference',
        'location_display',
        'service_provider_reference',
        'identifier_system',
        'identifier_value',
    ];
}