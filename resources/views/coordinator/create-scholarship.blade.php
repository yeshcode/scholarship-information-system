@extends('layouts.coordinator')

@section('page-content')
<h2 class="text-2xl font-bold mb-4">Create Scholarship</h2>
<form action="{{ route('coordinator.scholarships.store') }}" method="POST">
    @csrf
    <div class="mb-4">
        <label class="block text-gray-700 font-medium mb-2">Name</label>
        <input type="text" name="scholarship_name" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>
    <div class="mb-4">
        <label class="block text-gray-700 font-medium mb-2">Description</label>
        <textarea name="description" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700 font-medium mb-2">Requirements</label>
        <textarea name="requirements" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700 font-medium mb-2">Benefactor</label>
        <input type="text" name="benefactor" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>
    <div class="mb-4">
        <label class="block text-gray-700 font-medium mb-2">Status</label>
        <select name="status" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="open">Open</option>
            <option value="closed">Closed</option>
        </select>
    </div>
    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 mr-2">Create</button>
    <a href="{{ route('coordinator.manage-scholarships') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</a>
</form>
@endsection