<?php $fullWidth = true; ?>


<?php $__env->startSection('page-content'); ?>
<style>
    :root{
        --bisu:#003366;
        --bisu2:#0b4a85;
        --bisuSoft:#eaf2ff;
        --bisuLine:#bcd6ff;

        --ink:#0f172a;
        --muted:#64748b;
        --bg:#f2f7ff;
        --line:#e5e7eb;

        --danger:#dc3545;
        --card:#ffffff;
    }

    /* Page background wash */
    body{ background: var(--bg); }

    .wrap{
        max-width: 1100px;
        margin: 0 auto;
        padding: 14px;
        font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial;
        color: var(--ink);
    }

    /* Header / Title */
    .head{
        background:
            radial-gradient(900px 240px at 12% 0%, rgba(0,51,102,.18) 0%, rgba(0,51,102,0) 55%),
            linear-gradient(135deg, var(--bisu), var(--bisu2));
        border: 1px solid rgba(255,255,255,.20);
        border-radius: 18px;
        padding: 16px;
        box-shadow: 0 .9rem 2.2rem rgba(2,6,23,.16);
        color:#fff;
        margin-bottom: 12px;
    }

    .page-title{
        font-weight: 950;
        font-size: 1.55rem;
        margin: 0;
        letter-spacing: .2px;
    }
    .subtext{
        margin-top: 6px;
        color: rgba(255,255,255,.86);
        font-size: .92rem;
        line-height: 1.4;
        max-width: 70ch;
    }

    .meta-row{
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 10px;
    }

    /* Unread chip */
    .chip{
        display:inline-flex;
        align-items:center;
        gap: 8px;
        background: rgba(255,255,255,.14);
        border: 1px solid rgba(255,255,255,.22);
        padding: 6px 10px;
        border-radius: 999px;
        color:#fff;
        font-size: .82rem;
        white-space: nowrap;
    }
    .chip strong{ font-weight: 950; }

    /* Buttons */
    .btn-bisu{
        background: linear-gradient(180deg, #0b4a85, #003366) !important;
        border-color: rgba(255,255,255,.25) !important;
        color:#fff !important;
        font-weight: 900;
        border-radius: 12px;
        padding: .55rem .85rem;
        white-space: nowrap;
        flex: 0 0 auto;
        width: auto;
    }
    .btn-bisu:hover{
        filter: brightness(1.05);
        box-shadow: 0 .55rem 1.2rem rgba(0,51,102,.22);
    }

    /* Main card */
    .card-shell{
        background: var(--card);
        border: 1px solid var(--bisuLine);
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 .55rem 1.6rem rgba(15,23,42,.06);
    }

    .card-shell .card-body{ padding: 14px; }

    /* List styling */
    .list-group{ border-radius: 14px; overflow: hidden; }

    .notif{
        border: 1px solid var(--line);
        border-left: 6px solid var(--bisuLine);
        border-radius: 16px;
        background:#fff;
        padding: 14px;
        display:flex;
        justify-content:space-between;
        align-items:flex-start;
        gap: 12px;
        margin-bottom: 10px;
        transition: transform .08s ease, border-color .12s ease, background .12s ease;
    }
    .notif:hover{
        border-color: var(--bisuLine);
        background: linear-gradient(180deg, #fff, #fbfdff);
        transform: translateY(-1px);
    }

    /* Make UNREAD pop */
    .notif.unread{
        border-left-color: var(--bisu2);
        box-shadow: 0 .35rem 1rem rgba(0,51,102,.08);
    }

    .notif-title{
        font-weight: 950;
        color: var(--ink);
        margin: 0;
        font-size: .98rem;
        line-height: 1.25;
        word-break: break-word;
    }

    .meta{
        margin-top: 4px;
        color: var(--muted);
        font-size: .82rem;
    }

    .message{
        margin-top: 10px;
        color:#334155;
        line-height: 1.6;
        white-space: pre-line;
        word-break: break-word;
        overflow-wrap: anywhere;
        font-size: .94rem;
    }

    .badge-new{
        background: #dc3545;
        color:#fff;
        border-radius: 999px;
        padding: 4px 10px;
        font-size: .72rem;
        font-weight: 900;
        letter-spacing: .2px;
        border: 1px solid rgba(255,255,255,.25);
        white-space: nowrap;
    }

    .right{
        text-align:right;
        flex: 0 0 auto;
        min-width: 140px;
        display:flex;
        flex-direction:column;
        align-items:flex-end;
        gap: 8px;
    }

    .btn-read{
        border-radius: 12px;
        font-weight: 900;
        padding: .45rem .7rem;
        border-color: var(--bisuLine);
    }
    .btn-read:hover{
        background: var(--bisuSoft);
        border-color: var(--bisuLine);
        color: var(--bisu);
    }

    .read-muted{
        font-size: .82rem;
        color: var(--muted);
        padding-top: .3rem;
    }

    /* Empty state */
    .empty{
        border: 1px dashed var(--bisuLine);
        background: linear-gradient(180deg, #fff, #f8fbff);
        color: var(--muted);
        border-radius: 16px;
        padding: 28px 14px;
        text-align: center;
    }

    /* Pagination container */
    .pager{
        margin-top: 12px;
        border: 1px solid var(--bisuLine);
        border-radius: 16px;
        background: #fff;
        padding: 10px 12px;
        box-shadow: 0 .35rem 1rem rgba(15,23,42,.05);
    }

    /* ✅ Mobile responsiveness */
    @media (max-width: 576px){
        .wrap{ padding: 10px; }
        .head{ padding: 14px; }
        .page-title{ font-size: 1.3rem; }

        .meta-row{
            align-items: stretch;
        }
        .btn-bisu{
            width: 100% !important;
            justify-content: center;
        }

        .notif{
            flex-direction: column;
            align-items: stretch;
        }
        .right{
            align-items: flex-start;
            text-align: left;
            min-width: 0;
        }
        .btn-read{
            width: 100%;
            justify-content: center;
        }
    }
</style>

<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <?php echo e(session('success')); ?>

        <button class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="wrap">

    
    <div class="head">
        <div class="d-flex flex-column gap-1">
            <h2 class="page-title">Cheque Claim Notifications</h2>
            <div class="subtext">Notifications triggered when scholars confirm they already claimed their cheque.</div>
        </div>

        <div class="meta-row">
            <span class="chip">
                Unread: <strong><?php echo e($unreadCount ?? 0); ?></strong>
            </span>

            <a href="<?php echo e(route('coordinator.manage-stipends')); ?>" class="btn btn-bisu btn-sm">
                Back to Manage Stipends
            </a>
        </div>
    </div>

    <div class="card card-shell">
        <div class="card-body">
            <?php if($notifications->isEmpty()): ?>
                <div class="empty">No claim notifications yet.</div>
            <?php else: ?>
                <div class="list-group">

                    <?php $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="notif <?php echo e(!$n->is_read ? 'unread' : ''); ?>">
                            <div style="min-width:0;">
                                <div class="d-flex align-items-start gap-2 flex-wrap">
                                    <p class="notif-title"><?php echo e($n->title); ?></p>

                                    <?php if(!$n->is_read): ?>
                                        <span class="badge-new">NEW</span>
                                    <?php endif; ?>
                                </div>

                                <div class="meta">
                                    <?php echo e(\Carbon\Carbon::parse($n->sent_at ?? $n->created_at)->format('M d, Y h:i A')); ?>

                                </div>

                                <div class="message">
                                    <?php echo e($n->message); ?>

                                </div>
                            </div>

                            <div class="right">
                                <?php if(!$n->is_read): ?>
                                    <form method="POST" action="<?php echo e(route('coordinator.notifications.read', $n->id)); ?>">
                                        <?php echo csrf_field(); ?>
                                        <button class="btn btn-outline-secondary btn-sm btn-read" type="submit">
                                            Mark as read
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <span class="read-muted">Read</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </div>

                <div class="pager">
                    <?php echo e($notifications->links()); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.coordinator', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/coordinator/stipend-claim-notifications.blade.php ENDPATH**/ ?>