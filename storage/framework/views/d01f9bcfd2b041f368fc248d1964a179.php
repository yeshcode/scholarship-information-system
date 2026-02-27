

<?php $__env->startSection('page-content'); ?>

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


<?php if(session('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <?php echo e(session('error')); ?>

        <button class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>


<?php if($errors->any()): ?>
    <div class="alert alert-danger">
        <div class="fw-bold mb-1">Please fix the following:</div>
        <ul class="mb-0">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $err): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($err); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
<?php endif; ?>


<div class="d-flex align-items-end justify-content-between flex-wrap gap-2 mb-3">
    <div>
        <h2 class="page-title-bisu">Create Stipend Release Schedule</h2>
        <div class="subtext">Pick a scholarship (TDP/TES), then choose a batch under it.</div>
    </div>

    <a href="<?php echo e(route('coordinator.manage-stipend-releases')); ?>" class="btn btn-outline-secondary btn-sm">
        ← Back
    </a>
</div>

<div class="card card-bisu shadow-sm">
    <div class="card-header">
        <div class="fw-bold text-secondary">Schedule Details</div>
    </div>

    <div class="card-body">
        <form action="<?php echo e(route('coordinator.stipend-releases.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>

            <div class="row g-3">

                
                <div class="col-12 col-md-6">
                    <label class="form-label-bisu">Scholarship (TDP/TES)</label>
                    <select name="scholarship_id" id="scholarship_id"
                            class="form-select form-select-sm <?php $__errorArgs = ['scholarship_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            required>
                        <option value="">Select scholarship…</option>
                        <?php $__currentLoopData = $scholarships; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($s->id); ?>" <?php echo e(old('scholarship_id') == $s->id ? 'selected' : ''); ?>>
                                <?php echo e($s->scholarship_name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['scholarship_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <div class="form-text">Only scholarships that support batches will appear here.</div>
                </div>

                
                <div class="col-12 col-md-6">
                    <label class="form-label-bisu">Batch</label>
                    <select name="batch_id" id="batch_id"
                            class="form-select form-select-sm <?php $__errorArgs = ['batch_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            required disabled>
                        <option value="">Select scholarship first…</option>
                    </select>
                    <?php $__errorArgs = ['batch_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <div id="batchHelp" class="form-text text-muted">Choose a scholarship to load batches.</div>
                </div>

                
                <div class="col-12 col-md-6">
                    <label class="form-label-bisu">Release Semester (For Record)</label>
                    <select name="semester_id"
                            class="form-select form-select-sm <?php $__errorArgs = ['semester_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            required>
                        <option value="">Select semester…</option>
                        <?php $__currentLoopData = $semesters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($sem->id); ?>" <?php echo e(old('semester_id') == $sem->id ? 'selected' : ''); ?>>
                                <?php echo e($sem->term ?? $sem->semester_name); ?> <?php echo e($sem->academic_year); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['semester_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <div class="form-text">
                        This is the semester the stipend is intended for (can be delayed/past semester).
                    </div>
                </div>

                
                <div class="col-12">
                    <label class="form-label-bisu">Schedule Title</label>
                    <input type="text" name="title"
                           class="form-control form-control-sm <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           value="<?php echo e(old('title')); ?>"
                           required
                           placeholder="e.g., Stipend Release Schedule - Batch 13 (January)">
                    <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div class="col-12 col-md-6">
                    <label class="form-label-bisu">Amount</label>
                    <input type="number" step="0.01" name="amount"
                           class="form-control form-control-sm <?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           value="<?php echo e(old('amount')); ?>"
                           required
                           placeholder="e.g., 1750.00">
                    <?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div class="col-12 col-md-6">
                    <label class="form-label-bisu">Status</label>
                    <select name="status"
                            class="form-select form-select-sm <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            required>
                        <option value="for_billing" <?php echo e(old('status')=='for_billing' ? 'selected' : ''); ?>>For Billing</option>
                        <option value="for_check"   <?php echo e(old('status')=='for_check' ? 'selected' : ''); ?>>For Check</option>
                        <option value="for_release" <?php echo e(old('status')=='for_release' ? 'selected' : ''); ?>>For Release</option>
                        <option value="received"    <?php echo e(old('status')=='received' ? 'selected' : ''); ?>>Received</option>
                    </select>
                    <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div class="col-12">
                    <label class="form-label-bisu">Notes (optional)</label>
                    <textarea name="notes" rows="3"
                              class="form-control form-control-sm <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                              placeholder="Optional notes or reminders…"><?php echo e(old('notes')); ?></textarea>
                    <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-bisu btn-sm">
                    Create Schedule
                </button>
                <a href="<?php echo e(route('coordinator.manage-stipend-releases')); ?>" class="btn btn-outline-secondary btn-sm">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<?php
    $batchJs = $batches->map(function($b){
        return [
            'id' => $b->id,
            'scholarship_id' => $b->scholarship_id,
            'batch_number' => $b->batch_number,
        ];
    })->values();
?>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const scholarshipSelect = document.getElementById('scholarship_id');
    const batchSelect = document.getElementById('batch_id');
    const batchHelp = document.getElementById('batchHelp');

    const batches = <?php echo json_encode($batchJs, 15, 512) ?>;
    const oldBatchId = <?php echo json_encode(old('batch_id'), 15, 512) ?>; // ✅ preserve on error

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
            const label = `Batch ${b.batch_number}`;
            
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

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.coordinator', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/coordinator/create-stipend-release.blade.php ENDPATH**/ ?>