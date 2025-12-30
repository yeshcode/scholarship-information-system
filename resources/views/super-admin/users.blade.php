{{-- resources/views/super-admin/manage-users.blade.php --}}
@php $fullWidth = true; @endphp  {{-- Enable full-width for this page --}}
@extends('layouts.app')

@section('content')
<div class="p-6">  {{-- Padding for content --}}
    <h1 class="text-2xl font-bold mb-4">Manage Users</h1>
    <p class="mb-6">Manage users here. Data is loaded from your Supabase database.</p>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-4 mb-4 rounded-lg shadow-sm">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 text-red-800 p-4 mb-4 rounded-lg shadow-sm">{{ session('error') }}</div>
    @endif

    @if(isset($error))
        <div class="bg-red-100 text-red-800 p-4 mb-4 rounded-lg shadow-sm">{{ $error }}</div>
    @endif

    <!-- Buttons (Upper Right, Enhanced Design) -->
    <div class="flex justify-end mb-6 space-x-4">
        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center bg-black text-black hover:bg-gray-800 font-bold py-3 px-6 rounded-lg shadow-md transition duration-200">
            <span class="mr-2">+</span> Add User
        </a>
        <a href="{{ route('admin.users.bulk-upload-form') }}" class="inline-flex items-center bg-blue-600 text-black hover:bg-blue-800 font-bold py-3 px-6 rounded-lg shadow-md transition duration-200">
            <span class="mr-2">📤</span> Bulk Upload Students
        </a>
    </div>

    <!-- Search Bar (Filter by User ID, Name, Email, User Type, College, Year Level, Section, or Status) -->
    <div class="mb-6">
        <input type="text" id="searchInput" placeholder="Search by User ID, Name, Email, User Type, College, Year Level, Section, or Status..." class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>

    <!-- Table Card (Full-width, internal scrolling, compressed rows) -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="overflow-x-auto max-h-[calc(100vh-250px)] overflow-y-auto">  {{-- Strict height for internal scrolling --}}
            <table class="table-auto w-full border-collapse text-center min-w-full" id="usersTable">  {{-- Added ID for JS filtering --}}
                <thead class="bg-blue-200 text-black sticky top-0">  {{-- Light blue header --}}
                    <tr class="bg-gray-200">
                        <th class="border border-gray-300 px-3 py-2 font-bold text-sm uppercase tracking-wide">User ID</th>
                        <th class="border border-gray-300 px-3 py-2 font-bold text-sm uppercase tracking-wide">Name</th>
                        <th class="border border-gray-300 px-3 py-2 font-bold text-sm uppercase tracking-wide">Email</th>
                        <th class="border border-gray-300 px-3 py-2 font-bold text-sm uppercase tracking-wide">User Type</th>
                        <th class="border border-gray-300 px-3 py-2 font-bold text-sm uppercase tracking-wide">College</th>
                        <th class="border border-gray-300 px-3 py-2 font-bold text-sm uppercase tracking-wide">Year Level</th>
                        <th class="border border-gray-300 px-3 py-2 font-bold text-sm uppercase tracking-wide">Section</th>
                        <th class="border border-gray-300 px-3 py-2 font-bold text-sm uppercase tracking-wide">Status</th>
                        <th class="border border-gray-300 px-3 py-2 font-bold text-sm uppercase tracking-wide">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users ?? [] as $user)
                        <tr class="hover:bg-gray-50 transition duration-150 even:bg-gray-25">
                            <td class="border border-gray-300 px-3 py-2 text-gray-800">{{ $user->user_id }}</td>
                            <td class="border border-gray-300 px-3 py-2 text-gray-800">{{ $user->firstname }} {{ $user->lastname }}</td>
                            <td class="border border-gray-300 px-3 py-2 text-gray-800">{{ $user->bisu_email }}</td>
                            <td class="border border-gray-300 px-3 py-2 text-gray-800">{{ $user->userType->name ?? 'N/A' }}</td>
                            <td class="border border-gray-300 px-3 py-2 text-gray-800">{{ $user->college->college_name ?? 'N/A' }}</td>
                            <td class="border border-gray-300 px-3 py-2 text-gray-800">{{ $user->yearLevel->year_level_name ?? 'N/A' }}</td>
                            <td class="border border-gray-300 px-3 py-2 text-gray-800">{{ $user->section->section_name ?? 'N/A' }}</td>
                            <td class="border border-gray-300 px-3 py-2 text-gray-800">{{ $user->status }}</td>
                            <td class="border border-gray-300 px-3 py-2 space-x-2">
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="inline-flex items-center bg-blue-500 hover:bg-blue-600 text-black font-medium py-1 px-3 rounded shadow transition duration-200 text-sm">
                                    <span class="mr-1">✏️</span> Edit
                                </a>
                                <a href="{{ route('admin.users.delete', $user->id) }}" class="inline-flex items-center bg-red-500 hover:bg-red-600 text-black font-medium py-1 px-3 rounded shadow transition duration-200 text-sm">
                                    <span class="mr-1">🗑️</span> Delete
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    @if(empty($users))
                        <tr id="noResultsRow">
                            <td colspan="9" class="px-3 py-4 text-gray-500 text-center">No users found. <a href="{{ route('admin.users.create') }}" class="text-blue-500 underline hover:text-blue-700">Add one now</a>.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- JavaScript for Search Filtering -->
<script>
    document.getElementById('searchInput').addEventListener('input', function() {
        const query = this.value.toLowerCase();
        const rows = document.querySelectorAll('#usersTable tbody tr');
        let hasResults = false;

        rows.forEach(row => {
            if (row.id === 'noResultsRow') return;  // Skip the no-results row
            const cells = row.querySelectorAll('td');
            const userId = cells[0].textContent.toLowerCase();
            const name = cells[1].textContent.toLowerCase();
            const email = cells[2].textContent.toLowerCase();
            const userType = cells[3].textContent.toLowerCase();
            const college = cells[4].textContent.toLowerCase();
            const yearLevel = cells[5].textContent.toLowerCase();
            const section = cells[6].textContent.toLowerCase();
            const status = cells[7].textContent.toLowerCase();

            if (userId.includes(query) || name.includes(query) || email.includes(query) || userType.includes(query) || college.includes(query) || yearLevel.includes(query) || section.includes(query) || status.includes(query)) {
                row.style.display = '';
                hasResults = true;
            } else {
                row.style.display = 'none';
            }
        });

        // Show/hide no-results message
        const noResultsRow = document.getElementById('noResultsRow');
        noResultsRow.style.display = hasResults ? 'none' : '';
    });
</script>
@endsection