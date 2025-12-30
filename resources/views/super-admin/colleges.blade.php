{{-- resources/views/super-admin/colleges.blade.php --}}
@php $fullWidth = true; @endphp  {{-- Enable full-width for this page --}}
@extends('layouts.app')

@section('content')
<div class="p-6">  {{-- Padding for content --}}
    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-4 mb-4 rounded-lg shadow-sm">{{ session('success') }}</div>
    @endif

    <!-- Add College Button (Upper Right, Enhanced Design) -->
    <div class="flex justify-end mb-6">
        <a href="{{ route('admin.colleges.create') }}" class="inline-flex items-center bg-black text-black hover:bg-gray-800 font-bold py-3 px-6 rounded-lg shadow-md transition duration-200">
            <span class="mr-2">+</span> Add College
        </a>
    </div>

    <!-- Table Card (Full-width, internal scrolling, compressed rows) -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="overflow-x-auto max-h-[calc(100vh-200px)] overflow-y-auto">  {{-- Strict height for internal scrolling --}}
            <table class="table-auto w-full border-collapse text-center min-w-full">
                <thead class=" text-black sticky top-0">  {{-- Light blue header --}}
                    <tr class="bg-gray-200">
                        <th class="border border-gray-300 px-3 py-2 font-bold text-sm uppercase tracking-wide">College Name</th>
                        <th class="border border-gray-300 px-3 py-2 font-bold text-sm uppercase tracking-wide">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($colleges ?? [] as $college)
                        <tr class="hover:bg-gray-50 transition duration-150 even:bg-gray-25">
                            <td class="border border-gray-300 px-3 py-2 text-gray-800">{{ $college->college_name }}</td>
                            <td class="border border-gray-300 px-3 py-2 space-x-2">
                                <a href="{{ route('admin.colleges.edit', $college->id) }}" class="inline-flex items-center bg-blue-500 hover:bg-blue-600 text-black font-medium py-1 px-3 rounded shadow transition duration-200 text-sm">
                                    <span class="mr-1">✏️</span> Edit
                                </a>
                                <a href="{{ route('admin.colleges.delete', $college->id) }}" class="inline-flex items-center bg-red-500 hover:bg-red-600 text-black font-medium py-1 px-3 rounded shadow transition duration-200 text-sm">
                                    <span class="mr-1">🗑️</span> Delete
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    @if(empty($colleges))
                        <tr>
                            <td colspan="2" class="px-3 py-4 text-gray-500 text-center">No colleges found. <a href="{{ route('admin.colleges.create') }}" class="text-blue-500 underline hover:text-blue-700">Add one now</a>.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection