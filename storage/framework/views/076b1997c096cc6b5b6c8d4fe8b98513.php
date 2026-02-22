

<?php $__env->startSection('content'); ?>
<div class="container-fluid container-xl py-4">

    
    <div class="bisu-hero p-4 p-md-5 rounded-4 shadow-sm mb-4">
        <div class="row g-3 align-items-stretch">
            <div class="col-12 col-lg-8">
                <div class="text-white-50 small mb-1">Student Dashboard</div>
                <h2 class="text-white fw-bold mb-1">
                    Welcome, <?php echo e(auth()->user()->firstname); ?> 👋
                </h2>
                <div class="text-white-50">
                    Stay updated with announcements, scholarships, notifications, and your questions.
                </div>
            </div>

            
            <div class="col-12 col-lg-4 d-flex">
                <div class="card border-0 rounded-4 glass-card shadow-sm  w-100">
                    <div class="card-body panel-body-scroll">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-bisu">
                                <i class="bi bi-person-fill"></i>
                            </div>

                            <div class="flex-grow-1">
                                <div class="fw-bold text-dark">
                                    <?php echo e(auth()->user()->firstname); ?> <?php echo e(auth()->user()->lastname ?? ''); ?>

                                </div>

                                <div class="text-muted small">
                                    <i class="bi bi-mortarboard me-1"></i>
                                    <?php echo e($studentCourse ?? 'Course: N/A'); ?>

                                </div>

                                <div class="text-muted small">
                                    <i class="bi bi-bar-chart-steps me-1"></i>
                                    <?php echo e($studentYearLevel ?? 'Year Level: N/A'); ?>

                                </div>
                            </div>

                            <span class="badge rounded-pill px-3 py-2
                                <?php echo e(($isScholar ?? false) ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-light text-dark border'); ?>">
                                <i class="bi bi-patch-check-fill me-1"></i>
                                <?php echo e(($isScholar ?? false) ? 'Scholar' : 'Student'); ?>

                            </span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card border-0 rounded-4 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="icon-bubble bg-primary-subtle text-primary">
                        <i class="bi bi-megaphone-fill"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Total Announcements</div>
                        <div class="fs-4 fw-bold"><?php echo e($announcementsCount ?? 0); ?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-3">
            <div class="card border-0 rounded-4 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="icon-bubble bg-warning-subtle text-warning">
                        <i class="bi bi-bell-fill"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Unread Notifications</div>
                        <div class="fs-4 fw-bold"><?php echo e($unreadCount ?? 0); ?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-3">
            <div class="card border-0 rounded-4 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="icon-bubble bg-info-subtle text-info">
                        <i class="bi bi-chat-dots-fill"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Total Questions</div>
                        <div class="fs-4 fw-bold"><?php echo e($questionsCount ?? 0); ?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-3">
            <div class="card border-0 rounded-4 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="icon-bubble bg-success-subtle text-success">
                        <i class="bi bi-award-fill"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Available Scholarships</div>
                        <div class="fs-4 fw-bold"><?php echo e($scholarshipsCount ?? 0); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-6 col-lg-3">
            <a href="<?php echo e(route('student.announcements')); ?>" class="text-decoration-none">
                <div class="card border-0 rounded-4 shadow-sm h-100 dash-card">
                    <div class="card-body d-flex gap-3">
                        <div class="icon-bubble bisu-soft">
                            <i class="bi bi-megaphone"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-dark">Announcements</div>
                            <div class="text-muted small">Read updates & deadlines.</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-12 col-md-6 col-lg-3">
            <a href="<?php echo e(route('student.scholarships.index')); ?>" class="text-decoration-none">
                <div class="card border-0 rounded-4 shadow-sm h-100 dash-card">
                    <div class="card-body d-flex gap-3">
                        <div class="icon-bubble bisu-soft">
                            <i class="bi bi-award"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-dark">Scholarships</div>
                            <div class="text-muted small">Browse opportunities.</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-12 col-md-6 col-lg-3">
            <a href="<?php echo e(route('questions.create')); ?>" class="text-decoration-none">
                <div class="card border-0 rounded-4 shadow-sm h-100 dash-card">
                    <div class="card-body d-flex gap-3">
                        <div class="icon-bubble bisu-soft">
                            <i class="bi bi-chat-left-text"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-dark">Ask Question</div>
                            <div class="text-muted small">Send concern to coordinator.</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-12 col-md-6 col-lg-3">
            <a href="<?php echo e(route('student.stipend-history')); ?>" class="text-decoration-none">
                <div class="card border-0 rounded-4 shadow-sm h-100 dash-card">
                    <div class="card-body d-flex gap-3">
                        <div class="icon-bubble bisu-soft">
                            <i class="bi bi-cash-coin"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-dark">Stipend History</div>
                            <div class="text-muted small">Track releases.</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    
    <div class="row g-3">
        <div class="col-12 col-lg-6">
            <div class="card border-0 rounded-4 shadow-sm h-100 panel-body-fixed">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <div class="fw-bold">
                        <i class="bi bi-megaphone-fill text-primary me-2"></i> Recent Announcements
                    </div>
                    <a href="<?php echo e(route('student.announcements')); ?>" class="small text-decoration-none">
                        View all <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

                <div class="card-body panel-shell">
                    <div class="panel-items">
                        <?php $__empty_1 = true; $__currentLoopData = $announcements ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="p-3 rounded-4 border mb-2">
                                <div class="fw-semibold wrap-anywhere"><?php echo e($a->title); ?></div>
                                <div class="text-muted small mt-1">
                                    <?php echo e(\Illuminate\Support\Str::limit($a->description, 110)); ?>

                                </div>
                                <div class="text-muted small mt-2">
                                    <i class="bi bi-clock me-1"></i>
                                    <?php echo e(optional($a->created_at)->format('M d, Y')); ?>

                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="empty-state">
                                <div class="empty-icon"><i class="bi bi-megaphone"></i></div>
                                <div class="fw-semibold">No announcements yet</div>
                                <div class="text-muted small">You’ll see updates here once posted.</div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-3">
            <div class="card border-0 rounded-4 shadow-sm h-100 panel-body-fixed">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <div class="fw-bold">
                        <i class="bi bi-chat-dots-fill text-info me-2"></i> My Questions
                    </div>
                    <a href="<?php echo e(route('questions.my')); ?>" class="small text-decoration-none">
                        View all <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

               <div class="card-body panel-shell">
                    <div class="panel-items">
                        <?php $__empty_1 = true; $__currentLoopData = $myRecentQuestions ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $q): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                $raw = strtolower(trim((string)($q->status ?? 'pending')));
                                $isAnswered = in_array($raw, ['answered','resolved','done','closed']);

                                $label = $isAnswered ? 'Answered' : 'Pending';
                                $badgeClass = $isAnswered
                                    ? 'bg-success-subtle text-success border border-success-subtle'
                                    : 'bg-warning-subtle text-warning border border-warning-subtle';
                            ?>

                            <div class="p-3 rounded-4 border mb-2">
                                <div class="fw-semibold small wrap-anywhere">
                                    <?php echo e(\Illuminate\Support\Str::limit($q->question ?? $q->question_text ?? $q->message ?? 'No question text found.', 85)); ?>

                                </div>
                                <span class="badge rounded-pill mt-2 <?php echo e($badgeClass); ?>">
                                    <?php echo e($label); ?>

                                </span>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="empty-state">
                                <div class="empty-icon"><i class="bi bi-chat-dots"></i></div>
                                <div class="fw-semibold">No questions yet</div>
                                <div class="text-muted small mb-2">Ask a question if you need help.</div>
                                <a href="<?php echo e(route('questions.create')); ?>" class="btn btn-sm btn-outline-primary rounded-pill">
                                    <i class="bi bi-plus-circle me-1"></i> Ask now
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-3">
            <div class="card border-0 rounded-4 shadow-sm h-100 panel-body-fixed">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <div class="fw-bold">
                        <i class="bi bi-bell-fill text-warning me-2"></i> Notifications
                    </div>
                    <a href="<?php echo e(route('student.notifications')); ?>" class="small text-decoration-none">
                        View all
                        <?php if(($unreadCount ?? 0) > 0): ?>
                            <span class="badge bg-primary ms-1"><?php echo e($unreadCount); ?></span>
                        <?php endif; ?>
                    </a>
                </div>

                <div class="card-body panel-shell">
    <div class="panel-items">
        <?php $__empty_1 = true; $__currentLoopData = $notifications ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
                $isUnread = !$notification->is_read;
                $openUrl = route('student.notifications.open', $notification->id);
            ?>

            <a href="<?php echo e($openUrl); ?>" class="text-decoration-none text-dark">
                <div class="p-3 rounded-4 border mb-2 notif-card <?php echo e($isUnread ? 'bg-primary-subtle border-primary-subtle' : ''); ?>">
                    <div class="d-flex justify-content-between align-items-start gap-2">
                        <div class="fw-semibold small mb-1 wrap-anywhere">
                            <?php echo e($notification->title); ?>

                        </div>
                        <?php if($isUnread): ?>
                            <span class="badge rounded-pill bg-primary">New</span>
                        <?php endif; ?>
                    </div>

                    <div class="text-muted small clamp-2" style="white-space: pre-line;">
                        <?php echo e($notification->message); ?>

                    </div>

                    <div class="text-muted small mt-auto pt-2">
                        <i class="bi bi-clock me-1"></i>
                        <?php echo e($notification->sent_at ? $notification->sent_at->format('M d, Y • h:i A') : 'N/A'); ?>

                    </div>
                </div>
            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="empty-state">
                <div class="empty-icon"><i class="bi bi-bell"></i></div>
                <div class="fw-semibold">No notifications</div>
                <div class="text-muted small">You’re all caught up.</div>
            </div>
        <?php endif; ?>
    </div>
</div>
            </div>
        </div>
    </div>
</div>

<style>
    :root{
        --bisu-blue:#003366;
        --bisu-blue-2:#0b4a85;
        --bisu-soft:#eef6ff;
    }

    /* BISU gradient hero */
    .bisu-hero{
        background: linear-gradient(135deg, var(--bisu-blue) 0%, var(--bisu-blue-2) 55%, #0e5aa7 100%);
    }

    /* glass card */
    .glass-card{
        background: rgba(255,255,255,.92);
        backdrop-filter: blur(8px);
    }

    /* avatar */
    .avatar-bisu{
        width: 46px;
        height: 46px;
        border-radius: 16px;
        display:flex;
        align-items:center;
        justify-content:center;
        background: var(--bisu-soft);
        color: var(--bisu-blue);
        font-size: 1.3rem;
        border: 1px solid rgba(0,51,102,.12);
        flex: 0 0 auto;
    }

    .icon-bubble{
        width: 44px;
        height: 44px;
        border-radius: 16px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex: 0 0 auto;
    }

    .bisu-soft{
        background: var(--bisu-soft);
        color: var(--bisu-blue);
        border: 1px solid rgba(0,51,102,.10);
    }

    .dash-card { transition: transform .15s ease, box-shadow .15s ease; }
    .dash-card:hover { transform: translateY(-2px); box-shadow: 0 .9rem 1.6rem rgba(0,0,0,.08) !important; }

    /* Clamp preview to 2 lines */
    .clamp-2{
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Make notification card consistent height on desktop, auto on mobile */
    .notif-card{
        display: flex;
        flex-direction: column;
    }

    /* Better wrapping for long titles/messages */
    .wrap-anywhere{
        overflow-wrap: anywhere;
        word-break: break-word;
    }

    /* Responsive tuning */
    @media (min-width: 992px){
        /* On desktop: make right panels feel equal height */
        .panel-body-scroll{
            max-height: 430px;
            overflow: auto;
            padding-right: .25rem;
        }

        .notif-card{
            min-height: 165px;
        }
    }

    @media (max-width: 991.98px){
        /* On mobile/tablet: remove forced heights so it flows naturally */
        .panel-body-scroll{
            max-height: none;
            overflow: visible;
        }

        .dash-card:hover { transform: none; }
    }

    /* Panel shell: makes cards align & content consistent */
    .panel-shell{
        display: flex;
        flex-direction: column;
    }

    .panel-items{
        display: flex;
        flex-direction: column;
        gap: .5rem;
    }

    /* Nice empty center state */
    .empty-state{
        min-height: 220px;
        border: 1px dashed rgba(0,0,0,.15);
        border-radius: 18px;
        display:flex;
        flex-direction: column;
        align-items:center;
        justify-content:center;
        text-align:center;
        padding: 1.25rem;
        background: rgba(255,255,255,.55);
    }

    .empty-icon{
        width: 52px;
        height: 52px;
        border-radius: 18px;
        display:flex;
        align-items:center;
        justify-content:center;
        background: var(--bisu-soft);
        color: var(--bisu-blue);
        border: 1px solid rgba(0,51,102,.12);
        font-size: 1.4rem;
        margin-bottom: .6rem;
    }

    /* Desktop: match heights + align bottoms */
    @media (min-width: 992px){
        .panel-body-fixed{
            min-height: 470px; /* adjust if you want taller/shorter */
        }
    }
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/student/dashboard.blade.php ENDPATH**/ ?>