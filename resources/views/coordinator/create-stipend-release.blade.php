@extends('layouts.coordinator')

@section('page-content')
<h2 class="text-2xl font-bold mb-4">Create Stipend Release</h2>
<form action="{{ route('coordinator.stipend-releases.store') }}" method="POST">
    @csrf
    <div class="mb-4">
        <label class="block text-gray-700 font-medium mb-2">Batch</label>
        <select name="batch_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            @foreach($batches as $batch)
                <option value="{{ $batch->id }}">{{ $batch->batch_number }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700 font-medium mb-2">Title</label>
        <input type="text" name="title" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>
    <div class="mb-4">
        <label class="block text-gray-700 font-medium mb-2">Amount</label>
        <input type="number" step="0.01" name="amount" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>
    <div class="mb-4">
        <label class="block text-gray-700 font-medium mb-2">Status</label>
        <select name="status" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="pending">Pending</option>
            <option value="released">Released</option>
        </select>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700 font-medium mb-2">Date Release</label>
        <input type="date" name="date_release" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>
    <div class="mb-4">
        <label class="block text-gray-700 font-medium mb-2">Notes</label>
        <textarea name="notes" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
    </div>
    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 mr-2">Create</button>
    <a href="{{ route('coordinator.manage-stipend-releases') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</a>
</form>
@endsection