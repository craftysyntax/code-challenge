@extends('layouts.app')

@section('content')
    <div class="container px-4 mx-auto">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-2xl font-bold">Doctors</h1>
            <a href="{{ route('doctors.create') }}"
                class="px-4 py-2 font-bold text-white bg-blue-500 rounded hover:bg-blue-700">Add Doctor</a>
        </div>

        <livewire:tables.doctor-table>

    </div>
@endsection
