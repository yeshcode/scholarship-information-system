@extends('layouts.coordinator')

@section('page-content')
@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

<div class="flex items-center justify-between mb-4">
    <h2 class="text-2xl font-bold">Manage Scholars</h2>

    <div class="flex gap-2">
        <a href="{{ route('coordinator.scholars.create') }}"
           class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
            Add Scholar
        </a>
        <a href="{{ route('coordinator.scholars.ocr-upload') }}"
           class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
            OCR Upload
        </a>
    </div>
</div>

{{-- Scholarship Buttons --}}
<div class="bg-white border border-gray-200 rounded p-3 mb-4">
    <div class="font-semibold text-gray-700 mb-2">Scholarships</div>

    <div class="flex flex-wrap gap-2">
        @foreach($scholarships as $s)
            <a href="{{ route('coordinator.scholars.by-scholarship', $s->id) }}"
               class="px-3 py-2 rounded border text-sm font-semibold
                      {{ isset($selectedScholarship) && $selectedScholarship->id == $s->id
                          ? 'bg-[#003366] text-white border-[#003366]'
                          : 'bg-white text-[#003366] border-[#003366] hover:bg-gray-100' }}">
                {{ $s->scholarship_name }}
                <span class="ml-1 text-xs opacity-80">({{ $s->scholars_count ?? 0 }})</span>
            </a>
        @endforeach
    </div>
</div>

{{-- MODE: BATCH LIST (TDP/TES) --}}
@if(($mode ?? null) === 'batches')
    <div class="bg-white border border-gray-200 rounded p-4 mb-4">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-lg font-bold text-[#003366]">
                    {{ $selectedScholarship->scholarship_name }} — Batch Numbers
                </div>
                <div class="text-sm text-gray-600">Click a batch to view scholars.</div>
            </div>

            <a href="{{ route('coordinator.manage-scholars') }}"
               class="text-sm font-semibold text-[#003366] hover:underline">
                Back to all
            </a>
        </div>

        <div class="mt-3 flex flex-wrap gap-2">
            @forelse($batches as $b)
                <a href="{{ route('coordinator.scholars.by-batch', $b->id) }}"
                   class="px-3 py-2 rounded border text-sm font-semibold
                          bg-white text-[#003366] border-[#003366] hover:bg-gray-100">
                    Batch {{ $b->batch_number }}
                    <span class="ml-1 text-xs opacity-80">({{ $b->scholars_count ?? 0 }})</span>
                </a>
            @empty
                <div class="text-gray-600 text-sm">
                    No current batch for this scholarship.
                </div>
            @endforelse
        </div>
    </div>
@endif

{{-- Scholar Table --}}
<div class="bg-white border border-gray-200 rounded overflow-hidden">
    <div class="px-4 py-3 border-b flex items-center justify-between">
        <div class="font-semibold text-[#003366]">
            @if(($mode ?? null) === 'batch' && isset($selectedBatch))
                Scholars in {{ $selectedScholarship->scholarship_name }} — Batch {{ $selectedBatch->batch_number }}
            @elseif(($mode ?? null) === 'scholarship' && isset($selectedScholarship))
                Scholars under {{ $selectedScholarship->scholarship_name }}
            @else
                Latest Scholars
            @endif
        </div>

        @if(($mode ?? null) !== null)
            <a href="{{ route('coordinator.manage-scholars') }}"
               class="text-sm font-semibold text-[#003366] hover:underline">
                Clear filter
            </a>
        @endif
    </div>

    <table class="w-full">
        <thead>
            <tr class="bg-gray-50 text-sm">
                <th class="px-4 py-2 text-left">Student Name</th>
                <th class="px-4 py-2 text-left">Student ID</th>
                <th class="px-4 py-2 text-left">Course</th>
                <th class="px-4 py-2 text-left">Scholarship</th>
                <th class="px-4 py-2 text-left">Batch No.</th>
                <th class="px-4 py-2 text-left">Status</th>
                <th class="px-4 py-2 text-left">Date Added</th>
            </tr>
        </thead>

        <tbody class="text-sm">
            @forelse($scholars ?? [] as $scholar)
                <tr class="border-t">
                    <td class="px-4 py-2">
                        {{ $scholar->user->firstname ?? 'N/A' }} {{ $scholar->user->lastname ?? 'N/A' }}
                    </td>
                    <td class="px-4 py-2">{{ $scholar->user->student_id ?? 'N/A' }}</td>
                    <td class="px-4 py-2">{{ $scholar->user->course->course_name ?? 'N/A' }}</td>
                    <td class="px-4 py-2">{{ $scholar->scholarship->scholarship_name ?? 'N/A' }}</td>

                    {{-- Batch only meaningful for TDP/TES, otherwise show — --}}
                    <td class="px-4 py-2">
                        {{ $scholar->scholarshipBatch->batch_number ?? '—' }}
                    </td>

                    <td class="px-4 py-2">{{ $scholar->status }}</td>
                    <td class="px-4 py-2">{{ $scholar->date_added }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="px-4 py-6 text-center text-gray-600">
                        No current scholar.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if(isset($scholars) && method_exists($scholars, 'links'))
    <div class="mt-4">
        {{ $scholars->links() }}
    </div>
@endif
@endsection
