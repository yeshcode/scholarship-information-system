

<?php $__env->startSection('content'); ?>
<?php
    use Carbon\Carbon;
    use Illuminate\Support\Str;

    $theme = '#003366';
    $brand2 = '#0b3d8f';
    $bg = '#f4f7fb';
    $line = '#e5e7eb';
    $muted = '#6b7280';

    // Group the paginator collection, not paginator object
    $items = method_exists($announcements, 'getCollection')
        ? $announcements->getCollection()
        : collect($announcements);

    $grouped = $items->groupBy(function ($a) {
        if (empty($a->posted_at)) return 'Date Unknown';

        $dt = $a->posted_at instanceof Carbon ? $a->posted_at : Carbon::parse($a->posted_at);

        if ($dt->isToday()) return 'Today';
        if ($dt->isYesterday()) return 'Yesterday';

        return $dt->format('M d, Y');
    });

    // viewedIds passed from controller
    $viewedIds = $viewedIds ?? [];

    // for "see more"
    $previewLimit = 220; // adjust if you want shorter/longer
?>

<style>
    body{ background: <?php echo e($bg); ?>; }

    .page-wrap{
        font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial;
    }

    .page-head{
        display:flex;
        align-items:flex-end;
        justify-content:space-between;
        gap:12px;
        flex-wrap:wrap;
        margin-bottom: 14px;
    }
    .page-title{
        font-weight: 900;
        color: <?php echo e($theme); ?>;
        letter-spacing: .2px;
        margin: 0;
    }
    .subtext{ color: <?php echo e($muted); ?>; font-size: .92rem; }

    .timeline-label{
        display:flex;
        align-items:center;
        gap:12px;
        margin: 18px 0 12px;
    }
    .timeline-label .line{ flex: 1; height:1px; background: <?php echo e($line); ?>; }
    .timeline-label .tag{
        color: <?php echo e($muted); ?>;
        font-weight: 800;
        font-size: .86rem;
        padding: .25rem .7rem;
        border-radius: 999px;
        border: 1px solid <?php echo e($line); ?>;
        background: rgba(255,255,255,.8);
        white-space: nowrap;
    }

    .a-card{
        border: 1px solid <?php echo e($line); ?>;
        border-radius: 18px;
        box-shadow: 0 10px 26px rgba(0,0,0,.05);
        overflow: hidden;
        background: #fff;
        transition: transform .12s ease, box-shadow .12s ease;
    }
    .a-card:hover{
        transform: translateY(-1px);
        box-shadow: 0 14px 34px rgba(0,0,0,.07);
    }

    .a-body{ padding: 14px; }
    @media (min-width: 768px){
        .a-body{ padding: 18px 20px; }
    }

    .avatar{
        width: 44px; height: 44px;
        border-radius: 14px;
        display:flex; align-items:center; justify-content:center;
        background: linear-gradient(135deg, <?php echo e($theme); ?>, <?php echo e($brand2); ?>);
        color:#fff;
        font-weight: 900;
        letter-spacing: .03em;
        flex: 0 0 auto;
        box-shadow: 0 10px 22px rgba(11,46,94,.18);
        user-select:none;
    }

    .meta{
        min-width: 0;
        width: 100%;
    }
    .meta-top{
        display:flex;
        align-items:flex-start;
        justify-content:space-between;
        gap: 10px;
        flex-wrap: wrap;
    }
    .office{
        font-weight: 900;
        color: <?php echo e($theme); ?>;
        line-height: 1.15;
        font-size: .95rem;
    }
    .time{
        color: <?php echo e($muted); ?>;
        font-size: .82rem;
        white-space: nowrap;
    }

    .badge-new{
        background: rgba(34,197,94,.12);
        color: #166534;
        border: 1px solid rgba(34,197,94,.22);
        font-weight: 900;
        border-radius: 999px;
        padding: .22rem .55rem;
        font-size: .72rem;
        letter-spacing: .2px;
    }

    .a-title{
        margin-top: 10px;
        font-weight: 900;
        color: #111827;
        font-size: 1.02rem;
        line-height: 1.25;
    }

    .a-desc{
        margin-top: 8px;
        color: <?php echo e($muted); ?>;
        font-size: .95rem;
        line-height: 1.55;
        white-space: pre-line;
        overflow-wrap:anywhere;
        word-break: break-word;
    }

    .actions{
        display:flex;
        gap: 8px;
        margin-top: 12px;
        flex-wrap: wrap;
    }

    .btn-brand{
        background: <?php echo e($theme); ?>;
        border: none;
        color: #fff;
        font-weight: 800;
        border-radius: 12px;
        padding: .5rem .9rem;
    }
    .btn-brand:hover{ opacity:.92; color:#fff; }

    .btn-soft{
        border: 1px solid rgba(11,46,94,.18);
        background: rgba(11,46,94,.06);
        color: <?php echo e($theme); ?>;
        font-weight: 800;
        border-radius: 12px;
        padding: .5rem .9rem;
    }
    .btn-soft:hover{ background: rgba(11,46,94,.10); color: <?php echo e($theme); ?>; }

    /* Mobile: make buttons comfortable */
    @media (max-width: 575.98px){
        .actions .btn{ width: 100%; }
        .time{ font-size: .78rem; }
    }

</style>

<div class="container py-3 py-md-4 page-wrap">
    <div class="mx-auto" style="max-width: 820px;">

        <div class="page-head">
            <div>
                <h2 class="page-title">Announcements</h2>
                <div class="subtext">Your updates feed.</div>
            </div>
        </div>

        <?php $__empty_1 = true; $__currentLoopData = $grouped; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label => $list): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

            <div class="timeline-label">
                <div class="line"></div>
                <div class="tag"><?php echo e($label); ?></div>
                <div class="line"></div>
            </div>

            <?php $__currentLoopData = $list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $announcement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $dt = !empty($announcement->posted_at)
                        ? ($announcement->posted_at instanceof Carbon ? $announcement->posted_at : Carbon::parse($announcement->posted_at))
                        : null;

                    $timeLabel = $dt
                        ? ($dt->isToday() || $dt->isYesterday()
                            ? $dt->format('h:i A')
                            : $dt->format('M d') . ' at ' . $dt->format('h:i A'))
                        : 'N/A';

                    $isViewed = in_array((int)$announcement->id, $viewedIds, true);

                    // "new" logic: show only if NOT viewed + posted within last 3 days (adjust if you want)
                    $showNew = (!$isViewed && $dt && $dt->gt(now()->subDays(3)));

                    $desc = (string)($announcement->description ?? '');
                    $isLong = Str::length($desc) > $previewLimit;

                    $collapseId = 'ann_desc_'.$announcement->id;
                ?>

                <div class="a-card mb-3">
                    <div class="a-body">

                        <div class="d-flex align-items-start gap-3">
                            <div class="avatar">SO</div>

                            <div class="meta">
                                <div class="meta-top">
                                    <div>
                                        <div class="office">Scholarship Office</div>
                                        <div class="time"><?php echo e($timeLabel); ?></div>
                                    </div>

                                    <?php if($showNew): ?>
                                        <span class="badge-new">NEW</span>
                                    <?php endif; ?>
                                </div>

                                <div class="a-title">
                                    <?php echo e($announcement->title ?? 'Announcement'); ?>

                                </div>

                                
                                <div class="a-desc">
                                    <?php if($isLong): ?>
                                        
                                        <div id="preview_<?php echo e($collapseId); ?>">
                                            <?php echo e(Str::limit($desc, $previewLimit)); ?>

                                        </div>

                                        
                                        <div class="collapse" id="<?php echo e($collapseId); ?>">
                                            <?php echo e($desc); ?>

                                        </div>
                                    <?php else: ?>
                                        <?php echo e($desc); ?>

                                    <?php endif; ?>
                                </div>

                                <div class="actions">
                                    
                                    <a href="<?php echo e(route('student.announcements.show', $announcement->id)); ?>"
                                       class="btn btn-brand btn-sm">
                                        Open
                                    </a>

                                    <?php if($isLong): ?>
                                        <button class="btn btn-soft btn-sm"
                                                type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#<?php echo e($collapseId); ?>"
                                                aria-expanded="false"
                                                aria-controls="<?php echo e($collapseId); ?>"
                                                onclick="
                                                    const c = document.getElementById('<?php echo e($collapseId); ?>');
                                                    const p = document.getElementById('preview_<?php echo e($collapseId); ?>');
                                                    const btn = this;

                                                    setTimeout(() => {
                                                        const isShown = c.classList.contains('show');
                                                        if(isShown){
                                                            p.style.display = 'none';
                                                            btn.textContent = 'See less';
                                                        }else{
                                                            p.style.display = 'block';
                                                            btn.textContent = 'See more';
                                                        }
                                                    }, 50);
                                                ">
                                            See more
                                        </button>
                                    <?php endif; ?>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="text-center py-5">
                <div class="mb-2" style="font-size: 2rem;">📢</div>
                <h5 class="fw-semibold mb-1" style="color:<?php echo e($theme); ?>;">No announcements yet</h5>
                <p class="text-muted mb-0">Please check again later.</p>
            </div>
        <?php endif; ?>

        
        <?php if(method_exists($announcements, 'links')): ?>
            <div class="d-flex justify-content-center mt-4">
                <?php echo e($announcements->links()); ?>

            </div>
        <?php endif; ?>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/student/announcements.blade.php ENDPATH**/ ?>