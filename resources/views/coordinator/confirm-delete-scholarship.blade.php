@extends('layouts.coordinator')

@section('page-content')
<h2 class="text-2xl font-bold mb-4">Confirm Delete Scholarship</h2>
<p class="mb-4 text-red-600">Are you sure you want to delete this scholarship? This action cannot be undone.</p>
<div class="bg-gray-100 p-4 rounded mb-4">
    <p><strong>Name:</strong> {{ $scholarship->scholarship_name }}</p>
    <p><strong>Description:</strong> {{ $scholarship->description }}</p>
    <p><strong>Requirements:</strong> {{ $scholarship->requirements }}</p>
    <p><strong>Status:</strong> {{ $scholarship->status }}</p>
    <p><strong>Benefactor:</strong> {{ $scholarship->benefactor }}</p>
</div>
<div class="flex space-x-4">
    <form action="{{ route('coordinator.scholarships.destroy', $scholarship->id) }}" method="POST" class="inline">
        @csrf @method('DELETE')
        <button type="submit" class="bg-gray-500 text-black px-4 py-2 rounded">Yes, Delete</button>
    </form>
    <a href="{{ route('coordinator.manage-scholarships') }}" class="bg-gray-500 text-black px-4 py-2 rounded">Cancel</a>
</div>
@endsection