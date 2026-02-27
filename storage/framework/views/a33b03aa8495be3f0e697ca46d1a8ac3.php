

<?php $__env->startSection('page-content'); ?>

<style>
    :root{
        --bisu-blue:#003366;
        --bisu-blue-2:#0b4a85;
        --danger:#dc3545;

         /* ✅ ADD GREEN THEME */
      --bisu-green:#198754;        /* Bootstrap green */
      --bisu-green-soft:#eaf7ef;   /* soft green background */
    }

    /* ✅ For Release badge - green, eye-friendly */
    .badge-release-green{
        background: var(--bisu-green-soft) !important;
        color: var(--bisu-green) !important;
        border: 1px solid rgba(25,135,84,.25);
        font-weight: 800;
    }

    /* default (non-for-release) badge look (neutral) */
    .badge-release-default{
        background: #f1f5f9 !important;
        color: #334155 !important;
        border: 1px solid #e2e8f0;
        font-weight: 700;
    }
    .page-title-bisu{ font-weight:800; font-size:1.6rem; color:var(--bisu-blue); margin:0; }
    .subtext{ color:#6b7280; font-size:.9rem; }

    .btn-bisu{
        background:var(--bisu-blue)!important;
        border-color:var(--bisu-blue)!important;
        color:#fff!important;
        font-weight:700;
    }
    .btn-bisu:hover{ background:var(--bisu-blue-2)!important; border-color:var(--bisu-blue-2)!important; }

    .card-bisu{ border:1px solid #e5e7eb; border-radius:14px; overflow:hidden; }
    .card-bisu .card-header{ background:#fff; border-bottom:1px solid #eef2f7; }

    .thead-bisu th{
        background:var(--bisu-blue)!important;
        color:#fff!important;
        font-size:.78rem;
        letter-spacing:.03em;
        text-transform:uppercase;
        white-space:nowrap;
    }
    .table td{ vertical-align:middle; white-space:nowrap; font-size:.9rem; }
    .filter-label{ font-weight:700; color:#475569; margin-bottom:.35rem; font-size:.85rem; }
    .req{ color:var(--danger); font-weight:900; margin-left:2px; }

    .row-disabled{
        background:#f1f5f9 !important;
        color:#94a3b8;
    }
    .row-disabled input[type="checkbox"]{ pointer-events:none; }
    .sticky-actions{
        position: sticky;
        bottom: 0;
        background: #fff;
        border-top: 1px solid #e5e7eb;
        padding: .75rem;
        z-index: 5;
    }

    /* Modal 2: keep footer visible */
    .modal-body-scroll{
        max-height: calc(100vh - 230px);
        overflow:auto;
    }

    /* Make search row separated */
    .filters-divider{ border-top:1px dashed #e5e7eb; margin-top:.75rem; padding-top:.75rem; }
</style>

<?php if($errors->any()): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <strong>Action failed:</strong>
        <ul class="mb-0">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($e); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
        <button class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if(session('success')): ?>
<div class="alert alert-success alert-dismissible fade show">
    <?php echo e(session('success')); ?>

    <button class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<?php if(session('error')): ?>
<div class="alert alert-danger alert-dismissible fade show">
    <?php echo e(session('error')); ?>

    <button class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="d-flex align-items-end justify-content-between flex-wrap gap-2 mb-3">
    <div>
        <h2 class="page-title-bisu">Manage Stipend Release Schedules</h2>
        <div class="subtext">
            Filter Scholarship → Batch → Release. Bulk-assign stipend schedules to eligible scholars.
        </div>

        <?php if(!empty($currentSemester)): ?>
            <div class="mt-1">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                    Current Semester:
                    <strong><?php echo e($currentSemester->term ?? $currentSemester->semester_name); ?> <?php echo e($currentSemester->academic_year); ?></strong>
                </span>
            </div>
        <?php endif; ?>
    </div>

    <div class="d-flex gap-2">
      <a class="btn btn-outline-primary btn-sm"
        href="<?php echo e(route('coordinator.stipends.claim-notifications')); ?>">
          Notifications
          <?php if(!empty($claimUnreadCount) && $claimUnreadCount > 0): ?>
              <span class="badge bg-danger ms-1"><?php echo e($claimUnreadCount); ?></span>
          <?php endif; ?>
      </a>
      
        <button class="btn btn-bisu btn-sm" id="openBulkBtn" data-bs-toggle="modal" data-bs-target="#bulkSelectModal">
            Schedule Release
        </button>
    </div>
</div>


<div class="card card-bisu shadow-sm mb-3">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div class="fw-bold text-secondary">Filters</div>
        <small class="text-muted">Scholarship • Batch • Status • Search</small>
    </div>

    <div class="card-body">
        <form id="filterForm" method="GET" action="<?php echo e(route('coordinator.manage-stipends')); ?>">
            
            <div class="row g-3">
                <div class="col-12 col-md-4">
                    <label class="filter-label">Scholarship</label>
                    <select name="scholarship_id" id="scholarship_id" class="form-select form-select-sm">
                        <option value="">All scholarships</option>
                        <?php $__currentLoopData = $scholarships; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($s->id); ?>" <?php echo e((string)request('scholarship_id')===(string)$s->id?'selected':''); ?>>
                                <?php echo e($s->scholarship_name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="col-12 col-md-4">
                    <label class="filter-label">Batch</label>
                    <select name="batch_id" id="batch_id" class="form-select form-select-sm">
                        <option value="">All batches</option>
                        <?php $__currentLoopData = $batches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($b->id); ?>" <?php echo e((string)request('batch_id')===(string)$b->id?'selected':''); ?>>
                                Batch <?php echo e($b->batch_number); ?>

                                (<?php echo e($b->semester->term ?? ''); ?> <?php echo e($b->semester->academic_year ?? ''); ?>)
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    
                </div>

                <div class="col-12 col-md-4">
                    <label class="filter-label">Stipend Status</label>
                    <select name="stipend_status" id="stipend_status" class="form-select form-select-sm">
                        <option value="">All</option>
                        <option value="for_release" <?php echo e(request('stipend_status')==='for_release'?'selected':''); ?>>For Release</option>
                        <option value="released"    <?php echo e(request('stipend_status')==='released'?'selected':''); ?>>Released</option>
                        <option value="returned"    <?php echo e(request('stipend_status')==='returned'?'selected':''); ?>>Returned</option>
                        <option value="waiting"     <?php echo e(request('stipend_status')==='waiting'?'selected':''); ?>>Waiting</option>

                    </select>

                </div>
            </div>

            
            <div class="row g-3 filters-divider">
                <div class="col-12 col-md-6">
                    <label class="filter-label">Search scholar</label>
                    <input type="text" name="q" id="q" class="form-control form-control-sm"
                        value="<?php echo e(request('q')); ?>" placeholder="Lastname / Firstname / Student ID">
                </div>

                <div class="col-12 col-md-6 d-flex align-items-end gap-2">
                    <button class="btn btn-bisu btn-sm" type="submit">Apply</button>
                    <a class="btn btn-outline-secondary btn-sm" href="<?php echo e(route('coordinator.manage-stipends')); ?>">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>


<div class="card card-bisu shadow-sm">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div class="fw-bold text-secondary">Stipend Records</div>
        <small class="text-muted">Showing created stipend rows</small>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover mb-0">
            <thead class="thead-bisu">
                <tr>
                    <th>Scholar</th>
                    <th>Scholarship</th>
                    <th>Batch</th>
                    <th>Release Title</th>
                    <th>Release Status</th>
                    <th>Release At</th>
                    <th>Received At</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $stipends; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stipend): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $rel = $stipend->stipendRelease;
                        $relStatusLabel = strtoupper(str_replace('_',' ', $rel->status ?? ''));
                    ?>
                    <tr>
                        <td><?php echo e($stipend->scholar->user->firstname ?? 'N/A'); ?> <?php echo e($stipend->scholar->user->lastname ?? ''); ?></td>
                        <td><?php echo e($stipend->scholar->scholarship->scholarship_name ?? 'N/A'); ?></td>
                        <td>Batch <?php echo e($stipend->scholar->scholarshipBatch->batch_number ?? 'N/A'); ?></td>
                        <td><?php echo e($rel->title ?? 'N/A'); ?></td>
                        <td>
                            <?php
                                $isForRelease = strtolower($rel->status ?? '') === 'for_release';
                            ?>

                            <span class="badge <?php echo e($isForRelease ? 'badge-release-green' : 'badge-release-default'); ?>">
                                <?php echo e($relStatusLabel ?: 'N/A'); ?>

                            </span>
                        </td>


                        <td>
                            <?php if($stipend->release_at): ?>
                                <?php echo e(\Carbon\Carbon::parse($stipend->release_at)->format('M d, Y h:i A')); ?>

                            <?php else: ?>
                                —
                            <?php endif; ?>
                        </td>

                        <td>
                            <?php if($stipend->received_at): ?>
                                <?php echo e(\Carbon\Carbon::parse($stipend->received_at)->format('M d, Y h:i A')); ?>

                            <?php else: ?>
                                —
                            <?php endif; ?>
                        </td>

                        <td><?php echo e(number_format((float)$stipend->amount_received, 2)); ?></td>
                        <td><?php echo e(strtoupper(str_replace('_',' ', $stipend->status))); ?></td>

                        <td class="text-end">
                            <?php $canRelease = $stipend->status === 'for_release'; ?>

                            <button
                                type="button"
                                class="btn btn-sm btn-success me-2 openReleaseModal"
                                data-bs-toggle="modal"
                                data-bs-target="#releaseStipendModal"
                                <?php echo e($canRelease ? '' : 'disabled'); ?>


                                data-stipend-id="<?php echo e($stipend->id); ?>"
                                data-student-name="<?php echo e(($stipend->scholar->user->firstname ?? 'N/A').' '.($stipend->scholar->user->lastname ?? '')); ?>"
                                data-student-id="<?php echo e($stipend->scholar->user->student_id ?? 'N/A'); ?>"
                                data-scholarship="<?php echo e($stipend->scholar->scholarship->scholarship_name ?? 'N/A'); ?>"
                                data-batch="<?php echo e($stipend->scholar->scholarshipBatch->batch_number ?? 'N/A'); ?>"
                                data-release-title="<?php echo e($stipend->stipendRelease->title ?? 'N/A'); ?>"
                                data-amount="<?php echo e(number_format((float)$stipend->amount_received, 2)); ?>"
                                data-default-date="<?php echo e($stipend->release_at ? \Carbon\Carbon::parse($stipend->release_at)->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')); ?>"
                            >
                                Release
                            </button>

                            <a href="<?php echo e(route('coordinator.stipends.edit', $stipend->id)); ?>" class="text-primary me-2">Edit</a>
                            <a href="<?php echo e(route('coordinator.stipends.confirm-delete', $stipend->id)); ?>" class="text-danger">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="10" class="text-center text-muted py-4">No stipend records found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="card-body">
        <?php echo e($stipends->links()); ?>

    </div>
</div>




<div class="modal fade" id="bulkSelectModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">

      <div class="modal-header" style="background:var(--bisu-blue); color:#fff;">
        <div>
          <div class="fw-bold">Bulk Assign Stipend — Step 1</div>
          <small class="opacity-75">Select Scholarship → Batch → Release Schedule, then pick scholars.</small>
        </div>
        <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <div class="row g-3 mb-3">

          <div class="col-12 col-md-4">
            <label class="filter-label">
                Scholarship <span class="req">*</span>
            </label>

            
            <select id="m_scholarship_id" class="form-select form-select-sm">
              <option value="">Select scholarship…</option>
              <?php $__currentLoopData = $scholarships; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $nm = strtoupper($s->scholarship_name ?? ''); ?>
                <?php if(str_contains($nm, 'TES') || str_contains($nm, 'TDP')): ?>
                  <option value="<?php echo e($s->id); ?>"><?php echo e($s->scholarship_name); ?></option>
                <?php endif; ?>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>

            <div class="form-text text-danger">Required.</div>
          </div>

          <div class="col-12 col-md-4">
            <label class="filter-label">
                Batch <span class="req">*</span>
            </label>
            <select id="m_batch_id" class="form-select form-select-sm" disabled>
              <option value="">Select scholarship first…</option>
              <?php $__currentLoopData = $batches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($b->id); ?>" data-sch="<?php echo e($b->scholarship_id); ?>">
                    Batch <?php echo e($b->batch_number); ?>

                </option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <div class="form-text text-danger">Required.</div>
          </div>

          <div class="col-12 col-md-4">
            <label class="filter-label">
                Release Schedule <span class="req">*</span>
            </label>
            <select id="m_release_id" class="form-select form-select-sm" disabled>
                <option value="">Select batch first…</option>
            </select>
            <div class="form-text text-danger">Required.</div>
          </div>

          <div class="col-12 col-md-4">
            <label class="filter-label">Search scholar</label>
            <input type="text" id="m_q" class="form-control form-control-sm" placeholder="Lastname / Firstname / Student ID" disabled>
            <div class="form-text">Search unlocks after you pick a Release Schedule.</div>
          </div>

          <div class="col-12">
            <div class="alert alert-info small mb-0">
              Eligible = <strong>ENROLLED or GRADUATED</strong> in the <strong>release semester</strong>.
              Ineligible records are shown but disabled (gray).
            </div>
          </div>
        </div>

        <div class="table-responsive">
          <table class="table table-bordered table-hover mb-0" id="scholarsPickTable">
            <thead class="thead-bisu">
              <tr>
                <th style="width:70px;">
                  <input type="checkbox" id="checkAllEligible">
                </th>
                <th>Student ID</th>
                <th>Last Name</th>
                <th>First Name</th>
                <th>Enrollment Status</th>
                <th>Scholarship</th>
                <th>Batch</th>
                <th>Note</th>
              </tr>
            </thead>

            <tbody>
              <?php $__currentLoopData = $eligibleScholars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="row-disabled"
                    data-sch="<?php echo e($row->scholarship_id); ?>"
                    data-batch="<?php echo e($row->batch_id); ?>"
                    data-scholar-id="<?php echo e($row->id); ?>"
                    data-name="<?php echo e(strtolower(($row->user->lastname ?? '').' '.($row->user->firstname ?? ''))); ?>"
                    data-studentid="<?php echo e(strtolower($row->user->student_id ?? '')); ?>"
                    data-selectable="0">

                  <td class="text-center"><span class="text-muted small">—</span></td>

                  <td><?php echo e($row->user->student_id ?? ''); ?></td>
                  <td><?php echo e($row->user->lastname ?? ''); ?></td>
                  <td><?php echo e($row->user->firstname ?? ''); ?></td>

                  <td>
                    <span class="badge statusBadge bg-secondary-subtle text-secondary"
                          data-active-label="<?php echo e($row->enrollment_status_label); ?>">
                      <?php echo e($row->enrollment_status_label); ?>

                    </span>
                  </td>

                  <td><?php echo e($row->scholarship->scholarship_name ?? ''); ?></td>
                  <td>Batch <?php echo e($row->scholarshipBatch->batch_number ?? ''); ?></td>

                  <td class="small text-muted">
                    <span class="noteText"><?php echo e($row->note); ?></span>
                  </td>

                </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
          </table>
        </div>

      </div>

      <div class="sticky-actions d-flex justify-content-between align-items-center">
        <div class="small text-muted">
          Selected scholars: <span class="fw-bold" id="selectedCount">0</span>
        </div>
        <div class="d-flex gap-2">
          <button class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal" type="button">Close</button>
          <button class="btn btn-bisu btn-sm" id="proceedToSchedule" type="button" disabled>
            Proceed →
          </button>
        </div>
      </div>

    </div>
  </div>
</div>


<div class="modal fade" id="bulkScheduleModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header" style="background:var(--bisu-blue); color:#fff;">
        <div>
          <div class="fw-bold">Bulk Assign Stipend — Step 2</div>
          <small class="opacity-75">Confirm release + set date/time. Status will be FOR RELEASE.</small>
        </div>
        <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <form method="POST" action="<?php echo e(route('coordinator.stipends.bulk-assign-v2')); ?>" id="bulkScheduleForm">
        <?php echo csrf_field(); ?>

        
        <input type="hidden" name="scholarship_id" id="s2_scholarship_id">
        <input type="hidden" name="batch_id" id="s2_batch_id">
        <input type="hidden" name="stipend_release_id" id="s2_release_id">

        <div class="modal-body modal-body-scroll">

          <div class="row g-3">
            <div class="col-12">
              <div class="alert alert-info small mb-0">
                Selected scholars: <strong id="s2_selectedCount">0</strong>
              </div>
            </div>

            
            <div class="col-12">
              <div class="small">
                <span class="text-danger fw-bold">Working release schedule:</span>
                <span class="fw-bold" id="s2_release_title">—</span>
              </div>
              <div class="form-text">This is automatically taken from Step 1.</div>
            </div>

            <div class="col-12">
              <label class="filter-label">Release Date & Time <span class="req">*</span></label>
              <input type="datetime-local"
                  name="release_at"
                  id="s2_release_at"
                  class="form-control form-control-sm"
                  value="<?php echo e(old('release_at')); ?>"
                  required>
              <div class="form-text text-danger">Required.</div>
            </div>

            <div class="col-12">
              <div class="alert alert-warning small mb-0">
                Amount will be taken from the selected release schedule. (No manual amount input.)
              </div>
            </div>
          </div>

          <hr class="my-3">

          <div class="fw-bold mb-2">Selected scholars preview</div>
          <div class="small text-muted mb-2">Preview list (first 30 shown):</div>
          <ul class="small" id="selectedPreviewList"></ul>

          <div id="selectedInputs"></div>
        </div>

        
        <div class="sticky-actions d-flex justify-content-end gap-2">
          <button class="btn btn-outline-secondary btn-sm" id="backToStep1" type="button">Back</button>
          <button class="btn btn-bisu btn-sm" type="submit">Submit & Notify Scholars</button>
        </div>
      </form>

    </div>
  </div>
</div>


<div class="modal fade" id="releaseStipendModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header" style="background: var(--bisu-green); color:#fff;">
        <div>
          <div class="fw-bold">Release Stipend</div>
          <small class="opacity-75">Confirm scholar details before releasing.</small>
        </div>
        <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <form method="POST" id="releaseStipendForm">
        <?php echo csrf_field(); ?>

        <div class="modal-body">
          <div class="alert alert-warning small mb-3">
            This action will mark the stipend as <strong>RELEASED</strong> and notify the student.
          </div>

          <div class="row g-3">
            <div class="col-12">
              <div class="card border-0" style="background: var(--bisu-green-soft);">
                <div class="card-body py-3">
                  <div class="fw-bold" id="rm_student_name">—</div>
                  <div class="small text-muted">
                    Student ID: <span class="fw-semibold" id="rm_student_id">—</span>
                  </div>
                  <div class="small text-muted">
                    Scholarship: <span class="fw-semibold" id="rm_scholarship">—</span> •
                    Batch: <span class="fw-semibold" id="rm_batch">—</span>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-12 col-md-8">
              <label class="filter-label">Release Schedule</label>
              <input type="text" class="form-control form-control-sm" id="rm_release_title" readonly>
            </div>

            <div class="col-12 col-md-4">
              <label class="filter-label">Amount</label>
              <input type="text" class="form-control form-control-sm" id="rm_amount" readonly>
            </div>

            <div class="col-12">
              <label class="filter-label">
                Release Date & Time <span class="req">*</span>
              </label>
              <input type="datetime-local" name="received_at" id="rm_received_at"
                     class="form-control form-control-sm" required>
              <div class="form-text">Auto-filled but you can edit.</div>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-dismiss="modal">Cancel</button>
          <button class="btn btn-success btn-sm" type="submit">Confirm Release & Notify</button>
        </div>

      </form>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

  // =========================
  // PAGE FILTERS: make them work
  // =========================
  const filterForm = document.getElementById('filterForm');
  const scholarshipFilter = document.getElementById('scholarship_id');
  const batchFilter = document.getElementById('batch_id');
  const statusFilter = document.getElementById('stipend_status');
  const qFilter = document.getElementById('q');

  function submitFiltersDebounced(){
    clearTimeout(window.__stipendFilterTT);
    window.__stipendFilterTT = setTimeout(() => filterForm.submit(), 300);
  }

  scholarshipFilter?.addEventListener('change', () => filterForm.submit());
  batchFilter?.addEventListener('change', () => filterForm.submit());
  statusFilter?.addEventListener('change', () => filterForm.submit());
  qFilter?.addEventListener('input', submitFiltersDebounced);

  // =========================
  // BULK MODALS
  // =========================
  const openBulkBtn   = document.getElementById('openBulkBtn');

  const selectModalEl   = document.getElementById('bulkSelectModal');
  const scheduleModalEl = document.getElementById('bulkScheduleModal');

  const mScholarship = document.getElementById('m_scholarship_id');
  const mBatch       = document.getElementById('m_batch_id');
  const mRelease     = document.getElementById('m_release_id');
  const mQ           = document.getElementById('m_q');

  const proceedBtn      = document.getElementById('proceedToSchedule');
  const selectedCountEl = document.getElementById('selectedCount');

  const checkAllEligible = document.getElementById('checkAllEligible');
  const table = document.getElementById('scholarsPickTable');

  // Step 2 fields
  const s2ScholarshipId  = document.getElementById('s2_scholarship_id');
  const s2BatchId        = document.getElementById('s2_batch_id');
  const s2ReleaseId      = document.getElementById('s2_release_id');
  const s2ReleaseTitle   = document.getElementById('s2_release_title');
  const s2SelectedCount  = document.getElementById('s2_selectedCount');
  const backToStep1Btn   = document.getElementById('backToStep1');

  // injectors
  const inputsWrap = document.getElementById('selectedInputs');
  const preview    = document.getElementById('selectedPreviewList');

  // endpoints
  const urlReleasesByBatch = <?php echo json_encode(route('coordinator.stipend-releases.by-batch'), 15, 512) ?>;
  const urlPickMeta        = <?php echo json_encode(route('coordinator.stipends.pick-meta'), 15, 512) ?>;

  function getRows(){ return Array.from(table.querySelectorAll('tbody tr')); }

  // =========================
  // META
  // =========================
  let metaEligible  = new Set();
  let metaBlocked   = new Set();
  let metaLoaded    = false;
  let metaStatusMap = {};

  // =========================
  // UI HELPERS
  // =========================
  function setRowDisabled(tr, reasonText, badgeLabel){
    tr.classList.add('row-disabled');
    tr.setAttribute('data-selectable', '0');

    const cell = tr.querySelector('td:first-child');
    if (cell) cell.innerHTML = '<span class="text-muted small">—</span>';

    const cb = tr.querySelector('.pickScholar');
    if (cb) cb.checked = false;

    const note = tr.querySelector('.noteText');
    if (note) note.textContent = reasonText || 'Not selectable';

    const badge = tr.querySelector('.statusBadge');
    if (badge) {
      const fallback = badge.getAttribute('data-active-label') || badge.textContent || '—';
      badge.textContent = badgeLabel || fallback;

      badge.classList.remove('bg-success-subtle','text-success');
      badge.classList.add('bg-secondary-subtle','text-secondary');
    }
  }

  function setRowEnabled(tr){
    tr.classList.remove('row-disabled');
    tr.setAttribute('data-selectable', '1');

    const sid = tr.getAttribute('data-scholar-id');

    const cell = tr.querySelector('td:first-child');
    if (cell) cell.innerHTML = `<input type="checkbox" class="pickScholar" value="${sid}">`;

    const note = tr.querySelector('.noteText');
    if (note) note.textContent = 'Selectable';

    const badge = tr.querySelector('.statusBadge');
    if (badge) {
      const fallback = badge.getAttribute('data-active-label') || '—';
      badge.textContent = fallback;

      badge.classList.remove('bg-secondary-subtle','text-secondary');
      badge.classList.add('bg-success-subtle','text-success');
    }
  }

  function getBlockType(tr){
  // if you already set noteText, use it to classify
  const note = (tr.querySelector('.noteText')?.textContent || '').toLowerCase();

  if (note.includes('already scheduled')) return 2; // bottom
  if (note.includes('not enrolled')) return 1;      // middle
  if (note.includes('selectable')) return 0;        // top

  // fallback: data-selectable
  return tr.getAttribute('data-selectable') === '1' ? 0 : 1;
}

function sortRowsInModal(){
  const tbody = table.querySelector('tbody');
  const rows = getRows();

  // Only sort visible rows, keep hidden rows at the end (still hidden)
  const visible = rows.filter(r => r.style.display !== 'none');
  const hidden  = rows.filter(r => r.style.display === 'none');

  visible.sort((a,b) => {
    const pa = getBlockType(a);
    const pb = getBlockType(b);
    if (pa !== pb) return pa - pb;

    // tie-breaker: lastname then firstname
    const al = (a.children[2]?.textContent || '').toLowerCase();
    const bl = (b.children[2]?.textContent || '').toLowerCase();
    if (al !== bl) return al.localeCompare(bl);

    const af = (a.children[3]?.textContent || '').toLowerCase();
    const bf = (b.children[3]?.textContent || '').toLowerCase();
    return af.localeCompare(bf);
  });

  // re-append in new order
  [...visible, ...hidden].forEach(r => tbody.appendChild(r));
}


  function applyEligibilityAndBlock(){
    const batchVal   = mBatch.value;
    const releaseVal = mRelease.value;

    if (!batchVal || !releaseVal || !metaLoaded) {
      getRows().forEach(tr => {
        if (tr.style.display === 'none') return;
        const bladeLabel = tr.querySelector('.statusBadge')?.getAttribute('data-active-label') || '—';
        setRowDisabled(tr, 'Select Scholarship → Batch → Release Schedule first.', bladeLabel);
      });
      return;
    }

    getRows().forEach(tr => {
      if (tr.style.display === 'none') return;
      if (tr.getAttribute('data-batch') !== batchVal) return;

      const sid = String(tr.getAttribute('data-scholar-id') || '');
      if (!sid) return;

      const bladeLabel = tr.querySelector('.statusBadge')?.getAttribute('data-active-label') || '—';
      const statusLabel = metaStatusMap[sid] || bladeLabel;

      // priority #1 blocked
      if (metaBlocked.has(sid)) {
        setRowDisabled(tr, 'Already scheduled for this semester.', 'ENROLLED');
        return;
      }

      // priority #2 not eligible
      if (!metaEligible.has(sid)) {
        setRowDisabled(tr, 'Not enrolled in the release semester.', statusLabel);
        return;
      }

      // eligible
      setRowEnabled(tr);
      const badge = tr.querySelector('.statusBadge');
      if (badge) badge.textContent = statusLabel;
    });

      // after you setRowEnabled / setRowDisabled for all rows
  sortRowsInModal();

  }

  function toLocalDatetimeValue(date = new Date()) {
    const pad = (n) => String(n).padStart(2, '0');
    return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}T${pad(date.getHours())}:${pad(date.getMinutes())}`;
  }

  function syncSelected(){
    const checked = Array.from(table.querySelectorAll('.pickScholar:checked'));
    selectedCountEl.textContent = checked.length;
    proceedBtn.disabled = checked.length === 0 || !mRelease.value;
  }

  // =========================
  // STEP 1 FILTERS
  // =========================
  function filterBatchOptions(){
    const sch = mScholarship.value;

    Array.from(mBatch.options).forEach(opt => {
      if (!opt.value) return;
      opt.hidden = sch && opt.getAttribute('data-sch') !== sch;
    });

    // enable batch only after scholarship chosen
    if (!sch) {
      mBatch.value = '';
      mBatch.disabled = true;
      mBatch.innerHTML = '<option value="">Select scholarship first…</option>' + mBatch.innerHTML.replace(/<option value="">.*?<\/option>/, '');
    } else {
      mBatch.disabled = false;
    }

    // if current selection hidden, clear
    if (mBatch.selectedOptions[0]?.hidden) mBatch.value = '';
  }

  function applyRowFilters(){
    const sch      = mScholarship.value;
    const batchVal = mBatch.value;
    const search   = (mQ.value || '').trim().toLowerCase();

    getRows().forEach(tr => {
      const trSch   = tr.getAttribute('data-sch');
      const trBatch = tr.getAttribute('data-batch');
      const name    = tr.getAttribute('data-name') || '';
      const sid     = tr.getAttribute('data-studentid') || '';

      let ok = true;
      if (sch && trSch !== sch) ok = false;
      if (batchVal && trBatch !== batchVal) ok = false;
      if (search && !(name.includes(search) || sid.includes(search))) ok = false;

      tr.style.display = ok ? '' : 'none';
    });

    applyEligibilityAndBlock();
    sortRowsInModal();
    syncSelected();
  }

  // =========================
  // AJAX
  // =========================
  async function loadReleasesForBatch(batchId){
    mRelease.innerHTML = '<option value="">Loading…</option>';
    mRelease.disabled = true;

    metaLoaded = false;
    metaEligible = new Set();
    metaBlocked = new Set();
    metaStatusMap = {};

    if (!batchId) {
      mRelease.innerHTML = '<option value="">Select batch first…</option>';
      applyEligibilityAndBlock();
      syncSelected();
      return;
    }

    try{
      const res = await fetch(`${urlReleasesByBatch}?batch_id=${encodeURIComponent(batchId)}`);
      const data = await res.json();

      if (!Array.isArray(data) || data.length === 0) {
        mRelease.innerHTML = '<option value="">No release schedules found</option>';
        return;
      }

      mRelease.innerHTML = '<option value="">Select release…</option>';
      data.forEach(r => {
        const opt = document.createElement('option');
        opt.value = r.id;
        opt.textContent = r.title;
        mRelease.appendChild(opt);
      });

      mRelease.disabled = false;

    } catch (e){
      console.error(e);
      mRelease.innerHTML = '<option value="">Failed to load releases</option>';
    }

    applyEligibilityAndBlock();
    syncSelected();
  }

  async function loadPickMeta(releaseId){
    metaLoaded = false;
    metaEligible = new Set();
    metaBlocked = new Set();
    metaStatusMap = {};

    if (!releaseId) {
      applyEligibilityAndBlock();
      syncSelected();
      return;
    }

    try{
      const res = await fetch(`${urlPickMeta}?release_id=${encodeURIComponent(releaseId)}`);
      const meta = await res.json();

      metaEligible  = new Set((meta.eligible_ids || []).map(String));
      metaBlocked   = new Set((meta.blocked_ids  || []).map(String));
      metaStatusMap = meta.status_map || {};
      metaLoaded = true;

      // unlock search only after release selected
      mQ.disabled = false;

    } catch (e){
      console.error(e);
      metaLoaded = false;
    }

    applyEligibilityAndBlock();
    syncSelected();
  }

  // =========================
  // RESET MODAL STATE ON CLOSE
  // =========================
  function resetBulkWizard(){
    // reset selects
    mScholarship.value = '';
    mBatch.value = '';
    mRelease.value = '';
    mQ.value = '';

    mBatch.disabled = true;
    mRelease.disabled = true;
    mQ.disabled = true;

    mRelease.innerHTML = '<option value="">Select batch first…</option>';

    // meta reset
    metaLoaded = false;
    metaEligible = new Set();
    metaBlocked = new Set();
    metaStatusMap = {};

    // reset selection
    checkAllEligible.checked = false;
    selectedCountEl.textContent = '0';
    proceedBtn.disabled = true;

    // show all rows (but disabled until selection)
    getRows().forEach(tr => tr.style.display = '');

    applyRowFilters();
  }

  selectModalEl.addEventListener('hidden.bs.modal', () => {
    // when user closes bulk wizard, start fresh next time
    resetBulkWizard();
    // return focus to open button (prevents aria-hidden warning)
    openBulkBtn?.focus();
  });

  scheduleModalEl.addEventListener('hidden.bs.modal', () => {
    // if they close Step 2 directly, also reset
    resetBulkWizard();
    openBulkBtn?.focus();
  });

  // =========================
  // EVENTS
  // =========================
  mScholarship.addEventListener('change', () => {
    filterBatchOptions();

    // reset downstream
    mRelease.innerHTML = '<option value="">Select batch first…</option>';
    mRelease.disabled = true;
    mQ.disabled = true;

    applyRowFilters();
  });

  mBatch.addEventListener('change', async () => {
    await loadReleasesForBatch(mBatch.value);
    applyRowFilters();
  });

  mRelease.addEventListener('change', async () => {
    await loadPickMeta(mRelease.value);
  });

  let tt=null;
  mQ.addEventListener('input', () => {
    clearTimeout(tt);
    tt=setTimeout(applyRowFilters, 200);
  });

  checkAllEligible?.addEventListener('change', () => {
    const rows = getRows().filter(tr =>
      tr.style.display !== 'none' && tr.getAttribute('data-selectable') === '1'
    );
    rows.forEach(tr => {
      const cb = tr.querySelector('.pickScholar');
      if (cb) cb.checked = checkAllEligible.checked;
    });
    syncSelected();
  });

  table.addEventListener('change', (e) => {
    if (e.target.classList.contains('pickScholar')) syncSelected();
  });

  // ✅ Proceed: fix aria-hidden focus warning by blurring before hide
  proceedBtn.addEventListener('click', () => {
    const checked = Array.from(table.querySelectorAll('.pickScholar:checked'));
    const ids = checked.map(cb => cb.value);

    if (!mScholarship.value || !mBatch.value || !mRelease.value) {
      alert('Please select Scholarship, Batch, and Release Schedule first.');
      return;
    }
    if (ids.length === 0) {
      alert('No scholars selected.');
      return;
    }

    // blur focused element to avoid aria-hidden warning
    try { document.activeElement?.blur(); } catch(e){}

    // Step 2 hidden fields
    s2ScholarshipId.value = mScholarship.value;
    s2BatchId.value       = mBatch.value;
    s2ReleaseId.value     = mRelease.value;

    // show release title
    const selectedOpt = mRelease.selectedOptions[0];
    s2ReleaseTitle.textContent = selectedOpt ? selectedOpt.textContent : '—';

    // count
    s2SelectedCount.textContent = ids.length;

    // build hidden scholar_ids[]
    inputsWrap.innerHTML = '';
    ids.forEach(id => {
      const inp = document.createElement('input');
      inp.type = 'hidden';
      inp.name = 'scholar_ids[]';
      inp.value = id;
      inputsWrap.appendChild(inp);
    });

    // preview list (first 30)
    preview.innerHTML = '';
    checked.slice(0, 30).forEach(cb => {
      const tr = cb.closest('tr');
      const lname = tr.children[2].textContent.trim();
      const fname = tr.children[3].textContent.trim();
      const li = document.createElement('li');
      li.textContent = `${lname}, ${fname}`;
      preview.appendChild(li);
    });
    if (checked.length > 30) {
      const li = document.createElement('li');
      li.className = 'text-muted';
      li.textContent = `+ ${checked.length - 30} more…`;
      preview.appendChild(li);
    }

    // ✅ Default date/time in Step 2 (editable)
    const s2ReleaseAt = document.getElementById('s2_release_at');
    if (s2ReleaseAt && !s2ReleaseAt.value) {
      s2ReleaseAt.value = toLocalDatetimeValue(new Date());
    }

    bootstrap.Modal.getOrCreateInstance(selectModalEl).hide();
    bootstrap.Modal.getOrCreateInstance(scheduleModalEl).show();
  });

  // Back from Step 2 to Step 1
  backToStep1Btn.addEventListener('click', () => {
    bootstrap.Modal.getOrCreateInstance(scheduleModalEl).hide();
    bootstrap.Modal.getOrCreateInstance(selectModalEl).show();

    // focus back on proceed button
    setTimeout(() => proceedBtn?.focus(), 150);
  });

  // INIT: start fresh each time bulk wizard opens
  selectModalEl.addEventListener('show.bs.modal', () => {
    resetBulkWizard();
  });

  // keep default disabled until selection
  resetBulkWizard();
});



// =========================
// RELEASE MODAL (per stipend row)
// =========================
const releaseModalEl = document.getElementById('releaseStipendModal');
const releaseForm    = document.getElementById('releaseStipendForm');

const rmStudentName  = document.getElementById('rm_student_name');
const rmStudentId    = document.getElementById('rm_student_id');
const rmScholarship  = document.getElementById('rm_scholarship');
const rmBatch        = document.getElementById('rm_batch');
const rmReleaseTitle = document.getElementById('rm_release_title');
const rmAmount       = document.getElementById('rm_amount');
const rmReceivedAt   = document.getElementById('rm_received_at');

document.addEventListener('click', function(e){
  const btn = e.target.closest('.openReleaseModal');
  if(!btn) return;

  const stipendId   = btn.getAttribute('data-stipend-id');
  const studentName = btn.getAttribute('data-student-name');
  const studentId   = btn.getAttribute('data-student-id');
  const scholarship = btn.getAttribute('data-scholarship');
  const batch       = btn.getAttribute('data-batch');
  const releaseTitle= btn.getAttribute('data-release-title');
  const amount      = btn.getAttribute('data-amount');
  const defaultDate = btn.getAttribute('data-default-date');

  rmStudentName.textContent = studentName || '—';
  rmStudentId.textContent   = studentId || '—';
  rmScholarship.textContent = scholarship || '—';
  rmBatch.textContent       = batch || '—';

  rmReleaseTitle.value = releaseTitle || '—';
  rmAmount.value       = amount ? `₱ ${amount}` : '—';

  rmReceivedAt.value = defaultDate || '';

  // ✅ Set dynamic form action
  // We generate base URL from Laravel route with placeholder style:
  const base = <?php echo json_encode(route('coordinator.stipends.release', ['stipend' => '___ID___']), 512) ?>;
  releaseForm.action = base.replace('___ID___', stipendId);
});
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.coordinator', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/coordinator/manage-stipends.blade.php ENDPATH**/ ?>