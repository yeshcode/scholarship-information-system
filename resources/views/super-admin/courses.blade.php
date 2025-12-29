

@section('content')


@if(session('success'))
    <div class="bg-green-100 text-green-800 p-4 mb-4 rounded">{{ session('success') }}</div>
@endif

<!-- Add Button -->
<a href="{{ route('admin.courses.create') }}" class="bg-white text-black border border-black hover:bg-gray-100 font-bold py-2 px-4 rounded mb-4 inline-block">
    Add Course
</a>

<!-- Table with Data -->
<div class="overflow-x-auto">
    <table class="table-auto w-full border-collapse border border-gray-300 text-center">
        <thead>
            <tr class="bg-gray-200">
                <th class="border border-gray-300 px-4 py-2">ID</th>
                <th class="border border-gray-300 px-4 py-2">Course Name</th>
                <th class="border border-gray-300 px-4 py-2">Course Description</th>  <!-- Added column -->
                <th class="border border-gray-300 px-4 py-2">College</th>
                <th class="border border-gray-300 px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($courses ?? [] as $course)
                <tr class="hover:bg-gray-100">
                    <td class="border border-gray-300 px-4 py-2">{{ $course->id }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $course->course_name }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $course->course_description ?? 'N/A' }}</td>  <!-- Added data -->
                    <td class="border border-gray-300 px-4 py-2">{{ $course->college->college_name ?? 'N/A' }}</td>
                    <td class="border border-gray-300 px-4 py-2">
                        <a href="{{ route('admin.courses.edit', $course->id) }}" class="text-blue-500 mr-2">Edit</a>
                        <form method="POST" action="{{ route('admin.courses.destroy', $course->id) }}" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500" onclick="return confirm('Delete?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection