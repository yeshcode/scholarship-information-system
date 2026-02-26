

<?php $__env->startSection('content'); ?>
<div class="container py-4">

    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-3">
        <div>
            <h3 class="mb-1" style="color:#0b2e5e;">Reports</h3>
            <div class="text-muted">Generate official scholarship reports per semester (A4 format).</div>
        </div>

        <form class="d-flex align-items-center gap-2" method="GET" action="<?php echo e(route('coordinator.reports')); ?>">
            <span class="text-muted small">Semester:</span>
            <select class="form-select form-select-sm" style="min-width:260px;" disabled>
                <option>
                    <?php echo e($activeSemester ? ($activeSemester->term.' • '.$activeSemester->academic_year) : 'No active semester set'); ?>

                </option>
            </select>
        </form>
    </div>

    <div class="row g-3">
        
        <div class="col-12 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <h5 class="mb-1">Summary of Scholarships</h5>
                            <div class="text-muted small">
                                Official semester summary of scholarships and total scholars.
                            </div>
                        </div>
                        <span class="badge bg-light text-dark border">A4</span>
                    </div>

                    <div class="mt-3 d-flex flex-wrap gap-2">
                        <a class="btn btn-primary btn-sm"
                           style="background:#0b2e5e;border-color:#0b2e5e;"
                           href="<?php echo e(route('coordinator.reports.summary-of-scholarships', ['semester_id' => $activeSemesterId])); ?>">
                            View Report
                        </a>
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="window.print()">
                            Print Tip
                        </button>
                    </div>

                    <div class="mt-3 small text-muted">
                        Uses the current semester filter (<?php echo e($activeSemester?->term ?? 'N/A'); ?>).
                    </div>
                </div>
            </div>
        </div>

        
        <div class="col-12 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <h5 class="mb-1">List of Scholars and Grantees</h5>
                            <div class="text-muted small">
                                Official list of all scholars for the selected semester.
                            </div>
                        </div>
                        <span class="badge bg-light text-dark border">A4</span>
                    </div>

                    <div class="mt-3 d-flex flex-wrap gap-2">
                        <a class="btn btn-primary btn-sm"
                           style="background:#0b2e5e;border-color:#0b2e5e;"
                           href="<?php echo e(route('coordinator.reports.list-of-scholars', ['semester_id' => $activeSemesterId])); ?>">
                            View Report
                        </a>
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="window.print()">
                            Print Tip
                        </button>
                    </div>

                    <div class="mt-3 small text-muted">
                        Sorted alphabetically by last name.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4 text-muted small">
        Note: Data is generated automatically based on scholarships and scholars stored in the system.
    </div>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/coordinator/reports.blade.php ENDPATH**/ ?>