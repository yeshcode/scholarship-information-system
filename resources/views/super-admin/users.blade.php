{{-- resources/views/super-admin/manage-users.blade.php --}}
@php $fullWidth = true; @endphp  {{-- Enable full-width for this page --}}
@extends('layouts.app')

<style>
    /* Custom styles for table design tracking (blended with enrollment's borders) */
    .table-header {
        background-color: #003366; /* Dark blue header (your theme) */
        color: #003366; /* Dark blue text */
        font-weight: bold; /* Bold fonts for headers */
        border: 1px solid #007bff; /* Blue border */
    }
    .table-cell {
        color: #003366; /* Dark blue text */
        border: 1px solid #007bff; /* Blue border (matching enrollment's gray borders) */
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
<div class="p-6">  {{-- Padding for content (matching enrollment) --}}
    @if(session('success'))
        <div class="bg-[#007bff] text-[#003366] p-4 mb-4 rounded-lg shadow-sm border border-[#003366]">{{ session('success') }}</div>  {{-- Adapted to your theme --}}
    @endif

    @if(session('error'))
        <div class="bg-red-100 text-[#003366] p-4 mb-4 rounded-lg shadow-sm border border-red-200">{{ session('error') }}</div>
    @endif

    @if(isset($error))
        <div class="bg-red-100 text-[#003366] p-4 mb-4 rounded-lg shadow-sm border border-red-200">{{ $error }}</div>
    @endif

    <!-- Buttons (Upper Right, Simple and Bold Blue Design) -->
    <div class="flex justify-end mb-6 space-x-4">  {{-- Matching enrollment's button layout --}}
        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center bg-[#003366] text-[#f0f4f8] hover:bg-[#0056b3] font-bold py-3 px-6 rounded-lg shadow-md transition duration-200">
            <span class="mr-2">+</span> Add User
        </a>
        <a href="{{ route('admin.users.bulk-upload-form') }}" class="inline-flex items-center bg-[#007bff] text-[#003366] hover:bg-[#0056b3] font-bold py-3 px-6 rounded-lg shadow-md transition duration-200">
            <span class="mr-2">📤</span> Bulk Upload Students
        </a>
    </div>

    <!-- Search Bar (Filter by Name, Email, User Type, College, Year Level, Section, or Status) -->
    <div class="mb-6">
        <input type="text" id="searchInput" placeholder="Search by Name, Email, User Type, College, Year Level, Section, or Status..." class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">  {{-- Matching enrollment's search --}}
    </div>

    <!-- Table Card (Full-width, no scrolling, compressed rows, matching enrollment) -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">  {{-- Matching enrollment's card --}}
        <table class="table-auto w-full border-collapse text-center min-w-full" id="usersTable">  {{-- Matching enrollment's table setup --}}
            <thead class="bg-blue-200 text-black sticky top-0">  {{-- Light blue header (adapted from enrollment) --}}
                <tr class="bg-gray-200">
                    <th class="border border-gray-300 px-3 py-2 font-bold text-sm uppercase tracking-wide">Name</th>  {{-- Matching enrollment's th style --}}
                    <th class="border border-gray-300 px-3 py-2 font-bold text-sm uppercase tracking-wide">Email</th>
                    <th class="border border-gray-300 px-3 py-2 font-bold text-sm uppercase tracking-wide">Type</th>  {{-- Shortened --}}
                    <th class="border border-gray-300 px-3 py-2 font-bold text-sm uppercase tracking-wide">College</th>
                    <th class="border border-gray-300 px-3 py-2 font-bold text-sm uppercase tracking-wide">Year</th>  {{-- Shortened --}}
                    <th class="border border-gray-300 px-3 py-2 font-bold text-sm uppercase tracking-wide">Section</th>
                    <th class="border border-gray-300 px-3 py-2 font-bold text-sm uppercase tracking-wide">Status</th>
                    <th class="border border-gray-300 px-3 py-2 font-bold text-sm uppercase tracking-wide">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)  {{-- Pagination-compatible loop --}}
                    <tr class="hover:bg-gray-50 transition duration-150 even:bg-gray-25">  {{-- Matching enrollment's row hover --}}
                        <td class="border border-gray-300 px-3 py-2 text-gray-800">{{ $user->firstname }} {{ $user->lastname }}</td>  {{-- Matching enrollment's td style --}}
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
                @empty
                    <tr>
                        <td colspan="8" class="px-3 py-4 text-gray-500 text-center">No users found. <a href="{{ route('admin.users.create') }}" class="text-blue-500 underline hover:text-blue-700">Add one now</a>.</td>  {{-- Matching enrollment's no-results --}}
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination Links (Replaces Scrolling, Fixed to Stay on User Page) -->
    <div class="flex justify-center mt-4">
        {{ $users->appends(request()->query())->links() }} {{-- Pagination for vertical navigation --}}
    </div>
</div>

<!-- JavaScript for Search Filtering (Matching Enrollment's JS) -->
<script>
    document.getElementById('searchInput').addEventListener('input', function() {
        const query = this.value.toLowerCase();
        const rows = document.querySelectorAll('#usersTable tbody tr');
        let hasResults = false;

        rows.forEach(row => {
            if (row.cells.length < 8) return;  // Skip empty rows
            const cells = row.querySelectorAll('td');
            const name = cells[0].textContent.toLowerCase();
            const email = cells[1].textContent.toLowerCase();
            const type = cells[2].textContent.toLowerCase();
            const college = cells[3].textContent.toLowerCase();
            const year = cells[4].textContent.toLowerCase();
            const section = cells[5].textContent.toLowerCase();
            const status = cells[6].textContent.toLowerCase();

            if (name.includes(query) || email.includes(query) || type.includes(query) || college.includes(query) || year.includes(query) || section.includes(query) || status.includes(query)) {
                row.style.display = '';
                hasResults = true;
            } else {
                row.style.display = 'none';
            }
        });

        // Show/hide no-results message
        const noResultsRow = document.querySelector('#usersTable tbody tr:last-child');
        if (noResultsRow && noResultsRow.cells[0].colSpan === 8) {
            noResultsRow.style.display = hasResults ? 'none' : '';
        }
    });
</script>
@endsection