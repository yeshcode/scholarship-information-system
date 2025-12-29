


@if(session('success'))
    <div class="bg-green-100 text-green-800 p-4 mb-4 rounded">{{ session('success') }}</div>
@endif

<!-- Add Section Button (Right Side Above Table) -->
<div class="flex justify-end mb-4">
    <a href="{{ route('admin.sections.create') }}" class="bg-green-500 hover:bg-green-700 text-black font-bold py-2 px-4 rounded">
        Add Section
    </a>
</div>

<!-- Table with Data -->
<div class="overflow-x-auto">
    <table class="table-auto w-full border-collapse border border-gray-300 text-center">
        <thead>
            <tr class="bg-gray-200">
                <th class="border border-gray-300 px-4 py-2">ID</th>
                <th class="border border-gray-300 px-4 py-2">Course</th>
                <th class="border border-gray-300 px-4 py-2">Year Level</th>
                <th class="border border-gray-300 px-4 py-2">Section Name</th>
                <th class="border border-gray-300 px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sections ?? [] as $section)
                <tr class="hover:bg-gray-100">
                    <td class="border border-gray-300 px-4 py-2">{{ $section->id }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $section->course->course_name ?? 'N/A' }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $section->yearLevel->year_level_name ?? 'N/A' }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $section->section_name }}</td>
                    <td class="border border-gray-300 px-4 py-2">
                        <a href="{{ route('admin.sections.edit', $section->id) }}" class="text-blue-500 mr-2">Edit</a>
                        <form method="POST" action="{{ route('admin.sections.destroy', $section->id) }}" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500" onclick="return confirm('Are you sure you want to delete this section?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>