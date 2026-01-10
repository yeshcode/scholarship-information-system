{{-- resources/views/super-admin/manage-users.blade.php --}}
@php $fullWidth = true; @endphp  {{-- Enable full-width for this page --}}
@extends('layouts.app')

<style>
    /* Custom styles for table design tracking */
    .table-header {
        background-color: #003366; /* Dark blue header */
        color: #003366; /* Dark blue text */
        font-weight: bold; /* Bold fonts for headers */
        border: 1px solid #007bff; /* Blue border */
    }
    .table-cell {
        color: #003366; /* Dark blue text */
        border: 1px solid #007bff; /* Blue border */
        white-space: nowrap; /* Prevent wrapping for single-line rows */
    }
    .table-hover:hover {
        background-color: #e0e7ff; /* Slightly darker light blue hover */
    }
    .table-bg {
        background-color: #f0f4f8; /* Light blue background for the whole table */
    }
</style>

@section('content')
<div class="bg-[#f0f4f8] min-h-screen">  {{-- Removed p-4 sm:p-6 to let table fit screen with layout's margins --}}
    <div class="w-full">  {{-- Removed mx-auto for full stretching without centering --}}
        <h1 class="text-xl sm:text-2xl font-bold mb-4 text-[#003366]">Manage Users</h1>  {{-- Dark blue title --}}
        <p class="mb-6 text-[#003366]">Manage users here. Data is loaded from your Supabase database.</p>

        @if(session('success'))
            <div class="bg-[#007bff] text-[#003366] p-4 mb-4 rounded-lg shadow-sm border border-[#003366]">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 text-[#003366] p-4 mb-4 rounded-lg shadow-sm border border-red-200">{{ session('error') }}</div>
        @endif

        @if(isset($error))
            <div class="bg-red-100 text-[#003366] p-4 mb-4 rounded-lg shadow-sm border border-red-200">{{ $error }}</div>
        @endif

        <!-- Buttons (Upper Right, Simple and Bold Blue Design) -->
        <div class="flex flex-col sm:flex-row justify-end mb-6 space-y-2 sm:space-y-0 sm:space-x-4">
            <a href="{{ route('admin.users.create') }}" class="inline-flex items-center bg-[#003366] text-[#f0f4f8] hover:bg-[#0056b3] font-bold py-3 px-6 rounded-lg shadow-md transition duration-200">
                <span class="mr-2">+</span> Add User
            </a>
            <a href="{{ route('admin.users.bulk-upload-form') }}" class="inline-flex items-center bg-[#007bff] text-[#003366] hover:bg-[#0056b3] font-bold py-3 px-6 rounded-lg shadow-md transition duration-200">
                <span class="mr-2">📤</span> Bulk Upload Students
            </a>
        </div>

        <!-- Search Bar (Filter by Name, Email, User Type, College, Year Level, Section, or Status) -->
        <div class="mb-6">
            <input type="text" id="searchInput" placeholder="Search by Name, Email, User Type, College, Year Level, Section, or Status..." class="w-full px-4 py-2 border border-[#007bff] rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#003366] bg-white text-[#003366]">
        </div>

        <!-- Table Card (Blue Theme with Stretching Columns) -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden border border-[#007bff]">
            <div class="overflow-x-auto max-h-[calc(100vh-250px)] overflow-y-auto table-bg">  {{-- Light blue background for table --}}
                <table class="table-auto w-full border-collapse text-center min-w-full" id="usersTable">  {{-- Auto layout for stretching --}}
                    <thead class="sticky top-0">
                        <tr class="table-header">
                            <th class="px-1 py-1 text-xs sm:text-sm uppercase tracking-wide">Name</th>
                            <th class="px-1 py-1 text-xs sm:text-sm uppercase tracking-wide">Email</th>
                            <th class="px-1 py-1 text-xs sm:text-sm uppercase tracking-wide">User Type</th>
                            <th class="px-1 py-1 text-xs sm:text-sm uppercase tracking-wide">College</th>
                            <th class="px-1 py-1 text-xs sm:text-sm uppercase tracking-wide">Year Level</th>
                            <th class="px-1 py-1 text-xs sm:text-sm uppercase tracking-wide">Section</th>
                            <th class="px-1 py-1 text-xs sm:text-sm uppercase tracking-wide">Status</th>
                            <th class="px-1 py-1 text-xs sm:text-sm uppercase tracking-wide">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users ?? [] as $user)
                            <tr class="table-cell table-hover transition duration-150 even:bg-[#f0f4f8]">
                                <td class="px-1 py-1">{{ $user->firstname }} {{ $user->lastname }}</td>
                                <td class="px-1 py-1">{{ $user->bisu_email }}</td>
                                <td class="px-1 py-1">{{ $user->userType->name ?? 'N/A' }}</td>
                                <td class="px-1 py-1">{{ $user->college->college_name ?? 'N/A' }}</td>
                                <td class="px-1 py-1">{{ $user->yearLevel->year_level_name ?? 'N/A' }}</td>
                                <td class="px-1 py-1">{{ $user->section->section_name ?? 'N/A' }}</td>
                                <td class="px-1 py-1">{{ $user->status }}</td>
                                <td class="px-1 py-1">
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="inline-flex items-center bg-[#007bff] hover:bg-[#0056b3] text-[#003366] font-medium py-1 px-2 rounded shadow transition duration-200 text-xs">✏️</a>
                                    <a href="{{ route('admin.users.delete', $user->id) }}" class="inline-flex items-center bg-red-500 hover:bg-red-600 text-[#003366] font-medium py-1 px-2 rounded shadow transition duration-200 text-xs ml-1">🗑️</a>
                                </td>
                            </tr>
                        @endforeach
                        @if(empty($users))
                            <tr id="noResultsRow">
                                <td colspan="8" class="px-1 py-4 text-[#003366] text-center">No users found. <a href="{{ route('admin.users.create') }}" class="text-[#007bff] underline hover:text-[#0056b3]">Add one now</a>.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
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
            const name = cells[0].textContent.toLowerCase();
            const email = cells[1].textContent.toLowerCase();
            const userType = cells[2].textContent.toLowerCase();
            const college = cells[3].textContent.toLowerCase();
            const yearLevel = cells[4].textContent.toLowerCase();
            const section = cells[5].textContent.toLowerCase();
            const status = cells[6].textContent.toLowerCase();

            if (name.includes(query) || email.includes(query) || userType.includes(query) || college.includes(query) || yearLevel.includes(query) || section.includes(query) || status.includes(query)) {
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