<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $with = [
        'clinics',
        'tests'
    ];

    public function tests()
    {
        return $this->hasMany(Test::class, 'referring_doctor_id');
    }

    public function clinics()
    {
        return $this->belongsToMany(Clinic::class, 'doctors_clinics', 'doctor_id', 'clinic_id');
    }
}
