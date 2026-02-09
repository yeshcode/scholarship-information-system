@extends('layouts.coordinator')

@section('page-content')

<style>
  :root{ --bisu-blue:#003366; --bisu-blue-2:#0b4a85; }
  .card-bisu{ border:1px solid #e5e7eb; border-radius:14px; overflow:hidden; }
  .card-bisu .card-header{ background:#fff; border-bottom:1px solid #eef2f7; }
  .btn-bisu{
    background:var(--bisu-blue)!important;
    border-color:var(--bisu-blue)!important;
    color:#fff!important;
    font-weight:700;
  }
  .btn-bisu:hover{ background:var(--bisu-blue-2)!important; border-color:var(--bisu-blue-2)!important; }
  .label{ font-weight:700; color:#475569; font-size:.9rem; margin-bottom:.35rem; }
  .info-box{ background:#f8fafc; border:1px solid #e5e7eb; border-radius:10px; padding:.6rem .75rem; }
</style>

@if ($errors->any())
  <div class="alert alert-danger">
    <strong>Update failed:</strong>
    <ul class="mb-0">
      @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
    </ul>
  </div>
@endif

<div class="card card-bisu shadow-sm">
  <div class="card-header">
    <div class="fw-bold" style="color:var(--bisu-blue);">Edit Stipend</div>
    <small class="text-muted">Only Received At and Status can be changed.</small>
  </div>

  <div class="card-body">
    <form action="{{ route('coordinator.stipends.update', $stipend->id) }}" method="POST">
      @csrf
      @method('PUT')

      <div class="row g-3">

        {{-- INFORMATION ONLY --}}
        <div class="col-12 col-md-6">
          <div class="label">Scholar</div>
          <div class="info-box">
            {{ $stipend->scholar->user->lastname ?? '' }},
            {{ $stipend->scholar->user->firstname ?? '' }}
            <div class="small text-muted">
              Student ID: {{ $stipend->scholar->user->student_id ?? '—' }}
            </div>
          </div>
        </div>

        <div class="col-12 col-md-6">
          <div class="label">Release Schedule</div>
          <div class="info-box">
            {{ $stipend->stipendRelease->title ?? '—' }}
            <div class="small text-muted">
              Amount: {{ number_format((float)($stipend->stipendRelease->amount ?? 0), 2) }}
            </div>
          </div>
        </div>

        <div class="col-12 col-md-6">
          <div class="label">Current Amount (record)</div>
          <div class="info-box">
            {{ number_format((float)$stipend->amount_received, 2) }}
          </div>
        </div>

        {{-- EDITABLE ONLY --}}
        <div class="col-12 col-md-6">
          <label class="label">Status</label>
          <select name="status" class="form-select form-select-sm" required>
            <option value="for_release" {{ $stipend->status === 'for_release' ? 'selected' : '' }}>For Release</option>
            <option value="released" {{ $stipend->status === 'released' ? 'selected' : '' }}>Released</option>
          </select>
        </div>

        <div class="col-12 col-md-6">
          <label class="label">Received At</label>
          <input type="datetime-local"
                 name="received_at"
                 class="form-control form-control-sm"
                 value="{{ $stipend->received_at ? \Carbon\Carbon::parse($stipend->received_at)->format('Y-m-d\TH:i') : '' }}">
          <div class="form-text">If Status = Received, leaving this empty will auto-set to now.</div>
        </div>

      </div>

      <div class="mt-3 d-flex gap-2">
        <button type="submit" class="btn btn-bisu btn-sm">Update</button>
        <a href="{{ route('coordinator.manage-stipends') }}" class="btn btn-outline-secondary btn-sm">Cancel</a>
      </div>

    </form>
  </div>
</div>

@endsection
