@extends('layouts.coordinator')

@section('page-content')
<h2 class="text-2xl font-bold mb-4">Confirm Delete Stipend Release</h2>
<p class="mb-4 text-red-600">Are you sure you want to delete this release? This action cannot be undone.</p>
<div class="bg-gray-100 p-4 rounded mb-4">
    <p><strong>Batch:</strong> {{ $release->scholarshipBatch->batch_number ?? 'N/A' }}</p>
    <p><strong>Title:</strong> {{ $release->title }}</p>
    <p><strong>Amount:</strong> {{ $release->amount }}</p>
    <p><strong>Status:</strong> {{ $release->status }}</p>
    <p><strong>Date Release:</strong> {{ $release->date_release }}</p>
    <p><strong>Notes:</strong> {{ $release->notes ?: 'None' }}</p>
</div>
<div class="flex space-x-4">
    <form action="{{ route('coordinator.stipend-releases.destroy', $release->id) }}" method="POST" class="inline">
        @csrf @method('DELETE')
        <button type="submit" class="bg-gray-500 text-white px-4 py-2 rounded">Yes, Delete</button>
    </form>
    <a href="{{ route('coordinator.manage-stipend-releases') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</a>
</div>
@endsection