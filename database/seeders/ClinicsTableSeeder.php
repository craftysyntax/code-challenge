<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\Clinic;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClinicsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $doctors = Doctor::whereNull('clinic_id')->get();

        foreach ($doctors as $doctor) {
            $clinic = new Clinic();
            $clinic->name = $doctor->clinic_name;
            $clinic->address = $doctor->clinic_address;
            $clinic->save();

            $doctor->clinic_id = $clinic->id;
            $doctor->update();
        }
    }
}
