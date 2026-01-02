@extends('layouts.coordinator')

@section('page-content')
<h2 class="text-2xl font-bold mb-4">Create Stipend</h2>
<form action="{{ route('coordinator.stipends.store') }}" method="POST">
    @csrf
    <div class="mb-4">
        <label class="block text-gray-700 font-medium mb-2">Scholar</label>
        <select name="scholar_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            @foreach($scholars as $scholar)
                <option value="{{ $scholar->id }}">{{ $scholar->user->firstname }} {{ $scholar->user->lastname }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700 font-medium mb-2">Stipend Release</label>
        <select name="stipend_release_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            @foreach($releases as $release)
                <option value="{{ $release->id }}">{{ $release->title }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700 font-medium mb-2">Amount Received</label>
        <input type="number" step="0.01" name="amount_received" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>
    <div class="mb-4">
        <label class="block text-gray-700 font-medium mb-2">Status</label>
        <select name="status" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="for_release">For Release</option>
            <option value="released">Released</option>
            <option value="returned">Returned</option>
            <option value="waiting">Waiting</option>
        </select>
    </div>
    <button type="submit" class="bg-blue-500 text-black px-4 py-2 rounded hover:bg-blue-600 mr-2">Create</button>
    <a href="{{ route('coordinator.manage-stipends') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</a>
</form>
@endsection