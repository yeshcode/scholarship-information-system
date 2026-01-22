

<?php $__env->startSection('page-content'); ?>
<style>
    .fb-wrap { max-width: 900px; margin: 0 auto; padding: 10px; }
    .fb-header h2 { margin: 0; font-size: 24px; font-weight: 800; }
    .fb-header p { margin: 4px 0 0; color:#6b7280; }

    .fb-card {
        background:#fff;
        border:1px solid #e5e7eb;
        border-radius:14px;
        box-shadow: 0 1px 2px rgba(0,0,0,.06);
        padding: 16px;
        margin-bottom: 12px;
    }

    .fb-row { display:flex; gap:12px; align-items:center; }
    .fb-space { display:flex; justify-content:space-between; align-items:center; gap:12px; }

    .fb-avatar {
        height: 42px; width: 42px;
        border-radius: 999px;
        display:flex; align-items:center; justify-content:center;
        font-weight: 800;
        background:#dbeafe;
        color:#1d4ed8;
        flex: 0 0 auto;
    }

    .fb-name { font-weight: 800; color:#111827; margin:0; }
    .fb-sub { font-size: 12px; color:#6b7280; margin:2px 0 0; }

    .fb-input, .fb-textarea, .fb-select {
        width:100%;
        border:1px solid #e5e7eb;
        border-radius: 12px;
        padding: 10px 12px;
        outline: none;
        background: #fff;
    }
    .fb-textarea { min-height: 110px; resize: vertical; }

    .fb-grid {
        display:grid;
        grid-template-columns: 1fr 1fr 170px;
        gap: 10px;
        margin-top: 10px;
    }
    @media (max-width: 768px) {
        .fb-grid { grid-template-columns: 1fr; }
    }

    .fb-btn {
        border:none;
        border-radius: 10px;
        padding: 10px 14px;
        font-weight: 800;
        cursor:pointer;
        width:100%;
        background:#2563eb;
        color:#fff;
    }
    .fb-btn:hover { background:#1d4ed8; }

    .fb-pill {
        display:inline-block;
        font-size: 12px;
        padding: 4px 10px;
        border-radius: 999px;
        border:1px solid #e5e7eb;
        background:#f9fafb;
        color:#374151;
        vertical-align: middle;
    }

    .fb-divider { border-top:1px solid #eef2f7; margin: 12px 0; }

    .fb-scholar-box {
        display:none;
        border:1px solid #e5e7eb;
        border-radius: 12px;
        background:#f9fafb;
        padding: 10px;
        max-height: 230px;
        overflow:auto;
    }

    .fb-scholar-item{
        background:#fff;
        border:1px solid #e5e7eb;
        border-radius: 12px;
        padding: 10px;
        display:flex;
        gap:10px;
        align-items:flex-start;
        cursor:pointer;
        margin-bottom: 8px;
    }
    .fb-scholar-item:hover { background:#f3f4f6; }

    .fb-actions { display:flex; justify-content:space-around; gap:10px; color:#6b7280; font-weight:700; }
    .fb-actions span { cursor:pointer; }
    .fb-actions span:hover { color:#2563eb; }

    .alert-success { margin-bottom: 12px; }
</style>

<div class="fb-wrap">

    <div class="fb-header fb-card">
        <div class="fb-space">
            <div>
                <h2>Announcements</h2>
                <p>Post updates and notify students.</p>
            </div>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="fb-card" style="border-color:#bbf7d0;background:#f0fdf4;color:#166534;">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    
    <div class="fb-card">
        <div class="fb-row">
            <div class="fb-avatar">
                <?php echo e(strtoupper(substr(auth()->user()->firstname ?? 'U', 0, 1))); ?>

            </div>
            <div>
                <p class="fb-name"><?php echo e(auth()->user()->firstname); ?> <?php echo e(auth()->user()->lastname); ?></p>
                <p class="fb-sub">Create a new announcement</p>
            </div>
        </div>

        <form action="<?php echo e(route('coordinator.announcements.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>

            <div style="margin-top:12px;">
                <input type="text" name="title" class="fb-input" placeholder="Announcement title…" required>
            </div>

            <div style="margin-top:10px;">
                <textarea name="description" class="fb-textarea" placeholder="What do you want to announce?" required></textarea>
            </div>

            <div class="fb-grid">

                <div>
                    <label class="fb-sub" style="font-weight:800;">Audience</label>
                    <select name="audience" class="fb-select" id="audience-select" required>
                        <option value="all_students">All Students</option>
                        <option value="specific_scholars">Specific Scholars</option>
                    </select>
                </div>

                <div style="display:flex;align-items:flex-end;">
                    <button type="submit" class="fb-btn">Post & Notify</button>
                </div>
            </div>

            
            <div id="scholar-selection" style="margin-top:12px; display:none;">
                <div class="fb-divider"></div>

                <div class="fb-space" style="margin-bottom:10px;">
                    <p class="fb-name" style="margin:0;">Select Scholars</p>
                    <span class="fb-pill">Only selected scholars will be notified</span>
                </div>

                <div class="fb-scholar-box" id="scholar-box" style="display:block;">
                    <?php $__currentLoopData = ($scholars ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $scholar): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <label class="fb-scholar-item">
                            <input type="checkbox" name="selected_scholars[]" value="<?php echo e($scholar->id); ?>" style="margin-top:4px;">
                            <div>
                                <div style="font-weight:800; color:#111827;">
                                    <?php echo e($scholar->user->firstname); ?> <?php echo e($scholar->user->lastname); ?>

                                </div>
                                <div class="fb-sub"><?php echo e($scholar->user->bisu_email); ?></div>
                            </div>
                        </label>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </form>
    </div>

    
    <?php $__empty_1 = true; $__currentLoopData = $announcements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="fb-card">
            <div class="fb-space">
                <div class="fb-row">
                    <div class="fb-avatar" style="background:#f3f4f6;color:#374151;">
                        <?php echo e(strtoupper(substr($post->creator->firstname ?? 'C', 0, 1))); ?>

                    </div>

                    <div>
                        <p class="fb-name">
                            <?php echo e($post->creator->firstname ?? 'Coordinator'); ?> <?php echo e($post->creator->lastname ?? ''); ?>

                        </p>
                        <p class="fb-sub">
                            <?php echo e(\Carbon\Carbon::parse($post->posted_at)->format('M d, Y • h:i A')); ?>

                            •
                            <span class="fb-pill">
                                <?php echo e($post->audience === 'all_students' ? 'All Students' : 'Specific Scholars'); ?>

                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <div style="margin-top:12px;">
                <div style="font-weight:900; font-size:16px; color:#111827;">
                    <?php echo e($post->title); ?>

                </div>
                <div style="margin-top:6px; color:#374151; white-space:pre-line;">
                    <?php echo e($post->description); ?>

                </div>
            </div>

            <div class="fb-divider"></div>

            <div class="fb-actions">
                <span>👍 Like</span>
                <span>💬 Comment</span>
                <span>↗ Share</span>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="fb-card" style="text-align:center; color:#6b7280;">
            No announcements yet. Post the first update above.
        </div>
    <?php endif; ?>

    <div style="margin-top: 14px;">
        <?php echo e($announcements->links()); ?>

    </div>

</div>

<script>
    const audienceSelect = document.getElementById('audience-select');
    const scholarSelection = document.getElementById('scholar-selection');

    function toggleScholarSelection() {
        if (!audienceSelect || !scholarSelection) return;
        scholarSelection.style.display = (audienceSelect.value === 'specific_scholars') ? 'block' : 'none';
    }

    if (audienceSelect) {
        audienceSelect.addEventListener('change', toggleScholarSelection);
        toggleScholarSelection();
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.coordinator', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/coordinator/manage-announcements.blade.php ENDPATH**/ ?>