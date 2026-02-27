

<?php $__env->startSection('page-content'); ?>

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
</style>

<div class="page-head mb-3">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
        <div>
            <h4 class="mb-1 fw-bold brand-text">Manage Scholarships</h4>
            <div class="text-muted">
                View scholarship info and update dates anytime if there is a new call for application.
            </div>
        </div>

        <a href="<?php echo e(route('coordinator.scholarships.create')); ?>" class="btn btn-brand">
            + Add Scholarship
        </a>
    </div>
</div>

<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row g-3">
    <?php $__empty_1 = true; $__currentLoopData = $scholarships; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $scholarship): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <?php
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
        ?>

        <div class="col-12 col-lg-6 col-xl-4">

            <div class="sch-card h-100">
                <div class="sch-topbar"></div>

                <div class="card-body p-4">
                    <!-- Title + Status -->
                    <div class="d-flex justify-content-between align-items-start gap-2">
                        <div class="me-2">
                            <div class="sch-label">Title</div>
                            <h4 class="fw-bold mb-1"><?php echo e($scholarship->scholarship_name); ?></h4>
                        </div>

                        <span class="badge rounded-pill <?php echo e($isOpen ? 'text-bg-success' : 'text-bg-secondary'); ?>">
                            <?php echo e($isOpen ? 'OPEN' : 'CLOSED'); ?>

                        </span>
                    </div>

                    <!-- Benefactor -->
                    <div class="mt-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="sch-label">Benefactor</span>
                            <span class="sch-value text-end"><?php echo e($scholarship->benefactor); ?></span>
                        </div>
                    </div>

                     <!-- Small preview (optional but nice) -->
                    <div class="text-muted small mt-3">
                        <?php echo e(\Illuminate\Support\Str::limit($scholarship->description, 90)); ?>

                    </div>

                    <!-- Dates -->
                    <div class="sch-meta mt-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="sch-label">Application Date</span>
                            <span class="sch-value"><?php echo e($appDate); ?></span>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <span class="sch-label">Deadline</span>
                            <span class="sch-value">
                                <?php echo e($deadline); ?>

                                <?php if($deadlineSoon): ?>
                                    <span class="badge ms-2"
                                          style="background: rgba(255,193,7,.22); color:#7a5a00; border:1px solid rgba(255,193,7,.35);">
                                        Soon
                                    </span>
                                <?php endif; ?>
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
                                data-bs-target="#showScholarshipModal<?php echo e($scholarship->id); ?>">
                            Show
                        </button>

                        <a href="<?php echo e(route('coordinator.scholarships.edit', $scholarship->id)); ?>"
                           class="btn btn-brand w-100">
                            Edit
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- ✅ SHOW MODAL (Full details) -->
        <div class="modal fade" id="showScholarshipModal<?php echo e($scholarship->id); ?>" tabindex="-1"
             aria-labelledby="showScholarshipLabel<?php echo e($scholarship->id); ?>" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content" style="border-radius: 16px; overflow:hidden;">
                    <div class="sch-topbar"></div>

                    <div class="modal-header">
                        <div>
                            <h5 class="modal-title fw-bold mb-0" id="showScholarshipLabel<?php echo e($scholarship->id); ?>">
                                <?php echo e($scholarship->scholarship_name); ?>

                            </h5>
                            <div class="text-muted small mt-1">
                                Benefactor: <b><?php echo e($scholarship->benefactor); ?></b>
                                • Status:
                                <span class="badge rounded-pill <?php echo e($isOpen ? 'text-bg-success' : 'text-bg-secondary'); ?>">
                                    <?php echo e($isOpen ? 'OPEN' : 'CLOSED'); ?>

                                </span>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row g-3 mb-3">
                            <div class="col-12 col-md-6">
                                <div class="sch-meta h-100">
                                    <div class="sch-label">Application Date</div>
                                    <div class="sch-value"><?php echo e($appDate); ?></div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="sch-meta h-100">
                                    <div class="sch-label">Deadline</div>
                                    <div class="sch-value"><?php echo e($deadline); ?></div>
                                </div>
                            </div>
                        </div>

                        <div class="info-box mb-3">
                            <div class="info-title">Description</div>
                            <div class="text-muted"><?php echo e($scholarship->description); ?></div>
                        </div>

                        <div class="info-box">
                            <div class="info-title">Requirements</div>
                            <div class="text-muted" style="white-space: pre-wrap;"><?php echo e($scholarship->requirements); ?></div>
                        </div>
                    </div>

                    <div class="modal-footer d-flex justify-content-between">
                        <!-- Delete now opens confirm modal (no page) -->
                        <button type="button"
                                class="btn btn-outline-danger btn-open-confirm"
                                data-show-id="showScholarshipModal<?php echo e($scholarship->id); ?>"
                                data-confirm-id="confirmDeleteScholarshipModal<?php echo e($scholarship->id); ?>">
                            Delete
                        </button>

                        <div class="d-flex gap-2">
                            <a href="<?php echo e(route('coordinator.scholarships.edit', $scholarship->id)); ?>"
                               class="btn btn-brand">
                                Edit
                            </a>
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ✅ CONFIRM DELETE MODAL -->
        <div class="modal fade" id="confirmDeleteScholarshipModal<?php echo e($scholarship->id); ?>" tabindex="-1"
             aria-labelledby="confirmDeleteScholarshipLabel<?php echo e($scholarship->id); ?>" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="border-radius: 16px; overflow:hidden;">
                    <div class="sch-topbar"></div>

                    <div class="modal-header">
                        <h5 class="modal-title fw-bold" id="confirmDeleteScholarshipLabel<?php echo e($scholarship->id); ?>">
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
                            <div class="sch-value"><?php echo e($scholarship->scholarship_name); ?></div>

                            <div class="sch-label mt-2">Benefactor</div>
                            <div class="sch-value"><?php echo e($scholarship->benefactor); ?></div>

                            <div class="sch-label mt-2">Application Date • Deadline</div>
                            <div class="sch-value"><?php echo e($appDate); ?> • <?php echo e($deadline); ?></div>
                        </div>

                        <div class="text-muted small mt-3">
                            This action cannot be undone.
                        </div>
                    </div>

                    <div class="modal-footer d-flex justify-content-between">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                            Cancel
                        </button>

                        <form action="<?php echo e(route('coordinator.scholarships.destroy', $scholarship->id)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-danger">
                                Yes, Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="col-12">
            <div class="alert alert-info mb-0">No scholarships found.</div>
        </div>
    <?php endif; ?>
</div>

<div class="mt-4">
    <?php echo e($scholarships->links()); ?>

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

});
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.coordinator', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/coordinator/manage-scholarships.blade.php ENDPATH**/ ?>