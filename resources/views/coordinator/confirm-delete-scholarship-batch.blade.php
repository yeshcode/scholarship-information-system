@extends('layouts.coordinator')

@section('page-content')
<h2 class="text-2xl font-bold mb-4">Confirm Delete Scholarship Batch</h2>
<p class="mb-4 text-red-600">Are you sure you want to delete this batch? This action cannot be undone.</p>
<div class="bg-gray-100 p-4 rounded mb-4">
    <p><strong>Scholarship:</strong> {{ $batch->scholarship->scholarship_name ?? 'N/A' }}</p>
    <p><strong>Semester:</strong> {{ $batch->semester->term ?? 'N/A' }} {{ $batch->semester->academic_year ?? '' }}</p>
    <p><strong>Batch Number:</strong> {{ $batch->batch_number }}</p>
</div>
<div class="flex space-x-4">
    <form action="{{ route('coordinator.scholarship-batches.destroy', $batch->id) }}" method="POST" class="inline">
        @csrf @method('DELETE')
        <button type="submit" class="bg-gray-500 text-black px-4 py-2 rounded">Yes, Delete</button>
    </form>
    <a href="{{ route('coordinator.scholarship-batches') }}" class="bg-gray-500 text-black px-4 py-2 rounded">Cancel</a>
</div>
@endsection