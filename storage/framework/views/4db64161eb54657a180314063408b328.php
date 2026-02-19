
<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Scholarship Information Management System</title>

    
    <link rel="stylesheet" href="<?php echo e(asset('bootstrap/css/bootstrap.min.css')); ?>">

    <style>
        :root{
            --brand:#0b2e5e;
            --brand2:#123f85;
            --bg:#f4f7fb;
            --text:#0f172a;
            --muted:#6b7280;
            --line:#e5e7eb;
        }

        html{ scroll-behavior:smooth; }
        body{ background:var(--bg); color:var(--text); }

        /* Navbar */
        .nav-guest{
            border-bottom: 1px solid rgba(229,231,235,.9);
            background: rgba(255,255,255,.92) !important;
            backdrop-filter: blur(10px);
        }
        .brand-badge{
            width:42px; height:42px;
            border-radius:14px;
            background: linear-gradient(135deg, var(--brand), var(--brand2));
            display:grid; place-items:center;
            color:#fff; font-weight:800;
            box-shadow: 0 10px 24px rgba(11,46,94,.18);
            user-select:none;
        }
        .nav-link{
            font-weight:600;
            color:#0f172a !important;
        }
        .nav-link:hover{ color:var(--brand) !important; }

        .btn-login{
            background: var(--brand);
            border: none;
            font-weight: 700;
            padding: .55rem 1.05rem;
            border-radius: 12px;
            box-shadow: 0 10px 24px rgba(11,46,94,.18);
        }
        .btn-login:hover{ background: var(--brand2); }

        /* Hero */
        .hero{
            border-radius: 22px;
            overflow:hidden;
            background:
                radial-gradient(900px 380px at 10% 10%, rgba(29,95,208,.20), transparent 60%),
                radial-gradient(700px 320px at 90% 10%, rgba(11,46,94,.25), transparent 55%),
                linear-gradient(135deg, #ffffff, #f6f9ff);
            border: 1px solid rgba(229,231,235,.9);
            box-shadow: 0 18px 44px rgba(11,46,94,.10);
        }
        .hero-title{
            color: var(--brand);
            font-weight: 900;
            letter-spacing: .2px;
            line-height: 1.08;
        }
        .muted{ color: var(--muted); }

        /* Cards */
        .soft-card{
            border: 1px solid rgba(229,231,235,.9);
            border-radius: 18px;
            background: #fff;
            box-shadow: 0 14px 34px rgba(15,23,42,.06);
        }

        .stat-card{
            border-radius: 18px;
            border: 1px solid rgba(229,231,235,.9);
            background: linear-gradient(180deg, #ffffff, #f7faff);
            box-shadow: 0 14px 34px rgba(11,46,94,.08);
            position: relative;
            overflow:hidden;
        }
        .stat-card::before{
            content:'';
            position:absolute;
            left:0; top:0;
            width:6px; height:100%;
            background: var(--brand);
        }

        .section-title{
            font-weight: 900;
            color: var(--brand);
            letter-spacing: .2px;
        }

        .pill{
            display:inline-flex;
            align-items:center;
            gap:.45rem;
            border: 1px solid rgba(11,46,94,.18);
            background: rgba(11,46,94,.06);
            color: var(--brand);
            padding: .35rem .65rem;
            border-radius: 999px;
            font-weight: 700;
            font-size: .86rem;
        }

        .badge-status{
            border-radius: 999px;
            padding: .35rem .65rem;
            font-weight: 800;
            letter-spacing: .2px;
            border: 1px solid rgba(229,231,235,.9);
            background: #f8fafc;
        }
        .badge-open{ color:#166534; background:#ecfdf5; border-color:#bbf7d0; }
        .badge-closed{ color:#7f1d1d; background:#fef2f2; border-color:#fecaca; }

        .list-item{
            border: 1px solid rgba(229,231,235,.9);
            border-radius: 16px;
            background: #fff;
            padding: 1rem;
            box-shadow: 0 10px 26px rgba(15,23,42,.05);
        }

        /* Accordion (FAQ) - subtle modern */
        .accordion-button{
            border-radius: 14px !important;
            font-weight: 800;
        }
        .accordion-item{
            border: 1px solid rgba(229,231,235,.9);
            border-radius: 16px;
            overflow:hidden;
            box-shadow: 0 10px 26px rgba(15,23,42,.05);
            margin-bottom: .75rem;
        }

        footer{
            border-top: 1px solid rgba(229,231,235,.9);
            background:#ffffff;
        }
        .brand-logo{
            width:42px;
            height:42px;
            object-fit:cover;
            border-radius:14px;              /* modern rounded square */
            border:1px solid rgba(229,231,235,.9);
            box-shadow: 0 10px 24px rgba(11,46,94,.14);
            background:#fff;
        }

    </style>
</head>
<body>


<nav class="navbar navbar-expand-lg sticky-top nav-guest">
    <div class="container py-2">
        <a class="navbar-brand d-flex align-items-center gap-2" href="<?php echo e(route('landing')); ?>">
            <img src="<?php echo e(asset('images/scholarship_logo.jpg')); ?>"
                alt="Logo"
                class="brand-logo">

            <div class="d-flex flex-column">
                <span class="fw-bold" style="line-height:1.1;">Scholarship Management Information System</span>
                <small class="muted">BISU Candijay Campus</small>
            </div>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#guestNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="guestNav">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
                <li class="nav-item">
                    <a class="nav-link" href="#home">Home</a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        Scholarships
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                        <li><a class="dropdown-item" href="#scholarships">Available</a></li>
                        <li><a class="dropdown-item" href="#requirements">Requirements</a></li>
                        <li><a class="dropdown-item" href="#howto">How to Apply</a></li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#announcements">Announcements</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#faq">FAQ</a>
                </li>

                <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                    <a href="<?php echo e(route('login')); ?>" class="btn btn-login text-white w-100">
                        Login
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container my-4" id="home">

    
    <div class="hero p-4 p-lg-5 mb-4">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <div class="pill mb-3">Real-time • Scholarships • Announcements</div>
                <h1 class="hero-title display-5 mb-3">
                    A modern scholarship portal for students and coordinators.
                </h1>
                <p class="muted fs-5 mb-4">
                    View available scholarships, read requirements, stay updated with announcements,
                    and understand how to apply — all in one place.
                </p>

                <div class="d-flex flex-column flex-sm-row gap-2">
                    <a href="#scholarships" class="btn btn-primary px-4 py-2 rounded-3 fw-bold"
                       style="background:var(--brand); border:none;">
                        Explore Scholarships
                    </a>
                    <a href="#announcements" class="btn btn-outline-secondary px-4 py-2 rounded-3 fw-bold">
                        View Announcements
                    </a>
                </div>

                <div class="mt-4 muted">
                    Tip: Use the menu above to jump to sections quickly.
                </div>
            </div>

            <div class="col-lg-5">
                <div class="soft-card p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="fw-bold" style="color:var(--brand)">System Overview</div>
                        <span class="badge-status">Guest View</span>
                    </div>

                    <div class="mt-3">
                        <div class="d-flex align-items-center justify-content-between py-2 border-bottom">
                            <div class="muted">Live Scholars Count</div>
                            <div class="fw-bold"><?php echo e(number_format($scholarsCount)); ?></div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between py-2 border-bottom">
                            <div class="muted">Scholarships Shown</div>
                            <div class="fw-bold"><?php echo e(number_format($scholarships->count())); ?></div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between py-2">
                            <div class="muted">Latest Announcements</div>
                            <div class="fw-bold"><?php echo e(number_format($announcements->count())); ?></div>
                        </div>
                    </div>

                    <div class="mt-3 p-3 rounded-3" style="background:#f8fafc; border:1px solid rgba(229,231,235,.9);">
                        <div class="fw-bold mb-1">Why this matters</div>
                        <div class="muted">
                            Students don’t need to ask repeatedly — the system centralizes updates and scholarship details.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat-card p-3">
                <div class="muted">Total Scholars</div>
                <div class="display-6 fw-bold" style="color:var(--brand)"><?php echo e(number_format($scholarsCount)); ?></div>
                <div class="muted">Real-time count from database</div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="stat-card p-3">
                <div class="muted">Scholarships (Latest)</div>
                <div class="display-6 fw-bold" style="color:var(--brand)"><?php echo e(number_format($scholarships->count())); ?></div>
                <div class="muted">Recently created scholarships</div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="stat-card p-3">
                <div class="muted">Announcements (Latest)</div>
                <div class="display-6 fw-bold" style="color:var(--brand)"><?php echo e(number_format($announcements->count())); ?></div>
                <div class="muted">Most recent posts</div>
            </div>
        </div>
    </div>

    
    <div class="mb-4" id="scholarships">
        <div class="mb-2">
            <h3 class="section-title mb-0">Available Scholarships</h3>
            <div class="muted">Showing your latest entries (real-time).</div>
        </div>

        <?php if($scholarships->isEmpty()): ?>
            <div class="soft-card p-4">
                <div class="alert alert-light border mb-0">No scholarships found yet.</div>
            </div>
        <?php else: ?>
            <div class="row g-3">
                <?php $__currentLoopData = $scholarships; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $status = strtolower($s->status ?? '');
                        $isOpen = $status === 'open';
                    ?>

                    <div class="col-md-6 col-lg-4">
                        <div class="soft-card p-3 h-100">
                            <div class="d-flex justify-content-between align-items-start gap-2">
                                <div class="fw-bold" style="color:var(--brand)">
                                    <?php echo e($s->scholarship_name ?? '—'); ?>

                                </div>
                                <span class="badge-status <?php echo e($isOpen ? 'badge-open' : 'badge-closed'); ?>">
                                    <?php echo e(strtoupper($s->status ?? '—')); ?>

                                </span>
                            </div>

                            <?php if(!empty($s->benefactor)): ?>
                                <div class="mt-2 muted">
                                    Benefactor: <span class="fw-semibold"><?php echo e($s->benefactor); ?></span>
                                </div>
                            <?php endif; ?>

                            <?php if(!empty($s->description)): ?>
                                <div class="mt-2">
                                    <?php echo e(\Illuminate\Support\Str::limit($s->description, 150)); ?>

                                </div>
                            <?php endif; ?>

                            <div class="mt-3">
                                <a href="#howto" class="btn btn-outline-secondary btn-sm fw-bold rounded-3">
                                    How to Apply
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
    </div>

    
    <div class="soft-card p-4 mb-4" id="requirements">
        <h3 class="section-title mb-1">Requirements (General Guide)</h3>
        <div class="muted mb-3">
            This is a general guide. Exact requirements may vary per scholarship.
        </div>

        <div class="row g-3">
            <div class="col-md-6">
                <div class="list-item h-100">
                    <div class="fw-bold mb-1">Common Documents</div>
                    <ul class="mb-0 muted">
                        <li>Application form</li>
                        <li>Certificate of enrollment / registration</li>
                        <li>Grades (TOR / copy of grades)</li>
                        <li>Proof of residency (if required)</li>
                        <li>Valid ID / school ID</li>
                    </ul>
                </div>
            </div>

            <div class="col-md-6">
                <div class="list-item h-100">
                    <div class="fw-bold mb-1">Typical Qualifications</div>
                    <ul class="mb-0 muted">
                        <li>Good academic standing</li>
                        <li>No major disciplinary record</li>
                        <li>Complete submission before deadline</li>
                        <li>Some scholarships require financial need proof</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    
    <div class="soft-card p-4 mb-4" id="howto">
        <h3 class="section-title mb-1">How to Apply</h3>
        <div class="muted mb-3">Simple steps students can follow.</div>

        <div class="row g-3">
            <div class="col-md-3">
                <div class="list-item h-100">
                    <div class="pill mb-2">Step 1</div>
                    <div class="fw-bold">Check scholarship</div>
                    <div class="muted">Read description + benefactor + status.</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="list-item h-100">
                    <div class="pill mb-2">Step 2</div>
                    <div class="fw-bold">Prepare documents</div>
                    <div class="muted">Complete requirements early.</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="list-item h-100">
                    <div class="pill mb-2">Step 3</div>
                    <div class="fw-bold">Submit on time</div>
                    <div class="muted">Follow the scholarship office instructions.</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="list-item h-100">
                    <div class="pill mb-2">Step 4</div>
                    <div class="fw-bold">Wait for updates</div>
                    <div class="muted">Monitor announcements for results.</div>
                </div>
            </div>
        </div>

        <div class="mt-3">
            <a href="<?php echo e(route('login')); ?>" class="btn btn-primary fw-bold rounded-3"
               style="background:var(--brand); border:none;">
                Login to access your dashboard
            </a>
        </div>
    </div>

    
    <div class="mb-4" id="announcements">
        <div class="mb-2">
            <h3 class="section-title mb-0">Announcements</h3>
            <div class="muted">Latest updates from the scholarship office.</div>
        </div>

        <?php if($announcements->isEmpty()): ?>
            <div class="soft-card p-4">
                <div class="alert alert-light border mb-0">No announcements posted yet.</div>
            </div>
        <?php else: ?>
            <div class="row g-3">
                <?php $__currentLoopData = $announcements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $date = $a->posted_at ?? $a->created_at; ?>
                    <div class="col-lg-6">
                        <div class="list-item h-100">
                            <div class="d-flex justify-content-between align-items-start gap-2">
                                <div class="fw-bold"><?php echo e($a->title ?? 'Announcement'); ?></div>
                                <small class="muted text-nowrap">
                                    <?php echo e(\Carbon\Carbon::parse($date)->format('M d, Y')); ?>

                                </small>
                            </div>

                            <?php if(!empty($a->description)): ?>
                                <div class="mt-2 muted">
                                    <?php echo e(\Illuminate\Support\Str::limit($a->description, 180)); ?>

                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
    </div>

    
    <div class="soft-card p-4 mb-4" id="faq">
        <h3 class="section-title mb-1">FAQ</h3>
        <div class="muted mb-3">Quick answers for common student questions.</div>

        <div class="accordion" id="faqAcc">
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                        Who can apply for scholarships?
                    </button>
                </h2>
                <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAcc">
                    <div class="accordion-body muted">
                        Usually students who meet the scholarship’s qualifications and complete required documents.
                        Requirements vary per scholarship.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                        Where can I see announcements and deadlines?
                    </button>
                </h2>
                <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAcc">
                    <div class="accordion-body muted">
                        Check the Announcements section on this landing page. After login, students can view updates in the dashboard.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                        Do I need to login to apply?
                    </button>
                </h2>
                <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAcc">
                    <div class="accordion-body muted">
                        You can browse scholarship info as a guest, but personal features (notifications, tracking) require login.
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<footer class="py-4">
    <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
        <div>
            <div class="fw-bold" style="color:var(--brand)">Bohol Island State University - Candijay Campus</div>
            <div class="muted">Scholarship Information Management System</div>
        </div>
        <div class="muted">© <?php echo e(now()->year); ?></div>
    </div>
</footer>


<script src="<?php echo e(asset('bootstrap/js/bootstrap.bundle.min.js')); ?>"></script>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/landing.blade.php ENDPATH**/ ?>