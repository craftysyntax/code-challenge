@extends('layouts.app')

@section('content')
    {{-- @php
    dd($doctor);
@endphp --}}

    <div class="container px-4 mx-auto">

        <div class="flex items-center justify-between mb-4">
            <h1 class="text-2xl font-bold">Doctor Detail: {{ $doctor->name }}</h1>
            <a href="{{ route('doctors.edit', $doctor) }}"
                class="px-4 py-2 font-bold text-white bg-blue-500 rounded hover:bg-blue-700">Edit Doctor</a>
        </div>

        <div class="mb-4">
            <strong>Name:</strong> {{ $doctor->name }}
        </div>
        <div class="mb-4">
            <strong>Specialty:</strong> {{ $doctor->specialty }}
        </div>

        <h2 class="mb-4 text-xl font-bold">Clinics</h2>

        @foreach ($doctor->clinics as $clinic)
            <div class="">
                @if ($clinic->name)
                    {{ $clinic->name }} -
                @endif
                {{ $clinic->address }}
            </div>
        @endforeach


        <h2 class="my-4 text-xl font-bold">Related Tests</h2>

        <livewire:tables.doctor-test-table :referring_doctor_id="$doctor->id">

    </div>
@endsection
