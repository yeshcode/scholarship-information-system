

@section('content')


@if(session('success'))
    <div class="bg-green-100 text-green-800 p-4 mb-4 rounded">{{ session('success') }}</div>
@endif

<!-- Add Button -->
<a href="{{ route('admin.semesters.create') }}" class="bg-white text-black border border-black hover:bg-gray-100 font-bold py-2 px-4 rounded mb-4 inline-block">
    Add Semester
</a>

<!-- Table with Data -->
<div class="overflow-x-auto">
    <table class="table-auto w-full border-collapse border border-gray-300 text-center">
        <thead>
            <tr class="bg-gray-200">
                <th class="border border-gray-300 px-4 py-2">ID</th>
                <th class="border border-gray-300 px-4 py-2">Term</th>
                <th class="border border-gray-300 px-4 py-2">Academic Year</th>
                <th class="border border-gray-300 px-4 py-2">Start Date</th>
                <th class="border border-gray-300 px-4 py-2">End Date</th>
                <th class="border border-gray-300 px-4 py-2">Is Current</th>
                <th class="border border-gray-300 px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($semesters ?? [] as $semester)
                <tr class="hover:bg-gray-100">
                    <td class="border border-gray-300 px-4 py-2">{{ $semester->id }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $semester->term }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $semester->academic_year }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $semester->start_date }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $semester->end_date }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $semester->is_current ? 'Yes' : 'No' }}</td>
                    <td class="border border-gray-300 px-4 py-2">
                        <a href="{{ route('admin.semesters.edit', $semester->id) }}" class="text-blue-500 mr-2">Edit</a>
                        <form method="POST" action="{{ route('admin.semesters.destroy', $semester->id) }}" style="display:inline;">
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