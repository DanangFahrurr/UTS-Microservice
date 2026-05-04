<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hiker extends Model
{
    protected $fillable = [
        'name', 'email', 'phone', 'nik', 'birth_date',
        'gender', 'address', 'emergency_contact_name', 'emergency_contact_phone'
    ];

    public function medicalStatus()
    {
        return $this->hasOne(MedicalStatus::class);
    }

    public function hikingHistories()
    {
        return $this->hasMany(HikingHistory::class);
    }
}
