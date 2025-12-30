@extends('layouts.app')

@section('content')
<!-- Menu Bar (same as above) -->
<div class="bg-gray-800 text-white px-4 py-3 mb-6 rounded">
    <!-- ... copy the menu bar from colleges.blade.php ... -->
</div>

<h1 class="text-2xl font-bold mb-4">Edit College</h1>

@if(session('success'))
    <div class="bg-green-100 text-green-800 p-4 mb-4 rounded">{{ session('success') }}</div>
@endif

<form method="POST" action="{{ route('admin.colleges.update', $college->id) }}">
    @csrf @method('PUT')
    <input type="text" name="college_name" value="{{ $college->college_name }}" class="border p-2 w-full mb-4" required>
    <button type="submit" class="bg-yellow-500 text-black px-4 py-2 rounded">Update College</button>
    <a href="{{ route('admin.dashboard', ['page' => 'colleges']) }}" class="ml-4 text-gray-500">Cancel</a>
</form>

<form method="POST" action="{{ route('admin.colleges.destroy', $college->id) }}" class="mt-4">
    @csrf @method('DELETE')
    <button type="submit" class="bg-red-500 text-black px-4 py-2 rounded" onclick="return confirm('Delete?')">Delete College</button>
</form>
@endsection