@extends('layouts.coordinator')

@section('page-content')
@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
@endif
<h2 class="text-2xl font-bold mb-4">Manage Scholars</h2>
<div class="text-right mb-4">
    <a href="{{ route('coordinator.scholars.create') }}" class="bg-blue-500 text-black px-4 py-2 rounded mr-2">Add Scholar (Manual)</a>
    <a href="{{ route('coordinator.scholars.ocr-upload') }}" class="bg-green-500 text-black px-4 py-2 rounded">Add OCR (OCR)</a>
</div>
<table class="w-full bg-white border border-gray-200">
    <thead>
        <tr class="bg-gray-100">
            <th class="px-4 py-2">Student Name</th>
            <th class="px-4 py-2">Student ID</th>
            <th class="px-4 py-2">Section</th>  <!-- NEW -->
            <th class="px-4 py-2">Course</th>   <!-- NEW -->
            <th class="px-4 py-2">Scholarship Name</th>  <!-- NEW -->
            <th class="px-4 py-2">Batch No.</th>  <!-- NEW (renamed from Batch for clarity) -->
            <th class="px-4 py-2">Status</th>
            <th class="px-4 py-2">Date Added</th>
        </tr>
    </thead>
    <tbody>
        @forelse($scholars as $scholar)
            <tr class="border-t">
                <td class="px-4 py-2">{{ $scholar->user->firstname ?? 'N/A' }} {{ $scholar->user->lastname ?? 'N/A' }}</td>
                <td class="px-4 py-2">{{ $scholar->user->student_id ?? 'N/A' }}</td>
                <td class="px-4 py-2">{{ $scholar->user->section->section_name ?? 'N/A' }}</td>  <!-- NEW -->
                <td class="px-4 py-2">{{ $scholar->user->section->course->course_name ?? 'N/A' }}</td>  <!-- NEW -->
                <td class="px-4 py-2">{{ $scholar->scholarship->scholarship_name ?? 'N/A' }}</td>  <!-- NEW: Direct from scholarship -->
                <td class="px-4 py-2">{{ $scholar->scholarshipBatch->batch_number ?? 'N/A' }}</td>  <!-- NEW -->
                <td class="px-4 py-2">{{ $scholar->status }}</td>
                <td class="px-4 py-2">{{ $scholar->date_added }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="px-4 py-2 text-center">No scholars found.</td>  <!-- Updated colspan to 8 -->
            </tr>
        @endforelse
    </tbody>
</table>
{{ $scholars->links() }}
@endsection