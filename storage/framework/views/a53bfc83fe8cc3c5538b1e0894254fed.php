

<?php $__env->startSection('page-content'); ?>
<style>
    :root{
        /* ✅ Stronger Blue Theme (BISU-like) */
        --brand:#0b2e5e;        /* deep blue */
        --brand-600:#123f85;    /* mid blue */
        --brand-700:#0a2550;    /* darker */
        --brand-soft:#eaf2ff;   /* soft blue */
        --brand-line:#bcd6ff;   /* blue border */

        /* Neutrals */
        --ink:#1e293b;          /* ✅ softened ink */
        --muted:#6b7280;        /* ✅ softer muted */
        --line:#e5e7eb;
        --bg:#f2f7ff;
        --card:#ffffff;

        /* Status */
        --warn:#9a3412;
        --warn-bg:#fff7ed;
        --warn-line:#fed7aa;

        --danger:#b91c1c;
        --danger-bg:#fff1f2;
        --danger-line:#fecdd3;
    }

    .wrap{
        max-width: 1240px;
        margin: 0 auto;
        padding: 16px;
        font-family: "Inter", ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial;
        font-weight: 400; /* ✅ not bold */
        color: var(--ink);
    }

    /* ✅ Blue background wash */
    .page-shell{
        background:
            radial-gradient(1100px 380px at 10% 0%, rgba(11,46,94,.14) 0%, rgba(11,46,94,0) 55%),
            linear-gradient(180deg, var(--bg), #ffffff);
        border-radius: 22px;
        padding: 12px;
        border: 1px solid rgba(188,214,255,.65);
    }

    /* ✅ Blue header */
    .header{
        background:
            radial-gradient(900px 220px at 15% 0%, rgba(255,255,255,.16) 0%, rgba(255,255,255,0) 55%),
            linear-gradient(135deg, var(--brand), var(--brand-600));
        border: 1px solid rgba(255,255,255,.18);
        border-radius: 22px;
        padding: 18px;
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:14px;
        box-shadow: 0 .75rem 2rem rgba(2,6,23,.16);
        margin-bottom: 14px;
        color:#fff;
    }

    /* ✅ clean header typography (no heavy bold) */
    .title h2{
        margin:0;
        font-size: 1.45rem;
        font-weight: 600;
        letter-spacing: .2px;
        color:#fff;
    }
    .title p{
        margin:6px 0 0;
        color: rgba(255,255,255,.85);
        font-size: .92rem;
        font-weight: 400;
        line-height: 1.35;
        max-width: 60ch;
    }

    /* ✅ Base cards (wide + blue accent) */
    .card{
        background: #fff;
        border: 1px solid var(--line);
        border-radius: 20px;
        padding: 18px;
        box-shadow: 0 .45rem 1.25rem rgba(15,23,42,.06); /* ✅ softer */
        margin-bottom: 14px;
        width: 100%;
        position: relative;
        overflow: hidden;
    }
    .card::before{
        content:"";
        position:absolute;
        left:0; top:0; right:0;
        height: 4px;
        background: linear-gradient(90deg, var(--brand), var(--brand-600));
        opacity: .9;
    }
    .card:hover{ border-color: var(--brand-line); }

    /* Layout helpers */
    .row{ display:flex; align-items:flex-start; gap:12px; }
    .space{ display:flex; align-items:flex-start; justify-content:space-between; gap:14px; }

    /* Avatar */
    .avatar{
        width:46px; height:46px; border-radius:999px;
        display:flex; align-items:center; justify-content:center;
        background: var(--brand-soft);
        border: 1px solid var(--brand-line);
        color: var(--brand);
        font-weight: 600; /* ✅ softer */
        flex: 0 0 auto;
    }

    /* ✅ clean name */
    .name{
        margin:0;
        font-weight: 500;
        font-size: .95rem;
        color: var(--brand-700);
    }
    .sub{
        margin:4px 0 0;
        font-size: .82rem;
        font-weight: 400;
        color: var(--muted);
        display:flex;
        flex-wrap:wrap;
        gap:8px;
        align-items:center;
    }

    /* Pills */
    .pill{
        display:inline-flex; align-items:center; gap:6px;
        font-size: .72rem;
        font-weight: 500;
        padding:4px 10px;
        border-radius:999px;
        border:1px solid var(--line);
        background:#f8fafc;
        color:#334155;
        white-space:nowrap;
    }
    .pill-blue{
        background: var(--brand-soft);
        border-color: var(--brand-line);
        color: var(--brand);
        font-weight: 500;
    }
    .pill-scheduled{
        background: var(--warn-bg);
        border-color: var(--warn-line);
        color: var(--warn);
        font-weight: 500;
    }

    /* ✅ Tabs - blue but minimal */
    .tabs{
        display:flex;
        gap:10px;
        flex-wrap:wrap;
        padding:10px;
        border:1px solid rgba(188,214,255,.85);
        border-radius: 20px;
        background: rgba(255,255,255,.9);
        box-shadow: 0 .3rem .9rem rgba(15,23,42,.05);
        margin-bottom: 14px;
    }
    .tab{
        text-decoration:none;
        border:1px solid var(--line);
        background:#fff;
        color: var(--ink);
        border-radius:16px;
        padding:10px 14px;
        font-weight: 500; /* ✅ not bold */
        font-size: .9rem;
        display:inline-flex;
        align-items:center;
        gap:8px;
        transition: background .12s ease, border-color .12s ease, box-shadow .12s ease, transform .08s ease;
    }
    .tab:hover{
        background: var(--brand-soft);
        border-color: var(--brand-line);
        transform: translateY(-1px);
    }
    .tab.active-posted{
        background: linear-gradient(180deg, var(--brand-soft), #ffffff);
        border-color: var(--brand-line);
        color: var(--brand);
        box-shadow: 0 .25rem .8rem rgba(18,63,133,.10);
    }
    .tab.active-scheduled{
        background: var(--warn-bg);
        border-color: var(--warn-line);
        color: var(--warn);
        box-shadow: 0 .25rem .8rem rgba(154, 52, 18, .07);
    }

    /* Post content */
    .post-title{
        font-weight: 600; /* ✅ not heavy */
        font-size: 1rem;
        color: var(--brand-700);
        margin-top: 10px;
    }
    .post-body{
        margin-top: 8px;
        color:#334155;
        white-space:pre-line;
        word-break:break-word;
        overflow-wrap:anywhere;
        line-height: 1.65;
        font-size: .92rem;
        font-weight: 400;
    }
    .post-body.clamp{
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 4;
        overflow: hidden;
    }
    .see-more{
        border:none;
        background:transparent;
        color: var(--brand-600);
        font-weight: 500; /* ✅ softer */
        padding: 0;
        cursor:pointer;
        margin-top:10px;
    }
    .see-more:hover{ text-decoration: underline; }

    .divider{ border-top:1px solid #eef2f7; margin: 12px 0; }

    /* ===== Modal (existing) ===== */
    .modal-backdrop-custom{
        position:fixed; inset:0; background:rgba(17,24,39,.45);
        display:none; align-items:center; justify-content:center;
        z-index:1050; padding:16px;
    }
    .modal-custom{
        width:min(820px, 100%);
        max-height: 90vh;
        background:var(--card);
        border:1px solid var(--line);
        border-radius:20px;
        box-shadow: 0 18px 55px rgba(0,0,0,.22);
        overflow:hidden;
        display:flex;
        flex-direction:column;
    }
    .modal-head{
        padding:14px 16px;
        display:flex; align-items:center; justify-content:space-between; gap:10px;
        border-bottom:1px solid #eef2f7;
        background:
            radial-gradient(900px 220px at 0% 0%, rgba(11,46,94,.10), rgba(255,255,255,0) 55%),
            linear-gradient(180deg, #ffffff, #f8fbff);
    }
    .modal-head h3{
        margin:0;
        font-size: .98rem;
        font-weight: 600;
        color: var(--brand-700);
    }
    .modal-close{
        border:none; background:#f1f5f9; color:#0f172a;
        border-radius:12px; padding:8px 10px; cursor:pointer; font-weight: 500;
    }
    .modal-body{
        padding:16px;
        overflow:auto;
        flex:1 1 auto;
    }

    .input, .select, .textarea{
        width:100%;
        border:1px solid var(--line);
        border-radius:14px;
        padding:10px 12px;
        outline:none;
        background:#fff;
        font-size:.92rem;
        font-weight: 400;
    }
    .input:focus, .textarea:focus{
        border-color: var(--brand-line);
        box-shadow: 0 0 0 .25rem rgba(11, 46, 94, .14);
    }
    .textarea{ min-height:120px; resize:vertical; }

    .grid{ display:grid; grid-template-columns: 1fr 1fr; gap:10px; }
    @media(max-width:768px){ .grid{ grid-template-columns:1fr; } }

    .aud{
        display:grid; grid-template-columns: 1fr 1fr;
        gap:10px; margin-top:10px;
    }
    @media(max-width:768px){ .aud{ grid-template-columns:1fr; } }

    .aud-item{
        border:1px solid var(--line);
        border-radius:16px;
        padding:12px;
        cursor:pointer;
        display:flex; gap:10px; align-items:flex-start;
        background:#fff;
        transition: background .12s ease, border-color .12s ease, transform .08s ease;
        font-weight: 400;
    }
    .aud-item:hover{
        background: var(--brand-soft);
        border-color: var(--brand-line);
        transform: translateY(-1px);
    }
    .aud-item input{ margin-top:3px; }
    .aud-item b{
        display:block;
        color:var(--brand-700);
        font-weight: 500; /* ✅ softer */
    }
    .aud-item small{ color:var(--muted); font-weight: 400; }

    .pick-box{
        margin-top:12px;
        border:1px solid var(--line);
        border-radius:16px;
        padding:12px;
        background:#f8fafc;
        display:none;
    }
    .pick-top{
        display:flex; justify-content:space-between; align-items:center; gap:10px;
        margin-bottom:10px;
    }
    .chips{ display:flex; flex-wrap:wrap; gap:8px; }
    .chip{
        background:#fff;
        border:1px solid var(--line);
        border-radius:999px;
        padding:6px 10px;
        font-size:12px;
        font-weight: 400;
        display:inline-flex; align-items:center; gap:8px;
    }
    .chip button{
        border:none; background:#f1f5f9;
        border-radius:999px; padding:2px 8px;
        cursor:pointer; font-weight: 600;
    }

    .results{ max-height:240px; overflow:auto; }
    .res{
        background:#fff; border:1px solid var(--line);
        border-radius:16px; padding:10px 12px;
        display:flex; justify-content:space-between; gap:10px;
        margin-bottom:8px;
        cursor:pointer;
        transition: background .12s ease, border-color .12s ease;
    }
    .res:hover{ background: var(--brand-soft); border-color: var(--brand-line); }
    .res b{ color:var(--brand-700); font-weight: 500; }
    .res small{ color:var(--muted); font-weight: 400; }

    .footer-actions{
        margin-top:14px;
        display:flex;
        justify-content:flex-end;
        gap:10px;
        position: sticky;
        bottom: 0;
        background: #fff;
        padding: 12px 0 0;
        border-top: 1px solid #eef2f7;
    }

    /* ===== Modern button system (blue themed but simple) ===== */
    .btnx{
        border: 1px solid transparent;
        border-radius: 12px;
        padding: 9px 12px;
        font-weight: 500; /* ✅ not bold */
        font-size: .88rem;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        text-decoration: none;
        line-height: 1;
        transition: transform .08s ease, box-shadow .08s ease, background .12s ease, border-color .12s ease, filter .12s ease;
        white-space: nowrap;
    }
    .btnx:active{ transform: translateY(1px); }
    .btnx:focus{ outline: none; box-shadow: 0 0 0 .25rem rgba(11, 46, 94, .16); }

    .btnx-primary{
        background: linear-gradient(180deg, var(--brand), var(--brand-600));
        border-color: rgba(18,63,133,.50);
        color: #fff;
    }
    .btnx-primary:hover{
        filter: brightness(1.03);
        box-shadow: 0 .35rem 1rem rgba(11, 46, 94, .18);
        color:#fff;
    }

    .btnx-secondary{
        background: #fff;
        border-color: var(--line);
        color: var(--ink);
    }
    .btnx-secondary:hover{
        background: #f8fafc;
        border-color: #cbd5e1;
        box-shadow: 0 .25rem .8rem rgba(15,23,42,.05);
    }

    .btnx-soft{
        background: var(--brand-soft);
        border-color: var(--brand-line);
        color: var(--brand);
    }
    .btnx-soft:hover{
        background: #dbeafe;
        border-color: #93c5fd;
    }

    .btnx-warn{
        background: var(--warn-bg);
        border-color: var(--warn-line);
        color: var(--warn);
    }
    .btnx-warn:hover{
        background: #ffedd5;
        border-color: #fdba74;
    }

    .btnx-danger{
        background: var(--danger-bg);
        border-color: var(--danger-line);
        color: var(--danger);
    }
    .btnx-danger:hover{
        background: #ffe4e6;
        border-color: #fda4af;
    }

    .btnx-sm{
        padding: 7px 10px;
        border-radius: 11px;
        font-size: 12px;
        font-weight: 500;
    }

    /* Pagination wrap */
    .pagination-wrap{
        border:1px solid rgba(188,214,255,.85);
        border-radius: 18px;
        background:#fff;
        padding: 10px 12px;
        box-shadow: 0 .25rem .8rem rgba(15,23,42,.05);
    }

    /* Mobile */
    @media(max-width: 576px){
        .space{ flex-direction: column; }
        .space .d-flex{ justify-content:flex-start; }
    }

    #openModalBtn{
        width: auto !important;
        flex: 0 0 auto;
        align-self: center;
        padding: 10px 14px;
        border-radius: 14px;
    }

    @media (max-width: 576px){
        #openModalBtn{
            width: 100% !important;
            justify-content: center;
        }
    }

    /* ✅ Make layout less wide */
.wrap{
    max-width: 1040px;   /* was 1240px */
    margin: 0 auto;
    padding: 16px;
}

/* ✅ Shell not too stretched */
.page-shell{
    max-width: 1040px;   /* keep same as wrap */
    margin: 0 auto;
}

/* ✅ Cards not full-width (centered) */
.card{
    width: 100%;
    max-width: 920px;    /* control card width */
    margin: 0 auto 14px; /* center cards */
}

/* ✅ Keep header aligned with cards */
.header{
    max-width: 920px;
    margin: 0 auto 14px;
}

/* ✅ Keep tabs aligned with cards */
.tabs{
    max-width: 920px;
    margin: 0 auto 14px;
}

/* ✅ Keep pagination aligned with cards */
.pagination-wrap{
    max-width: 920px;
    margin: 12px auto 0;
}
</style>

<?php
    $list = ($tab ?? 'posted') === 'scheduled' ? $scheduledAnnouncements : $postedAnnouncements;
?>

<div class="wrap">
  <div class="page-shell">

    <div class="header">
        <div class="title">
            <h2>Announcements</h2>
            <p>Post updates and notify students.</p>
        </div>
        <button class="btnx btnx-primary" id="openModalBtn">Create Announcement</button>
    </div>

    <?php if(session('success')): ?>
        <div class="card" style="border-color:#bbf7d0;background:#f0fdf4;color:#166534;">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    
    <div class="tabs">
        <a href="<?php echo e(route('coordinator.manage-announcements', ['tab' => 'posted'])); ?>"
           class="tab <?php echo e(($tab ?? 'posted') === 'posted' ? 'active-posted' : ''); ?>">
            Posted
        </a>

        <a href="<?php echo e(route('coordinator.manage-announcements', ['tab' => 'scheduled'])); ?>"
           class="tab <?php echo e(($tab ?? 'posted') === 'scheduled' ? 'active-scheduled' : ''); ?>">
            Scheduled Posts
        </a>
    </div>

    
    <?php $__empty_1 = true; $__currentLoopData = $list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <?php
            $isScheduled = $post->posted_at && $post->posted_at->isFuture();
        ?>

        <div class="card" style="<?php echo e($isScheduled ? 'border-color:var(--warn-line); background: linear-gradient(180deg, #fff, #fffaf5);' : ''); ?>">
            <div class="space">
                <div class="row">
                    <div class="avatar">
                        <?php echo e(strtoupper(substr($post->creator->firstname ?? 'C', 0, 1))); ?>

                    </div>

                    <div>
                        <p class="name"><?php echo e($post->creator->firstname ?? 'Coordinator'); ?> <?php echo e($post->creator->lastname ?? ''); ?></p>

                        <p class="sub">
                            <span class="js-timeago"
                                  data-time="<?php echo e($post->posted_at?->toIso8601String()); ?>"
                                  title="<?php echo e($post->posted_at?->format('M d, Y h:i A')); ?>">
                            </span>

                            <span class="pill pill-blue">
                                <?php echo e(match($post->audience){
                                    'all_students' => 'All Students',
                                    'all_scholars' => 'All Scholars',
                                    'specific_students' => 'Specific Students',
                                    'specific_scholars' => 'Specific Scholars',
                                    default => 'Audience'
                                }); ?>

                            </span>

                            <?php if($isScheduled): ?>
                                <span class="pill pill-scheduled">🕒 Scheduled</span>
                            <?php endif; ?>

                            <span class="pill" title="Views">👁️ <?php echo e($post->views_count ?? 0); ?></span>
                        </p>
                    </div>
                </div>

                
                <div class="d-flex gap-2 flex-wrap ms-auto">
                    <?php if($isScheduled): ?>
                        <button type="button"
                                class="btnx btnx-soft btnx-sm"
                                onclick="openEditSchedule(<?php echo e($post->id); ?>, '<?php echo e($post->posted_at->format('Y-m-d\TH:i')); ?>')">
                            ✏️ Edit Time
                        </button>

                        <form action="<?php echo e(route('coordinator.announcements.cancel-schedule', $post->id)); ?>"
                              method="POST"
                              onsubmit="return confirm('Cancel this scheduled post?')">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PATCH'); ?>
                            <button class="btnx btnx-warn btnx-sm" type="submit">
                                ⛔ Cancel
                            </button>
                        </form>
                    <?php endif; ?>

                    <form action="<?php echo e(route('coordinator.announcements.destroy', $post->id)); ?>"
                          method="POST"
                          onsubmit="return confirm('Delete this announcement? This cannot be undone.')">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button class="btnx btnx-danger btnx-sm" type="submit">
                            🗑 Delete
                        </button>
                    </form>
                </div>

            </div>

            <div class="post-title"><?php echo e($post->title); ?></div>
            <div class="post-body clamp" id="body-<?php echo e($post->id); ?>"><?php echo e($post->description); ?></div>

            <?php if(mb_strlen($post->description ?? '') > 220): ?>
                <button type="button" class="see-more" data-target="<?php echo e($post->id); ?>">See more</button>
            <?php endif; ?>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="card" style="text-align:center; color:var(--muted);">
            No <?php echo e(($tab ?? 'posted') === 'scheduled' ? 'scheduled posts' : 'announcements'); ?> yet.
        </div>
    <?php endif; ?>

    <div class="pagination-wrap mt-3">
        <?php echo e($list->links()); ?>

    </div>

  </div>
</div>


<div class="modal-backdrop-custom" id="editModal">
    <div class="modal-custom" style="width:min(520px, 100%);">
        <div class="modal-head">
            <h3>Edit Scheduled Time</h3>
            <button class="modal-close" type="button" onclick="closeEditModal()">✕</button>
        </div>

        <form class="modal-body" method="POST" id="editScheduleForm">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PATCH'); ?>

            <label class="sub" style="color:var(--muted);">New Schedule Date & Time</label>
            <input type="datetime-local" name="posted_at" class="input" id="editPostedAt" required>

            <div class="footer-actions">
                <button type="button" class="btnx btnx-secondary" onclick="closeEditModal()">Cancel</button>
                <button type="submit" class="btnx btnx-primary">Save</button>
            </div>
        </form>
    </div>
</div>


<div class="modal-backdrop-custom" id="createModal">
    <div class="modal-custom">
        <div class="modal-head">
            <h3>Create Announcement</h3>
            <button class="modal-close" id="closeModalBtn">✕</button>
        </div>

        <form class="modal-body" action="<?php echo e(route('coordinator.announcements.store')); ?>" method="POST" id="announceForm">
            <?php echo csrf_field(); ?>

            <div class="grid">
                <div>
                    <label class="sub" style="color:var(--muted);">Title</label>
                    <input class="input" name="title" placeholder="Announcement title…" required>
                </div>
                <div>
                    <label class="sub" style="color:var(--muted);">Posting Time</label>
                    <input class="input" type="datetime-local" name="posted_at"
                           value="<?php echo e(now()->format('Y-m-d\TH:i')); ?>" required>
                </div>
            </div>

            <div style="margin-top:10px;">
                <label class="sub" style="color:var(--muted);">Description</label>
                <textarea class="textarea" name="description" placeholder="What do you want to announce?" required></textarea>
            </div>

            <div style="margin-top:10px;">
                <label class="sub" style="color:var(--muted);">Audience</label>
                <div class="aud" id="audienceCards">
                    <label class="aud-item">
                        <input type="radio" name="audience" value="all_students" checked>
                        <div><b>All Students</b><small>Notify all student accounts</small></div>
                    </label>
                    <label class="aud-item">
                        <input type="radio" name="audience" value="all_scholars">
                        <div><b>All Scholars</b><small>Notify all scholars only</small></div>
                    </label>
                    <label class="aud-item">
                        <input type="radio" name="audience" value="specific_students">
                        <div><b>Specific Students</b><small>Pick students (search, no preload)</small></div>
                    </label>
                    <label class="aud-item">
                        <input type="radio" name="audience" value="specific_scholars">
                        <div><b>Specific Scholars</b><small>Pick scholars (search, no preload)</small></div>
                    </label>
                </div>
            </div>

            
            <div class="pick-box" id="pickerBox">
                <div class="pick-top">
                    <div>
                        <b style="color:var(--brand-700); font-weight:500;">Select recipients</b><br>
                        <small class="sub">Type a name / student id / email to search</small>
                    </div>
                    <span class="pill" id="pickedCount">0 selected</span>
                </div>

                <input class="input" id="searchInput" placeholder="Search…" autocomplete="off">

                <div class="divider"></div>

                <div class="chips" id="chips"></div>

                <div class="divider"></div>

                <div class="results" id="results"></div>
            </div>

            <div class="footer-actions">
                <button type="button" class="btnx btnx-secondary" id="cancelBtn">Cancel</button>
                <button type="submit" class="btnx btnx-primary">Post & Notify</button>
            </div>
        </form>
    </div>
</div>

<script>
(function(){
    // ===== create modal (your existing logic) =====
    const modal = document.getElementById('createModal');
    const openBtn = document.getElementById('openModalBtn');
    const closeBtn = document.getElementById('closeModalBtn');
    const cancelBtn = document.getElementById('cancelBtn');

    const pickerBox = document.getElementById('pickerBox');
    const searchInput = document.getElementById('searchInput');
    const results = document.getElementById('results');
    const chips = document.getElementById('chips');
    const pickedCount = document.getElementById('pickedCount');

    let selected = new Map();
    let currentType = null;
    let debounce = null;

    function openModal(){
        const form = document.getElementById('announceForm');
        form.reset();

        selected.clear();
        updatePickedUI();
        setPickerVisibility();

        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeModal(){
        modal.style.display = 'none';
        document.body.style.overflow = '';

        const form = document.getElementById('announceForm');
        form.reset();

        results.innerHTML = '';
        searchInput.value = '';
        selected.clear();
        updatePickedUI();
        setPickerVisibility();
    }

    openBtn?.addEventListener('click', openModal);
    closeBtn?.addEventListener('click', closeModal);
    cancelBtn?.addEventListener('click', closeModal);
    modal?.addEventListener('click', (e)=>{ if(e.target === modal) closeModal(); });

    function updatePickedUI(){
        chips.innerHTML = '';
        selected.forEach((v, k) => {
            const el = document.createElement('span');
            el.className = 'chip';
            el.innerHTML = `${v.label}<button type="button" aria-label="remove">×</button>`;
            el.querySelector('button').addEventListener('click', ()=>{
                selected.delete(k);
                updatePickedUI();
            });
            chips.appendChild(el);
        });
        pickedCount.textContent = `${selected.size} selected`;

        document.querySelectorAll('.dyn-picked').forEach(x => x.remove());

        const aud = document.querySelector('input[name="audience"]:checked')?.value;
        const form = document.getElementById('announceForm');

        if (aud === 'specific_students') {
            selected.forEach((v) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'selected_users[]';
                input.value = v.id;
                input.className = 'dyn-picked';
                form.appendChild(input);
            });
        }

        if (aud === 'specific_scholars') {
            selected.forEach((v) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'selected_scholars[]';
                input.value = v.id;
                input.className = 'dyn-picked';
                form.appendChild(input);
            });
        }
    }

    function setPickerVisibility(){
        const aud = document.querySelector('input[name="audience"]:checked')?.value;
        const needsPicker = (aud === 'specific_students' || aud === 'specific_scholars');
        pickerBox.style.display = needsPicker ? 'block' : 'none';

        if (needsPicker) {
            currentType = (aud === 'specific_students') ? 'students' : 'scholars';
        } else {
            currentType = null;
            selected.clear();
            updatePickedUI();
        }
    }

    document.querySelectorAll('input[name="audience"]').forEach(r=>{
        r.addEventListener('change', ()=>{
            selected.clear();
            updatePickedUI();
            setPickerVisibility();
        });
    });
    setPickerVisibility();

    async function fetchRecipients(q){
        if(!currentType) return [];
        const url = new URL("<?php echo e(route('coordinator.announcements.recipients')); ?>", window.location.origin);
        url.searchParams.set('type', currentType);
        url.searchParams.set('q', q);

        const res = await fetch(url.toString(), { headers: { 'Accept': 'application/json' } });
        return await res.json();
    }

    function renderResults(list){
        results.innerHTML = '';
        if(!list.length){
            results.innerHTML = `<div class="sub">No results.</div>`;
            return;
        }

        list.forEach(item=>{
            const id = item.id;
            const fullName = `${item.firstname ?? ''} ${item.lastname ?? ''}`.trim();
            const meta = `${item.student_id ?? ''}${item.bisu_email ? ' • ' + item.bisu_email : ''}`.trim();
            const key = currentType + ':' + id;

            const card = document.createElement('div');
            card.className = 'res';
            card.innerHTML = `
                <div>
                    <b>${fullName || 'Unnamed'}</b><br>
                    <small>${meta || ''}</small>
                </div>
                <div class="pill">${selected.has(key) ? 'Selected' : 'Select'}</div>
            `;

            card.addEventListener('click', ()=>{
                if(selected.has(key)){
                    selected.delete(key);
                } else {
                    selected.set(key, { id, label: fullName });
                }
                updatePickedUI();
                renderResults(list);
            });

            results.appendChild(card);
        });
    }

    searchInput?.addEventListener('input', ()=>{
        clearTimeout(debounce);
        debounce = setTimeout(async ()=>{
            const q = searchInput.value.trim();
            if(q.length < 2){
                results.innerHTML = `<div class="sub">Type at least 2 characters…</div>`;
                return;
            }
            results.innerHTML = `<div class="sub">Searching…</div>`;
            const data = await fetchRecipients(q);
            renderResults(data);
        }, 250);
    });

    document.getElementById('announceForm')?.addEventListener('submit', (e)=>{
        const aud = document.querySelector('input[name="audience"]:checked')?.value;
        if ((aud === 'specific_students' || aud === 'specific_scholars') && selected.size === 0) {
            e.preventDefault();
            alert('Please select at least 1 recipient.');
        }
    });

    // ===== timeago =====
    function timeAgoText(date){
        const now = new Date();
        const then = new Date(date);
        if (isNaN(then.getTime())) return '';

        const diff = Math.floor((now - then) / 1000);

        if (diff < 0) {
            const mins = Math.ceil(Math.abs(diff) / 60);
            if (mins < 60) return `scheduled in ${mins}m`;
            const hrs = Math.ceil(mins / 60);
            if (hrs < 24) return `scheduled in ${hrs}h`;
            const days = Math.ceil(hrs / 24);
            return `scheduled in ${days}d`;
        }

        if (diff < 5) return 'just now';
        if (diff < 60) return `${diff}s ago`;

        const mins = Math.floor(diff / 60);
        if (mins < 60) return `${mins}m ago`;

        const hrs = Math.floor(mins / 60);
        if (hrs < 24) return `${hrs}h ago`;

        const days = Math.floor(hrs / 24);
        if (days === 1) return 'yesterday';
        if (days < 7) return `${days}d ago`;

        return then.toLocaleDateString(undefined, { year:'numeric', month:'short', day:'numeric' });
    }

    function refreshTimeago(){
        document.querySelectorAll('.js-timeago').forEach(el=>{
            const t = el.getAttribute('data-time');
            if(!t) return;
            const txt = timeAgoText(t);
            if(txt) el.textContent = txt;
        });
    }

    refreshTimeago();
    setInterval(refreshTimeago, 30000);

    document.querySelectorAll('.see-more').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.getAttribute('data-target');
            const body = document.getElementById('body-' + id);
            if (!body) return;

            const isClamped = body.classList.contains('clamp');
            if (isClamped) {
                body.classList.remove('clamp');
                btn.textContent = 'Show less';
            } else {
                body.classList.add('clamp');
                btn.textContent = 'See more';
            }
        });
    });

})();
</script>

<script>
    function openEditSchedule(id, value){
        const modal = document.getElementById('editModal');
        const input = document.getElementById('editPostedAt');
        const form  = document.getElementById('editScheduleForm');

        input.value = value;

        let url = "<?php echo e(route('coordinator.announcements.reschedule', ['announcement' => '__ID__'])); ?>";
        url = url.replace('__ID__', id);

        form.action = url;

        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeEditModal(){
        const modal = document.getElementById('editModal');
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }

    document.getElementById('editModal')?.addEventListener('click', (e)=>{
        if(e.target.id === 'editModal') closeEditModal();
    });
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.coordinator', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/coordinator/manage-announcements.blade.php ENDPATH**/ ?>