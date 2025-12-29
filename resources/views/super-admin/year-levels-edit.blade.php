

@section('content')


<h1 class="text-2xl font-bold mb-4">Edit Year Level</h1>

@if(session('success'))
    <div class="bg-green-100 text-green-800 p-4 mb-4 rounded">{{ session('success') }}</div>
@endif

<form method="POST" action="{{ route('admin.year-levels.update', $yearLevel->id) }}">
    @csrf @method('PUT')
    <input type="text" name="year_level_name" value="{{ $yearLevel->year_level_name }}" class="border p-2 w-full mb-4" required>
    <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded">Update Year Level</button>
    <a href="{{ route('admin.dashboard', ['page' => 'year-levels']) }}" class="ml-4 text-gray-500">Cancel</a>
</form>

<form method="POST" action="{{ route('admin.year-levels.destroy', $yearLevel->id) }}" class="mt-4">
    @csrf @method('DELETE')
    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded" onclick="return confirm('Delete?')">Delete Year Level</button>
</form>
@endsection