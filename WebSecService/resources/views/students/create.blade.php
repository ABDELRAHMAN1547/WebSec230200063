@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2>{{ isset($student) ? 'Edit Student' : 'Add New Student' }}</h2>

    <form action="{{ isset($student) ? url('/students/' . $student->id) : url('/students') }}" method="POST">
        @csrf
        @if(isset($student))
            @method('PUT')
        @endif

        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $student->name ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $student->email ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Age</label>
            <input type="number" name="age" class="form-control" value="{{ old('age', $student->age ?? '') }}" required>
        </div>

        <button type="submit" class="btn btn-success">{{ isset($student) ? 'Update' : 'Create' }}</button>
    </form>
</div>
@endsection
