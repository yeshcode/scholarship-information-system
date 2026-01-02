@extends('layouts.coordinator')

@section('page-content')
<h2 class="text-2xl font-bold mb-4">Edit Scholarship</h2>
<form action="{{ route('coordinator.scholarships.update', $scholarship->id) }}" method="POST">
    @csrf @method('PUT')
    <div class="mb-4">
        <label class="block text-gray-700 font-medium mb-2">Name</label>
        <input type="text" name="scholarship_name" value="{{ $scholarship->scholarship_name }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>
    <div class="mb-4">
        <label class="block text-gray-700 font-medium mb-2">Description</label>
        <textarea name="description" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ $scholarship->description }}</textarea>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700 font-medium mb-2">Requirements</label>
        <textarea name="requirements" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ $scholarship->requirements }}</textarea>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700 font-medium mb-2">Benefactor</label>
        <input type="text" name="benefactor" value="{{ $scholarship->benefactor }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>
    <div class="mb-4">
        <label class="block text-gray-700 font-medium mb-2">Status</label>
        <select name="status" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="open" {{ $scholarship->status == 'open' ? 'selected' : '' }}>Open</option>
            <option value="closed" {{ $scholarship->status == 'closed' ? 'selected' : '' }}>Closed</option>
        </select>
    </div>
    <button type="submit" class="bg-blue-500 text-black px-4 py-2 rounded hover:bg-blue-600 mr-2">Update</button>
    <a href="{{ route('coordinator.manage-scholarships') }}" class="bg-gray-500 text-black px-4 py-2 rounded">Cancel</a>
</form>
@endsection