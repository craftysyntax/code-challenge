@extends('layouts.app')

@section('content')
    <div class="container px-4 mx-auto">

        <div class="flex items-center justify-between mb-4">
            <h1 class="text-2xl font-bold">Edit Doctor: {{ $doctor->name }}</h1>
        </div>

        <form action="{{ route('doctors.update', $doctor) }}" method="post">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="name" class="block mb-2">Name</label>
                <input type="text" name="name" id="name" value="{{ old('name', $doctor->name) }}"
                    class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline">
                @error('name')
                    <p class="mt-1 text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="specialty" class="block mb-2">Specialty</label>
                <input type="text" name="specialty" id="specialty" value="{{ old('specialty', $doctor->specialty) }}"
                    class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline">
                @error('specialty')
                    <p class="mt-1 text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="clinic_name" class="block mb-2">Clinic Name</label>
                <select name="clinic_id" id="">
                    @foreach ($clinics as $clinic)
                        @if($clinic->clinic != "" && $doctor->clinic_id != $clinic->id)
                            <option value="{{ $clinic->id }}" @if($doctor->clinic_id == $clinic->id) selected @endif>{{ $clinic->clinic }}</option>
                        @endif
                    @endforeach
                </select>
                @error('clinic_name')
                    <p class="mt-1 text-red-500">{{ $message }}</p>
                @enderror
            </div>



            <button type="submit" class="px-4 py-2 font-bold text-white bg-blue-500 rounded hover:bg-blue-700">Update
                Doctor</button>
        </form>
    </div>
@endsection
