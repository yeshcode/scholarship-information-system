@extends('layouts.coordinator')

@section('page-content')
@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
@endif

<h2 class="text-2xl font-bold mb-4">Confirm Add OCR Scholars</h2>
<p class="mb-4">Review the selected scholars below and choose a scholarship and batch to assign them.</p>

@if(session('selectedResults'))
    <form action="{{ route('coordinator.scholars.confirm-add-ocr.post') }}" method="POST">
        @csrf
        <input type="hidden" name="selected_results" value="{{ json_encode(session('selectedResults')) }}">
        
        <!-- Scholarship Dropdown -->
        <div class="mb-4">
            <label class="block text-gray-700">Select Scholarship</label>
            <select name="scholarship_id" required class="w-full px-3 py-2 border rounded">
                <option value="">Choose a scholarship...</option>
                @foreach($scholarships as $scholarship)
                    <option value="{{ $scholarship->id }}">{{ $scholarship->scholarship_name }} ({{ $scholarship->benefactor ?? 'N/A' }})</option>
                @endforeach
            </select>
        </div>
        
        <!-- Batch Dropdown -->
        <div class="mb-4">
            <label class="block text-gray-700">Select Scholarship Batch</label>
            <select name="batch_id" required class="w-full px-3 py-2 border rounded">
                <option value="">Choose a batch...</option>
                @foreach($batches as $batch)
                    <option value="{{ $batch->id }}">{{ $batch->batch_number }} ({{ $batch->semester->term ?? 'N/A' }} {{ $batch->semester->academic_year ?? '' }})</option>
                @endforeach
            </select>
        </div>
        
        <h3 class="mt-4 text-xl font-semibold">Selected Scholars to Add</h3>
        <table class="min-w-full bg-white border border-gray-300 mt-2">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 text-left">Last Name</th>
                    <th class="px-4 py-2 text-left">First Name</th>
                    <th class="px-4 py-2 text-left">Middle Name</th>
                    <th class="px-4 py-2 text-left">Student ID</th>
                    <th class="px-4 py-2 text-left">Year Level</th>  <!-- CORRECTED: Now "Year Level" -->
                    <th class="px-4 py-2 text-left">Course</th>
                    <th class="px-4 py-2 text-left">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach(session('selectedResults') as $result)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $result['data']['last_name'] ?? 'N/A' }}</td>
                        <td class="px-4 py-2">{{ $result['data']['first_name'] ?? 'N/A' }}</td>
                        <td class="px-4 py-2">{{ $result['data']['middlename'] ?? 'N/A' }}</td>
                        <td class="px-4 py-2">{{ $result['data']['student_id'] }}</td>
                        <td class="px-4 py-2">{{ $result['user']->yearLevel->year_level_name ?? 'N/A' }}</td>  <!-- CORRECTED: Now pulls from yearLevel -->
                        <td class="px-4 py-2">{{ $result['user']->section->course->course_name ?? 'N/A' }}</td>
                        <td class="px-4 py-2 text-green-600">Ready to Add</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded mt-4">Confirm and Add Scholars</button>
        <a href="{{ route('coordinator.scholars.ocr-upload') }}" class="bg-gray-500 text-black px-4 py-2 rounded ml-2">Back to OCR Upload</a>
    </form>
@else
    <p class="text-red-600">No scholars selected. Please go back and select scholars from the OCR results.</p>
    <a href="{{ route('coordinator.scholars.ocr-upload') }}" class="bg-blue-500 text-white px-4 py-2 rounded">Back to OCR Upload</a>
@endif
@endsection