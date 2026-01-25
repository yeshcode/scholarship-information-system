@extends('layouts.coordinator')

@section('page-content')

<style>
    :root{
        --bisu-blue:#003366;
        --bisu-blue-2:#0b4a85;
    }
    .page-title-bisu{ font-weight:800; font-size:1.6rem; color:var(--bisu-blue); margin:0; }
    .subtext{ color:#6b7280; font-size:.9rem; }

    .btn-bisu{
        background:var(--bisu-blue) !important;
        border-color:var(--bisu-blue) !important;
        color:#fff !important;
        font-weight:700;
    }
    .btn-bisu:hover{ background:var(--bisu-blue-2) !important; border-color:var(--bisu-blue-2) !important; }

    .card-bisu{
        border:1px solid #e5e7eb;
        border-radius:14px;
        overflow:hidden;
    }
    .card-bisu .card-header{
        background:#fff;
        border-bottom:1px solid #eef2f7;
    }

    .form-label-bisu{
        font-weight:700;
        color:#475569;
        margin-bottom:.35rem;
        font-size:.85rem;
    }
</style>

{{-- Flash --}}
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        {{ session('error') }}
        <button class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- ✅ Validation Errors --}}
@if($errors->any())
    <div class="alert alert-danger">
        <div class="fw-bold mb-1">Please fix the following:</div>
        <ul class="mb-0">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- Header --}}
<div class="d-flex align-items-end justify-content-between flex-wrap gap-2 mb-3">
    <div>
        <h2 class="page-title-bisu">Create Stipend Release Schedule</h2>
        <div class="subtext">Pick a scholarship (TDP/TES), then choose a batch under it.</div>
    </div>

    <a href="{{ route('coordinator.manage-stipend-releases') }}" class="btn btn-outline-secondary btn-sm">
        ← Back
    </a>
</div>

<div class="card card-bisu shadow-sm">
    <div class="card-header">
        <div class="fw-bold text-secondary">Schedule Details</div>
    </div>

    <div class="card-body">
        <form action="{{ route('coordinator.stipend-releases.store') }}" method="POST">
            @csrf

            <div class="row g-3">

                {{-- Scholarship --}}
                <div class="col-12 col-md-6">
                    <label class="form-label-bisu">Scholarship (TDP/TES)</label>
                    <select name="scholarship_id" id="scholarship_id"
                            class="form-select form-select-sm @error('scholarship_id') is-invalid @enderror"
                            required>
                        <option value="">Select scholarship…</option>
                        @foreach($scholarships as $s)
                            <option value="{{ $s->id }}" {{ old('scholarship_id') == $s->id ? 'selected' : '' }}>
                                {{ $s->scholarship_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('scholarship_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <div class="form-text">Only scholarships that support batches will appear here.</div>
                </div>

                {{-- Batch --}}
                <div class="col-12 col-md-6">
                    <label class="form-label-bisu">Batch</label>
                    <select name="batch_id" id="batch_id"
                            class="form-select form-select-sm @error('batch_id') is-invalid @enderror"
                            required disabled>
                        <option value="">Select scholarship first…</option>
                    </select>
                    @error('batch_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <div id="batchHelp" class="form-text text-muted">Choose a scholarship to load batches.</div>
                </div>

                {{-- Title --}}
                <div class="col-12">
                    <label class="form-label-bisu">Schedule Title</label>
                    <input type="text" name="title"
                           class="form-control form-control-sm @error('title') is-invalid @enderror"
                           value="{{ old('title') }}"
                           required
                           placeholder="e.g., Stipend Release Schedule - Batch 13 (January)">
                    @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Amount --}}
                <div class="col-12 col-md-6">
                    <label class="form-label-bisu">Amount</label>
                    <input type="number" step="0.01" name="amount"
                           class="form-control form-control-sm @error('amount') is-invalid @enderror"
                           value="{{ old('amount') }}"
                           required
                           placeholder="e.g., 1750.00">
                    @error('amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Status --}}
                <div class="col-12 col-md-6">
                    <label class="form-label-bisu">Status</label>
                    <select name="status"
                            class="form-select form-select-sm @error('status') is-invalid @enderror"
                            required>
                        <option value="for_billing" {{ old('status')=='for_billing' ? 'selected' : '' }}>For Billing</option>
                        <option value="for_check"   {{ old('status')=='for_check' ? 'selected' : '' }}>For Check</option>
                        <option value="for_release" {{ old('status')=='for_release' ? 'selected' : '' }}>For Release</option>
                        <option value="received"    {{ old('status')=='received' ? 'selected' : '' }}>Received</option>
                    </select>
                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Notes --}}
                <div class="col-12">
                    <label class="form-label-bisu">Notes (optional)</label>
                    <textarea name="notes" rows="3"
                              class="form-control form-control-sm @error('notes') is-invalid @enderror"
                              placeholder="Optional notes or reminders…">{{ old('notes') }}</textarea>
                    @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-bisu btn-sm">
                    Create Schedule
                </button>
                <a href="{{ route('coordinator.manage-stipend-releases') }}" class="btn btn-outline-secondary btn-sm">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

@php
    $batchJs = $batches->map(function($b){
        return [
            'id' => $b->id,
            'scholarship_id' => $b->scholarship_id,
            'batch_number' => $b->batch_number,
            'term' => $b->semester->term ?? null,
            'academic_year' => $b->semester->academic_year ?? null,
        ];
    })->values();
@endphp

<script>
document.addEventListener('DOMContentLoaded', function () {

    const scholarshipSelect = document.getElementById('scholarship_id');
    const batchSelect = document.getElementById('batch_id');
    const batchHelp = document.getElementById('batchHelp');

    const batches = @json($batchJs);
    const oldBatchId = @json(old('batch_id')); // ✅ preserve on error

    function renderBatches(scholarshipId){
        batchSelect.innerHTML = '';
        const filtered = batches.filter(b => String(b.scholarship_id) === String(scholarshipId));

        if (!scholarshipId) {
            batchSelect.setAttribute('disabled', 'disabled');
            batchSelect.innerHTML = '<option value="">Select scholarship first…</option>';
            batchHelp.textContent = 'Choose a scholarship to load batches.';
            return;
        }

        if (filtered.length === 0) {
            batchSelect.setAttribute('disabled', 'disabled');
            batchSelect.innerHTML = '<option value="">No batches found for this scholarship</option>';
            batchHelp.textContent = 'No batches available. Create batches first.';
            return;
        }

        batchSelect.removeAttribute('disabled');
        batchHelp.textContent = '';

        batchSelect.innerHTML = '<option value="">Select batch…</option>';

        filtered.forEach(b => {
            const sem = `${b.term ?? ''} ${b.academic_year ?? ''}`.trim();
            const label = `Batch ${b.batch_number}${sem ? ' (' + sem + ')' : ''}`;

            const opt = document.createElement('option');
            opt.value = b.id;
            opt.textContent = label;

            // ✅ keep selection on reload
            if (oldBatchId && String(oldBatchId) === String(b.id)) {
                opt.selected = true;
            }

            batchSelect.appendChild(opt);
        });
    }

    scholarshipSelect.addEventListener('change', function(){
        renderBatches(this.value);
    });

    // ✅ auto-render on page load if scholarship already chosen (validation fail)
    if (scholarshipSelect.value) {
        renderBatches(scholarshipSelect.value);
    }

});
</script>

@endsection
