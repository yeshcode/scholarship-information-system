@extends('layouts.app')

@section('content')


<h1 class="text-2xl font-bold mb-4">Add College</h1>

@if(session('success'))
    <div class="bg-green-100 text-green-800 p-4 mb-4 rounded">{{ session('success') }}</div>
@endif

<form method="POST" action="{{ route('admin.colleges.store') }}">
    @csrf
    <input type="text" name="college_name" placeholder="College Name" class="border p-2 w-full mb-4" required>
    <button type="submit" class="bg-green-500 text-black px-4 py-2 rounded">Add College</button>
    <a href="{{ route('admin.dashboard', ['page' => 'colleges']) }}" class="ml-4 text-gray-500">Cancel</a>
</form>
@endsection