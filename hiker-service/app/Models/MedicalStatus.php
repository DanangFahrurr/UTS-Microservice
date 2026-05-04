<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalStatus extends Model
{
    protected $fillable = [
        'hiker_id', 'blood_type', 'has_heart_condition',
        'has_asma', 'has_hypertension', 'other_conditions', 'surat_kesehatan'
    ];

    protected $casts = [
        'has_heart_condition' => 'boolean',
        'has_asma' => 'boolean',
        'has_hypertension' => 'boolean',
    ];
}
