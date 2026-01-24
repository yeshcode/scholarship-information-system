

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
            <h3 class="mb-1">Bulk Upload Preview</h3>
            <div class="text-muted small">
                Total rows: <b><?php echo e($totalCount); ?></b> |
                Rows with issues: <b><?php echo e($issuesCount); ?></b>
            </div>
        </div>
        <a href="<?php echo e(route('admin.users.bulk-upload-form')); ?>" class="btn btn-outline-secondary btn-sm">
            Upload another file
        </a>
    </div>

    <?php if(session('error')): ?>
        <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0 align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Line</th>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>College</th>
                            <th>Course</th>
                            <th>Year Level</th>
                            <th>Result</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $preview; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="<?php echo e(!empty($r['issues']) ? 'table-warning' : ''); ?>">
                                <td><?php echo e($r['line']); ?></td>
                                <td><?php echo e($r['student_id']); ?></td>
                                <td><?php echo e($r['lastname']); ?>, <?php echo e($r['firstname']); ?></td>
                                <td><?php echo e($r['bisu_email']); ?></td>
                                <td><?php echo e($r['college']); ?></td>
                                <td><?php echo e($r['course']); ?></td>
                                <td><?php echo e($r['year_level']); ?></td>
                                <td>
                                    <?php if(empty($r['issues'])): ?>
                                        <span class="badge bg-success">OK</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Has issues</span>
                                        <div class="small text-muted mt-1">
                                            <?php echo e(implode('; ', $r['issues'])); ?>

                                        </div>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer d-flex justify-content-end gap-2">
            <a href="<?php echo e(route('admin.users.bulk-upload-form')); ?>" class="btn btn-outline-secondary">
                Cancel
            </a>

            <?php if($issuesCount > 0): ?>
                <button class="btn btn-success" disabled title="Fix issues first">
                    Confirm Upload
                </button>
            <?php else: ?>
                <!-- Trigger modal -->
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#confirmModal">
                    Confirm Upload
                </button>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if($issuesCount === 0): ?>
<!-- Confirm Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirm Bulk Upload</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        This will register <b><?php echo e($totalCount); ?></b> students.
        Their default password will be their <b>student_id</b>.
        <div class="mt-2 text-muted small">
            Make sure the list is correct before confirming.
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Back</button>
        <form method="POST" action="<?php echo e(route('admin.users.bulk-upload.confirm')); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-success">Yes, Confirm</button>
        </form>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/super-admin/users-bulk-upload-preview.blade.php ENDPATH**/ ?>