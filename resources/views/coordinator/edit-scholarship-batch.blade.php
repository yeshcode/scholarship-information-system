@extends('layouts.coordinator')

@section('page-content')
<h2 class="text-2xl font-bold mb-4">Edit Scholarship Batch</h2>
<form action="{{ route('coordinator.scholarship-batches.update', $batch->id) }}" method="POST">
    @csrf @method('PUT')
    <div class="mb-4">
        <label class="block text-gray-700 font-medium mb-2">Scholarship</label>
        <select name="scholarship_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            @foreach($scholarships as $scholarship)
                <option value="{{ $scholarship->id }}" {{ $batch->scholarship_id == $scholarship->id ? 'selected' : '' }}>{{ $scholarship->scholarship_name }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700 font-medium mb-2">Semester</label>
        <select name="semester_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            @foreach($semesters as $semester)
                <option value="{{ $semester->id }}" {{ $batch->semester_id == $semester->id ? 'selected' : '' }}>{{ $semester->term }} {{ $semester->academic_year }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700 font-medium mb-2">Batch Number</label>
        <input type="text" name="batch_number" value="{{ $batch->batch_number }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>
    <button type="submit" class="bg-blue-500 text-black px-4 py-2 rounded hover:bg-blue-600 mr-2">Update</button>
    <a href="{{ route('coordinator.scholarship-batches') }}" class="bg-gray-500 text-black px-4 py-2 rounded">Cancel</a>
</form>
@endsection