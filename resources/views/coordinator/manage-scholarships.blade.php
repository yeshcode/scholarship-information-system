@extends('layouts.coordinator')

@section('page-content')

<style>
    :root{
        /* ✅ Your system navy theme */
        --brand: #003366;
        --brand-2: #00284f;
        --soft: rgba(0,51,102,.10);
        --soft-2: rgba(0,51,102,.06);
        --border: rgba(0,0,0,.08);
    }

    .brand-text { color: var(--brand) !important; }
    .brand-bg { background: var(--brand) !important; }

    .page-head {
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 16px;
        background: #fff;
        box-shadow: 0 6px 18px rgba(0,0,0,.06);
    }

    .sch-card {
        border: 1px solid var(--border);
        border-radius: 18px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 10px 26px rgba(0,0,0,.08);
        transition: transform .15s ease, box-shadow .15s ease;
        min-height: 360px;   /* ✅ makes card taller */
    }   

    .sch-card:hover{
        transform: translateY(-2px);
        box-shadow: 0 10px 26px rgba(0,0,0,.10);
    }

    .sch-topbar { height: 6px; background: var(--brand); }

    .sch-meta {
        border: 1px solid var(--border);
        background: var(--soft-2);
        border-radius: 14px;
        padding: 16px;     /* ✅ more space */
    }

    .btn-brand{
        background: var(--brand);
        border-color: var(--brand);
        color: #fff;
    }
    .btn-brand:hover{
        background: var(--brand-2);
        border-color: var(--brand-2);
        color: #fff;
    }
    .btn-outline-brand{
        border-color: rgba(0,51,102,.35);
        color: var(--brand);
    }
    .btn-outline-brand:hover{
        background: var(--soft);
        border-color: rgba(0,51,102,.35);
        color: var(--brand);
    }

    .sch-label { font-size: .82rem; color: #6c757d; }
    .sch-value { font-weight: 600; }

    .info-box {
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 12px;
        background: #fff;
    }
    .info-title {
        font-weight: 700;
        margin-bottom: 6px;
        color: var(--brand);
    }

    .modal-edit-scholarship .modal-body{
        max-height: 75vh;
        overflow-y: auto;
    }
</style>

<div class="page-head mb-3">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
        <div>
            <h4 class="mb-1 fw-bold brand-text">Manage Scholarships</h4>
            <div class="text-muted">
                View scholarship info and update dates anytime if there is a new call for application.
            </div>
        </div>

        <a href="{{ route('coordinator.scholarships.create') }}" class="btn btn-brand">
            Add Scholarship
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row g-3">
    @forelse($scholarships as $scholarship)
        @php
            $isOpen = ($scholarship->status === 'open');

            $appDate = !empty($scholarship->application_date)
                ? \Carbon\Carbon::parse($scholarship->application_date)->format('M d, Y')
                : '—';

            $deadline = !empty($scholarship->deadline)
                ? \Carbon\Carbon::parse($scholarship->deadline)->format('M d, Y')
                : '—';

            $deadlineSoon = false;
            if (!empty($scholarship->deadline)) {
                $d = \Carbon\Carbon::parse($scholarship->deadline);
                $deadlineSoon = $d->isFuture() && $d->diffInDays(now()) <= 7;
            }
        @endphp

        <div class="col-12 col-lg-6 col-xl-4">

            <div class="sch-card h-100">
                <div class="sch-topbar"></div>

                <div class="card-body p-4">
                    <!-- Title + Status -->
                    <div class="d-flex justify-content-between align-items-start gap-2">
                        <div class="me-2">
                            <div class="sch-label">Title</div>
                            <h4 class="fw-bold mb-1">{{ $scholarship->scholarship_name }}</h4>
                        </div>

                        <span class="badge rounded-pill {{ $isOpen ? 'text-bg-success' : 'text-bg-secondary' }}">
                            {{ $isOpen ? 'OPEN' : 'CLOSED' }}
                        </span>
                    </div>

                    <!-- Benefactor -->
                    <div class="mt-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="sch-label">Benefactor</span>
                            <span class="sch-value text-end">{{ $scholarship->benefactor }}</span>
                        </div>
                    </div>

                     <!-- Small preview (optional but nice) -->
                    <div class="text-muted small mt-3">
                        {{ \Illuminate\Support\Str::limit($scholarship->description, 90) }}
                    </div>

                    <!-- Dates -->
                    <div class="sch-meta mt-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="sch-label">Application Date</span>
                            <span class="sch-value">{{ $appDate }}</span>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <span class="sch-label">Deadline</span>
                            <span class="sch-value">
                                {{ $deadline }}
                                @if($deadlineSoon)
                                    <span class="badge ms-2"
                                          style="background: rgba(255,193,7,.22); color:#7a5a00; border:1px solid rgba(255,193,7,.35);">
                                        Soon
                                    </span>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Buttons: ONLY Show + Edit -->
                <div class="card-footer bg-white border-0 pt-0 pb-3 px-3">
                    <div class="d-flex gap-2">
                        <button type="button"
                                class="btn btn-outline-brand w-100"
                                data-bs-toggle="modal"
                                data-bs-target="#showScholarshipModal{{ $scholarship->id }}">
                            Show
                        </button>

                        <button type="button"
                                class="btn btn-brand w-100"
                                data-bs-toggle="modal"
                                data-bs-target="#editScholarshipModal{{ $scholarship->id }}">
                            Edit
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ✅ SHOW MODAL (Full details) -->
        <div class="modal fade" id="showScholarshipModal{{ $scholarship->id }}" tabindex="-1"
             aria-labelledby="showScholarshipLabel{{ $scholarship->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content" style="border-radius: 16px; overflow:hidden;">
                    <div class="sch-topbar"></div>

                    <div class="modal-header">
                        <div>
                            <h5 class="modal-title fw-bold mb-0" id="showScholarshipLabel{{ $scholarship->id }}">
                                {{ $scholarship->scholarship_name }}
                            </h5>
                            <div class="text-muted small mt-1">
                                Benefactor: <b>{{ $scholarship->benefactor }}</b>
                                • Status:
                                <span class="badge rounded-pill {{ $isOpen ? 'text-bg-success' : 'text-bg-secondary' }}">
                                    {{ $isOpen ? 'OPEN' : 'CLOSED' }}
                                </span>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        @php
                            $sourceValue = trim((string)($scholarship->source ?? ''));
                            $isSourceUrl = \Illuminate\Support\Str::startsWith(strtolower($sourceValue), ['http://', 'https://']);
                        @endphp

                        <div class="row g-3 mb-3">
                            <div class="col-12 col-md-6">
                                <div class="sch-meta h-100">
                                    <div class="sch-label">Application Date</div>
                                    <div class="sch-value">{{ $appDate }}</div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="sch-meta h-100">
                                    <div class="sch-label">Deadline</div>
                                    <div class="sch-value">{{ $deadline }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="info-box mb-3">
                            <div class="info-title">Description</div>
                            <div class="text-muted" style="white-space: pre-wrap;">{{ $scholarship->description }}</div>
                        </div>

                        <div class="info-box mb-3">
                            <div class="info-title">Application Guide</div>
                            <div class="text-muted" style="white-space: pre-wrap;">
                                {{ $scholarship->application_guide ?: 'No application guide provided.' }}
                            </div>
                        </div>

                        <div class="info-box mb-3">
                            <div class="info-title">Requirements</div>
                            <div class="text-muted" style="white-space: pre-wrap;">{{ $scholarship->requirements }}</div>
                        </div>

                        <div class="info-box">
                            <div class="info-title">Source / Verification</div>

                            @if(!empty($sourceValue))
                                @if($isSourceUrl)
                                    <a href="{{ $sourceValue }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-primary mb-2">
                                        Open Source Link
                                    </a>
                                    <div class="text-muted small" style="word-break: break-word;">
                                        {{ $sourceValue }}
                                    </div>
                                @else
                                    <div class="text-muted">{{ $sourceValue }}</div>
                                @endif
                            @else
                                <div class="text-muted">No source provided.</div>
                            @endif
                        </div>
                    </div>

                    <div class="modal-footer d-flex justify-content-between">
                        <!-- Delete now opens confirm modal (no page) -->
                        <button type="button"
                                class="btn btn-outline-danger btn-open-confirm"
                                data-show-id="showScholarshipModal{{ $scholarship->id }}"
                                data-confirm-id="confirmDeleteScholarshipModal{{ $scholarship->id }}">
                            Delete
                        </button>

                        <div class="d-flex gap-2">
                            <button type="button"
                                    class="btn btn-brand btn-open-edit"
                                    data-show-id="showScholarshipModal{{ $scholarship->id }}"
                                    data-edit-id="editScholarshipModal{{ $scholarship->id }}">
                                Edit
                            </button>
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ✅ CONFIRM DELETE MODAL -->
        <div class="modal fade" id="confirmDeleteScholarshipModal{{ $scholarship->id }}" tabindex="-1"
             aria-labelledby="confirmDeleteScholarshipLabel{{ $scholarship->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="border-radius: 16px; overflow:hidden;">
                    <div class="sch-topbar"></div>

                    <div class="modal-header">
                        <h5 class="modal-title fw-bold" id="confirmDeleteScholarshipLabel{{ $scholarship->id }}">
                            Confirm Delete
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-2">
                            Are you sure you want to delete this scholarship?
                        </div>

                        <div class="sch-meta">
                            <div class="sch-label">Title</div>
                            <div class="sch-value">{{ $scholarship->scholarship_name }}</div>

                            <div class="sch-label mt-2">Benefactor</div>
                            <div class="sch-value">{{ $scholarship->benefactor }}</div>

                            <div class="sch-label mt-2">Application Date • Deadline</div>
                            <div class="sch-value">{{ $appDate }} • {{ $deadline }}</div>
                        </div>

                        <div class="text-muted small mt-3">
                            This action cannot be undone.
                        </div>
                    </div>

                    <div class="modal-footer d-flex justify-content-between">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                            Cancel
                        </button>

                        <form action="{{ route('coordinator.scholarships.destroy', $scholarship->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                Yes, Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- ✅ EDIT MODAL -->
        <div class="modal fade modal-edit-scholarship" id="editScholarshipModal{{ $scholarship->id }}" tabindex="-1"
            aria-labelledby="editScholarshipLabel{{ $scholarship->id }}" aria-hidden="true">
            <div class="modal-dialog modal-fullscreen-lg-down modal-xl modal-dialog-scrollable">
                <div class="modal-content" style="border-radius:16px; overflow:hidden;">
                    <div class="sch-topbar"></div>

                    <form action="{{ route('coordinator.scholarships.update', $scholarship->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="modal-header" style="background: var(--brand); color:#fff;">
                            <div>
                                <h5 class="modal-title fw-bold mb-0" id="editScholarshipLabel{{ $scholarship->id }}">
                                    Edit Scholarship
                                </h5>
                                <div class="small opacity-75 mt-1">
                                    Update scholarship details, guide, dates, source, and status.
                                </div>
                            </div>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            {{-- Scholarship Name --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold brand-text mb-1">Scholarship Name</label>
                                <input type="text"
                                    name="scholarship_name"
                                    value="{{ $scholarship->scholarship_name }}"
                                    class="form-control"
                                    required>
                            </div>

                            {{-- Benefactor + Status --}}
                            <div class="row g-3">
                                <div class="col-12 col-md-8">
                                    <label class="form-label fw-semibold brand-text mb-1">Benefactor</label>
                                    <input type="text"
                                        name="benefactor"
                                        value="{{ $scholarship->benefactor }}"
                                        class="form-control"
                                        required>
                                </div>

                                <div class="col-12 col-md-4">
                                    <label class="form-label fw-semibold brand-text mb-1">Status</label>
                                    <select name="status" class="form-select" required>
                                        <option value="open" {{ ($scholarship->status === 'open') ? 'selected' : '' }}>Open</option>
                                        <option value="closed" {{ ($scholarship->status === 'closed') ? 'selected' : '' }}>Closed</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Dates --}}
                            <div class="row g-3 mt-1">
                                <div class="col-12 col-md-6">
                                    <div class="sch-meta">
                                        <label class="form-label fw-semibold brand-text mb-1">Application Date</label>
                                        <input type="date"
                                            name="application_date"
                                            value="{{ $scholarship->application_date ? \Carbon\Carbon::parse($scholarship->application_date)->format('Y-m-d') : '' }}"
                                            class="form-control">
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="sch-meta">
                                        <label class="form-label fw-semibold brand-text mb-1">Deadline</label>
                                        <input type="date"
                                            name="deadline"
                                            value="{{ $scholarship->deadline ? \Carbon\Carbon::parse($scholarship->deadline)->format('Y-m-d') : '' }}"
                                            class="form-control">
                                    </div>
                                </div>
                            </div>

                            {{-- Description --}}
                            <div class="info-box mt-3">
                                <div class="info-title">Description</div>
                                <textarea name="description"
                                        class="form-control"
                                        rows="6"
                                        style="min-height:150px;"
                                        required>{{ $scholarship->description }}</textarea>
                            </div>

                            {{-- Application Guide --}}
                            <div class="info-box mt-3">
                                <div class="info-title">Application Guide</div>
                                <textarea name="application_guide"
                                        class="form-control"
                                        rows="6"
                                        style="min-height:150px;"
                                        placeholder="Example:
        1. Prepare all required documents.
        2. Submit to the scholarship office.">{{ $scholarship->application_guide }}</textarea>
                                <div class="form-text">
                                    Add a short guide so students know how to apply and what to prepare.
                                </div>
                            </div>

                            {{-- Requirements --}}
                            <div class="info-box mt-3">
                                <div class="info-title">Requirements</div>
                                <textarea name="requirements"
                                        class="form-control"
                                        rows="8"
                                        style="min-height:220px;"
                                        required>{{ $scholarship->requirements }}</textarea>
                            </div>

                            {{-- Source --}}
                            <div class="info-box mt-3">
                                <div class="info-title">Source / Verification</div>
                                <input type="text"
                                    name="source"
                                    value="{{ $scholarship->source }}"
                                    class="form-control"
                                    placeholder="Example: Facebook page, DOST page, or https://example.com/post">
                                <div class="form-text">
                                    You can type plain text or paste a direct link.
                                </div>
                            </div>

                            {{-- Errors --}}
                            @if($errors->any())
                                <div class="alert alert-danger mt-3 mb-0">
                                    <div class="fw-semibold">Please fix the errors:</div>
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $e)
                                            <li>{{ $e }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>

                        <div class="modal-footer bg-white">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                Cancel
                            </button>
                            <button type="submit" class="btn btn-brand">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    @empty
        <div class="col-12">
            <div class="alert alert-info mb-0">No scholarships found.</div>
        </div>
    @endforelse
</div>



<div class="mt-4">
    {{ $scholarships->links() }}
</div>

<!-- ✅ FIX stacked modal/backdrop issue -->
<script>
document.addEventListener('DOMContentLoaded', function () {

    // Close SHOW modal first, then open CONFIRM modal (prevents gray backdrop bug)
    document.querySelectorAll('.btn-open-confirm').forEach(btn => {
        btn.addEventListener('click', function () {
            const showId = this.getAttribute('data-show-id');
            const confirmId = this.getAttribute('data-confirm-id');

            const showEl = document.getElementById(showId);
            const confirmEl = document.getElementById(confirmId);
            if (!showEl || !confirmEl) return;

            const showModal = bootstrap.Modal.getOrCreateInstance(showEl);
            const confirmModal = bootstrap.Modal.getOrCreateInstance(confirmEl);

            const onHidden = () => {
                confirmModal.show();
                showEl.removeEventListener('hidden.bs.modal', onHidden);
            };

            showEl.addEventListener('hidden.bs.modal', onHidden);
            showModal.hide();
        });
    });

    // Safety clean: if no modals open, remove leftover backdrop
    document.addEventListener('hidden.bs.modal', function () {
        if (document.querySelectorAll('.modal.show').length === 0) {
            document.body.classList.remove('modal-open');
            document.body.style.removeProperty('padding-right');
            document.querySelectorAll('.modal-backdrop').forEach(b => b.remove());
        }
    });

    // ✅ Show -> Confirm delete (already yours)
  document.querySelectorAll('.btn-open-confirm').forEach(btn => {
    btn.addEventListener('click', function () {
      const showId = this.getAttribute('data-show-id');
      const confirmId = this.getAttribute('data-confirm-id');

      const showEl = document.getElementById(showId);
      const confirmEl = document.getElementById(confirmId);
      if (!showEl || !confirmEl) return;

      const showModal = bootstrap.Modal.getOrCreateInstance(showEl);
      const confirmModal = bootstrap.Modal.getOrCreateInstance(confirmEl);

      const onHidden = () => {
        confirmModal.show();
        showEl.removeEventListener('hidden.bs.modal', onHidden);
      };

      showEl.addEventListener('hidden.bs.modal', onHidden);
      showModal.hide();
    });
  });

  // ✅ Show -> Edit (NEW)
  document.querySelectorAll('.btn-open-edit').forEach(btn => {
    btn.addEventListener('click', function () {
      const showId = this.getAttribute('data-show-id');
      const editId = this.getAttribute('data-edit-id');

      const showEl = document.getElementById(showId);
      const editEl = document.getElementById(editId);
      if (!showEl || !editEl) return;

      const showModal = bootstrap.Modal.getOrCreateInstance(showEl);
      const editModal = bootstrap.Modal.getOrCreateInstance(editEl);

      const onHidden = () => {
        editModal.show();
        showEl.removeEventListener('hidden.bs.modal', onHidden);
      };

      showEl.addEventListener('hidden.bs.modal', onHidden);
      showModal.hide();
    });
  });

  // ✅ Backdrop safety clean (already yours)
  document.addEventListener('hidden.bs.modal', function () {
    if (document.querySelectorAll('.modal.show').length === 0) {
      document.body.classList.remove('modal-open');
      document.body.style.removeProperty('padding-right');
      document.querySelectorAll('.modal-backdrop').forEach(b => b.remove());
    }
  });

});
</script>

@endsection
