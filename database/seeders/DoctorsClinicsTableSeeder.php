<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Doctor;

class DoctorsClinicsTableSeeder extends Seeder
{

    public function run(): void
    {
        $doctors = Doctor::where('clinic_id', '!=', null)->get();

        foreach ($doctors as $doctor) {
            $doctor->clinics()->attach($doctor->clinic_id);
        }
    }
}
