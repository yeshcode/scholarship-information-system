<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Scholarship Information Management System</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">

    <style>
    :root{
        --brand-blue:#0b2e5e;
        --brand-blue-soft:#e7eef8;
        --brand-accent:#1d5fd0;
    }

    body{
        background:#f4f7fb;
    }

    /* Navbar */
    .navbar-brand img{
        height:44px;
        width:44px;
        object-fit:cover;
        border-radius:50%;
        border:2px solid var(--brand-blue);
    }

    /* Login button */
    .btn-login{
        background:var(--brand-blue);
        border:none;
        padding:.45rem 1.1rem;
        font-weight:600;
        letter-spacing:.2px;
    }

    .btn-login:hover{
        background:#123f85;
    }

    /* Carousel */
    .hero-card{
        border-radius:16px;
        overflow:hidden;
        border:none;
    }

    .carousel-item img{
        height:380px;
        object-fit:cover;
        filter:brightness(.78);
    }

    .carousel-caption{
        text-shadow:0 6px 18px rgba(0,0,0,.55);
    }

    /* Cards */
    .stat-card{
        border:none;
        border-radius:16px;
        background:linear-gradient(180deg,#ffffff,#f6f9ff);
        box-shadow:0 10px 30px rgba(11,46,94,.08);
        position:relative;
        overflow:hidden;
    }

    .stat-card::before{
        content:'';
        position:absolute;
        left:0;
        top:0;
        width:6px;
        height:100%;
        background:var(--brand-blue);
    }

    .section-title{
        color:var(--brand-blue);
        font-weight:800;
    }

    .card{
        border:none;
        border-radius:16px;
        box-shadow:0 10px 28px rgba(11,46,94,.07);
    }

    .list-group-item{
        border:none;
        border-bottom:1px solid #eef2f7;
        padding:1rem 1rem;
    }

    .list-group-item:last-child{
        border-bottom:none;
    }

    .muted{
        color:#6c7a92;
    }

    .badge-soft{
        background:rgba(11,46,94,.08);
        color:var(--brand-blue);
        border:1px solid rgba(11,46,94,.18);
        font-weight:600;
    }

    footer{
        background:var(--brand-blue);
        color:#fff;
    }
</style>

</head>

<body>

<!-- ✅ Top Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('landing') }}">
            <img src="{{ asset('images/scholarship_logo.jpg') }}" alt="Logo">
            <div>
                <div class="fw-bold" style="line-height:1.1;">
                    Scholarship Information Management System
                </div>
                <small class="muted">Bohol Island State University - Candijay Campus</small>
            </div>
        </a>

        <div class="ms-auto">
            <a href="{{ route('login') }}" class="btn btn-login text-white">
                Login
            </a>
        </div>
    </div>
</nav>

<div class="container my-4">

    <!-- ✅ Carousel / Hero -->
    <div class="card hero-card shadow-sm mb-4">
        <div id="landingCarousel" class="carousel slide" data-bs-ride="carousel">

            <div class="carousel-indicators">
                <button type="button" data-bs-target="#landingCarousel" data-bs-slide-to="0" class="active"></button>
                <button type="button" data-bs-target="#landingCarousel" data-bs-slide-to="1"></button>
                <button type="button" data-bs-target="#landingCarousel" data-bs-slide-to="2"></button>
            </div>

            <div class="carousel-inner">
                <div class="carousel-item active">
                    <!-- ✅ Add your images here: public/images/landing1.jpg -->
                    <img src="{{ asset('images/scholarship-landing1.jpg') }}" class="d-block w-100" alt="Slide 1">
                    <div class="carousel-caption text-start">
                        <h2 class="fw-bold">Stay updated with scholarships</h2>
                        <p class="mb-0">Announcements, scholarship info, and monitoring — all in one place.</p>
                    </div>
                </div>

                <div class="carousel-item">
                    <img src="{{ asset('images/landing2.png') }}" class="d-block w-100" alt="Slide 2">
                    <div class="carousel-caption text-start">
                        <h2 class="fw-bold">Real-time information</h2>
                        <p class="mb-0">See the latest posted announcements and available scholarship details.</p>
                    </div>
                </div>

                <div class="carousel-item">
                    <img src="{{ asset('images/scholarship-landing2.jpg') }}" class="d-block w-100" alt="Slide 3">
                    <div class="carousel-caption text-start">
                        <h2 class="fw-bold">For BISU Candijay scholars</h2>
                        <p class="mb-0">A simple platform designed for students and coordinators.</p>
                    </div>
                </div>
            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#landingCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
                <span class="visually-hidden">Previous</span>
            </button>

            <button class="carousel-control-next" type="button" data-bs-target="#landingCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>

    <!-- ✅ Stats Row -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card stat-card shadow-sm">
                <div class="card-body">
                    <div class="muted">Total Scholars</div>

                    <div class="display-6 fw-bold" style="color:var(--brand-blue)">
                        {{ number_format($scholarsCount) }}
                    </div>

                    <div class="muted">Real-time count from the system</div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card stat-card shadow-sm">
                <div class="card-body">
                    <div class="muted">Scholarships Shown</div>

                    <div class="display-6 fw-bold" style="color:var(--brand-blue)">
                        {{ number_format($scholarships->count()) }}
                    </div>
                    
                    <div class="muted">Latest scholarship entries</div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card stat-card shadow-sm">
                <div class="card-body">
                    <div class="muted">Latest Announcements</div>
                    
                    <div class="display-6 fw-bold" style="color:var(--brand-blue)">
                        {{ number_format($announcements->count()) }}
                    </div>

                    <div class="muted">Most recent posts</div>
                </div>
            </div>
        </div>
    </div>

    <!-- ✅ Scholarships + Announcements -->
    <div class="row g-3">
        <!-- Scholarships -->
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0 section-title">Scholarships</h5>
                    <small class="muted">Latest scholarship information</small>
                </div>

                <div class="card-body">
                    @if($scholarships->isEmpty())
                        <div class="alert alert-light border mb-0">
                            No scholarships found yet.
                        </div>
                    @else
                        <div class="list-group">
                            @foreach($scholarships as $s)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-start gap-2">
                                        <div class="fw-bold">
                                            {{ $s->scholarship_name ?? '—' }}
                                        </div>

                                        <span class="badge badge-soft">
                                            {{ $s->status ?? '—' }}
                                        </span>
                                    </div>

                                    @if(!empty($s->benefactor))
                                        <div class="mt-1 muted">
                                            Benefactor: <span class="fw-semibold">{{ $s->benefactor }}</span>
                                        </div>
                                    @endif

                                    @if(!empty($s->description))
                                        <div class="mt-2">
                                            {{ \Illuminate\Support\Str::limit($s->description, 160) }}
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Announcements -->
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0 section-title">Announcements</h5>
                    <small class="muted">Latest updates from Scholarship Office</small>
                </div>

                <div class="card-body">
                    @if($announcements->isEmpty())
                        <div class="alert alert-light border mb-0">
                            No announcements posted yet.
                        </div>
                    @else
                        <div class="list-group">
                            @foreach($announcements as $a)
                                @php
                                    $date = $a->posted_at ?? $a->created_at;
                                @endphp

                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-start gap-2">
                                        <div class="fw-bold">
                                            {{ $a->title ?? 'Announcement' }}
                                        </div>
                                        <small class="muted text-nowrap">
                                            {{ \Carbon\Carbon::parse($date)->format('M d, Y') }}
                                        </small>
                                    </div>

                                    @if(!empty($a->description))
                                        <div class="mt-2">
                                            {{ \Illuminate\Support\Str::limit($a->description, 180) }}
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

</div>

<!-- ✅ Footer (auto year) -->
<footer class="py-3 mt-4">
    <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center">
        <div class="fw-semibold">BISU UNIVERSITY CANDIJAY</div>
        <div>© {{ now()->year }}</div>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>

</body>
</html>
