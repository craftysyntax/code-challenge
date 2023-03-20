<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Http\Requests\StoreDoctorRequest;
use App\Http\Requests\UpdateDoctorRequest;
use App\Models\Clinic;
use Illuminate\Support\Facades\DB;

class DoctorController extends Controller
{
    public function index()
    {
        return view('doctors.index');
    }

    public function create()
    {
        $clinics = Clinic::select([ 'id', DB::raw("name || ' - ' || address as clinic") ])->orderBy('clinic', 'ASC' )->get();
        return view('doctors.create', compact('clinics'));
    }

    public function store(StoreDoctorRequest $request)
    {
        Doctor::create($request->validated());
        return redirect()->route('doctors.index')->with('success', 'Doctor created successfully.');
    }

    public function show(Doctor $doctor)
    {
        return view('doctors.show', compact('doctor'));
    }

    public function edit(Doctor $doctor)
    {
        $clinics = Clinic::select([ 'id', DB::raw("name || ' - ' || address as clinic") ])->orderBy('clinic', 'ASC' )->get();
        return view('doctors.edit', compact('doctor', 'clinics'));
    }

    public function update(UpdateDoctorRequest $request, Doctor $doctor)
    {
        $doctor->update($request->validated());
        return redirect()->route('doctors.index')->with('success', 'Doctor updated successfully.');
    }
}
