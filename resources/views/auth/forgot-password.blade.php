<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Forgot Password - Scholarship Information Management System</title>

    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">

    <style>
        body {
            background: #f0f4f8;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }
        .card-wrap{
            background:#fff;
            border-radius:10px;
            padding:2rem;
            box-shadow:0 4px 8px rgba(0,0,0,.1);
            max-width:450px;
            width:100%;
        }
        .title {
            font-weight: 800;
            color: #003366;
            margin-bottom: .25rem;
        }
        .required-star {
            color: #dc3545;
            font-weight: 700;
            margin-left: 2px;
        }
        .btn-primary {
            background-color: #003366;
            border-color: #235182;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="card-wrap">
        <h4 class="title">Forgot Password</h4>
        <p class="text-muted mb-3" style="font-size:.95rem;">
            Enter your email/username and we will send a password reset link.
        </p>

        <x-auth-session-status class="mb-3" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">
                    Email / Username <span class="required-star">*</span>
                </label>
                <input id="email"
                       class="form-control"
                       type="email"
                       name="email"
                       value="{{ old('email') }}"
                       required
                       autofocus>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="d-grid mb-2">
                <button type="submit" class="btn btn-primary">
                    Email Password Reset Link
                </button>
            </div>

            <div class="text-center">
                <a href="{{ route('login') }}" class="text-decoration-none" style="font-size:.9rem;">
                    Back to Login
                </a>
            </div>
        </form>
    </div>

    <script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
