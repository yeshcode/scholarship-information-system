@extends('layouts.coordinator')

@section('page-content')
<h2 class="text-2xl font-bold mb-4">Confirm Delete Stipend</h2>
<p class="mb-4 text-red-600">Are you sure you want to delete this stipend? This action cannot be undone.</p>
<div class="bg-gray-100 p-4 rounded mb-4">
    <p><strong>Scholar:</strong> {{ $stipend->scholar->user->firstname ?? 'N/A' }} {{ $stipend->scholar->user->lastname ?? '' }}</p>
    <p><strong>Release Title:</strong> {{ $stipend->stipendRelease->title ?? 'N/A' }}</p>
    <p><strong>Amount Received:</strong> {{ $stipend->amount_received }}</p>
    <p><strong>Status:</strong> {{ $stipend->status }}</p>
</div>
<div class="flex space-x-4">
    <form action="{{ route('coordinator.stipends.destroy', $stipend->id) }}" method="POST" class="inline">
        @csrf @method('DELETE')
        <button type="submit" class="bg-gray-500 text-black px-4 py-2 rounded">Yes, Delete</button>
    </form>
    <a href="{{ route('coordinator.manage-stipends') }}" class="bg-gray-500 text-black px-4 py-2 rounded">Cancel</a>
</div>
@endsection