

<?php $__env->startSection('page-content'); ?>

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

<?php if($errors->any()): ?>
  <div class="alert alert-danger">
    <strong>Update failed:</strong>
    <ul class="mb-0">
      <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <li><?php echo e($e); ?></li> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
  </div>
<?php endif; ?>

<div class="card card-bisu shadow-sm">
  <div class="card-header">
    <div class="fw-bold" style="color:var(--bisu-blue);">Edit Stipend</div>
    <small class="text-muted">Only Received At and Status can be changed.</small>
  </div>

  <div class="card-body">
    <form action="<?php echo e(route('coordinator.stipends.update', $stipend->id)); ?>" method="POST">
      <?php echo csrf_field(); ?>
      <?php echo method_field('PUT'); ?>

      <div class="row g-3">

        
        <div class="col-12 col-md-6">
          <div class="label">Scholar</div>
          <div class="info-box">
            <?php echo e($stipend->scholar->user->lastname ?? ''); ?>,
            <?php echo e($stipend->scholar->user->firstname ?? ''); ?>

            <div class="small text-muted">
              Student ID: <?php echo e($stipend->scholar->user->student_id ?? '—'); ?>

            </div>
          </div>
        </div>

        <div class="col-12 col-md-6">
          <div class="label">Release Schedule</div>
          <div class="info-box">
            <?php echo e($stipend->stipendRelease->title ?? '—'); ?>

            <div class="small text-muted">
              Amount: <?php echo e(number_format((float)($stipend->stipendRelease->amount ?? 0), 2)); ?>

            </div>
          </div>
        </div>

        <div class="col-12 col-md-6">
          <div class="label">Current Amount (record)</div>
          <div class="info-box">
            <?php echo e(number_format((float)$stipend->amount_received, 2)); ?>

          </div>
        </div>

        
        <div class="col-12 col-md-6">
          <label class="label">Status</label>
          <select name="status" class="form-select form-select-sm" required>
            <option value="for_release" <?php echo e($stipend->status === 'for_release' ? 'selected' : ''); ?>>For Release</option>
            <option value="released" <?php echo e($stipend->status === 'released' ? 'selected' : ''); ?>>Released</option>
          </select>
        </div>

        <div class="col-12 col-md-6">
          <label class="label">Received At</label>
          <input type="datetime-local"
                 name="received_at"
                 class="form-control form-control-sm"
                 value="<?php echo e($stipend->received_at ? \Carbon\Carbon::parse($stipend->received_at)->format('Y-m-d\TH:i') : ''); ?>">
          <div class="form-text">If Status = Received, leaving this empty will auto-set to now.</div>
        </div>

      </div>

      <div class="mt-3 d-flex gap-2">
        <button type="submit" class="btn btn-bisu btn-sm">Update</button>
        <a href="<?php echo e(route('coordinator.manage-stipends')); ?>" class="btn btn-outline-secondary btn-sm">Cancel</a>
      </div>

    </form>
  </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.coordinator', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/coordinator/edit-stipend.blade.php ENDPATH**/ ?>