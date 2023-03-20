@extends('layouts.app')

@section('content')
    <div class="container px-4 mx-auto">
        <h1 class="mb-4 text-2xl font-bold">Clinics</h1>

        <livewire:tables.clinic-table>
    </div>
@endsection
