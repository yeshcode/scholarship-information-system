@extends('layouts.app')

@section('content')


<h1 class="text-2xl font-bold mb-4">Add User Type</h1>

@if(session('success'))
    <div class="bg-green-100 text-green-800 p-4 mb-4 rounded">{{ session('success') }}</div>
@endif

<form method="POST" action="{{ route('admin.user-types.store') }}">
    @csrf
    <input type="text" name="name" placeholder="Name" class="border p-2 w-full mb-4" required>
    <textarea name="description" placeholder="Description" class="border p-2 w-full mb-4" rows="3"></textarea>
    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Add User Type</button>
    <a href="{{ route('admin.dashboard', ['page' => 'user-type']) }}" class="ml-4 text-gray-500">Cancel</a>
</form>
@endsection