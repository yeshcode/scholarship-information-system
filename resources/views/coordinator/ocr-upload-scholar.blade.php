@extends('layouts.coordinator')

@section('page-content')
@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
@endif
<h2 class="text-2xl font-bold mb-4">Add Scholar (OCR Upload)</h2>
<p class="mb-4">Upload a PDF, Excel, or Image file containing a list of potential scholars. The system will analyze and match against existing students.</p>  <!-- UPDATED: Mention images -->
<form action="{{ route('coordinator.scholars.ocr-process') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="mb-4">
        <label class="block text-gray-700">Upload File (PDF, Excel, or Image)</label>  <!-- UPDATED: Mention images -->
        <input type="file" name="file" accept=".pdf,.xlsx,.xls,.jpg,.jpeg,.png,.gif,.bmp,.tiff" required class="w-full px-3 py-2 border rounded">  <!-- UPDATED: Accept images -->
    </div>
    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Process File</button>
    <a href="{{ route('coordinator.manage-scholars') }}" class="bg-gray-500 text-black px-4 py-2 rounded ml-2">Cancel</a>
</form>

@if(session('file_type') && in_array(session('file_type'), ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'tiff']) && session('extracted_data'))
<div class="mt-4">
    <h3 class="text-lg font-semibold mb-2">Scanned Data from OCR (All Columns and Rows)</h3>
    <p class="text-sm text-gray-600 mb-2">This shows the raw table extracted by OCR from the image. Review all columns/rows before checking matches below.</p>
    <table class="min-w-full bg-white border border-gray-300">
        <thead>
            <tr class="bg-gray-100">
                @if(count(session('extracted_data')) > 0)
                    @foreach(array_keys(session('extracted_data')[0]) as $header)
                        <th class="px-4 py-2 text-left">{{ ucfirst(str_replace('_', ' ', $header)) }}</th>
                    @endforeach
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach(session('extracted_data') as $row)
                <tr class="border-t">
                    @foreach($row as $value)
                        <td class="px-4 py-2">{{ $value }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

@if(session('results'))
    <table class="min-w-full bg-white border border-gray-300 mt-2">
        <thead>
            <tr class="bg-gray-100">
                <th class="px-4 py-2 text-left">Select</th>
                <th class="px-4 py-2 text-left">First Name</th>
                <th class="px-4 py-2 text-left">Middle Name</th>
                <th class="px-4 py-2 text-left">Last Name</th>
                <th class="px-4 py-2 text-left">Course</th>
                <th class="px-4 py-2 text-left">Year Level</th>
                <th class="px-4 py-2 text-left">Verified in Database</th>
                <th class="px-4 py-2 text-left">Reason/Details</th>
            </tr>
        </thead>
        <tbody>
            @foreach(session('results') as $index => $result)
                <tr class="border-t">
                    <td class="px-4 py-2">
                        @if($result['user'] && $result['is_enrolled'] && !$result['is_scholar'])
                            <input type="checkbox" name="selected_ids[]" value="{{ $index }}" class="form-checkbox">
                        @else
                            <span class="text-gray-400">N/A</span>
                        @endif
                    </td>
                    <td class="px-4 py-2">{{ $result['data']['first_name'] ?? '' }}</td>
                    <td class="px-4 py-2">{{ $result['data']['middlename'] ?? '' }}</td>
                    <td class="px-4 py-2">{{ $result['data']['last_name'] ?? '' }}</td>
                    <td class="px-4 py-2">{{ $result['data']['course'] ?? '' }}</td>
                    <td class="px-4 py-2">{{ $result['data']['year_level'] ?? '' }}</td>
                    <td class="px-4 py-2">
                        @if($result['user'])
                            <span class="text-green-600">Verified (Matched)</span>
                        @else
                            <span class="text-red-600">Not Verified (No Match)</span>
                        @endif
                    </td>
                    <td class="px-4 py-2">{{ $result['reason'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
@endsection