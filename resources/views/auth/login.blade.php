<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Scholarship Information Management System - Login</title>

    <!-- Bootstrap CSS (local) -->
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
        .login-container {
            background: #ffffff;
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }
        .system-name {
            font-size: 1.5rem;
            font-weight: bold;
            color: #003366;
            text-align: center;
            margin-bottom: 0.5rem;
        }
        .student-login {
            font-size: 1.2rem;
            font-weight: 600;
            color: #007bff;
            text-align: center;
            margin-bottom: 1rem;
        }
        .btn-primary {
            background-color: #003366;
            border-color: #235182;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
        .text-danger { color: #dc3545 !important; }
        .logo-style {
            max-width: 100px;
            height: auto;
            border-radius: 50px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border: 2px solid #007bff;
        }
        .university-name {
            font-size: 0.9rem;
            color: #6c757d;
            text-align: center;
            margin-top: 1rem;
        }
        .required-star {
            color: #dc3545;
            font-weight: 700;
            margin-left: 2px;
        }
        .forgot-link {
            font-size: .9rem;
            text-decoration: none;
        }
        .forgot-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <img src="{{ asset('images/scholarship_logo.jpg') }}" alt="Scholarship System Logo" class="logo-style mb-3 d-block mx-auto">

        <div class="system-name">Scholarship Information Management System</div>

        <div class="student-login">Login:</div>

       

        <!-- Session Status -->
        <x-auth-session-status class="mb-3" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Username -->
            <div class="mb-3">
                <label for="email" class="form-label">
                    USERNAME <span class="required-star">*</span>
                </label>
                <input id="email"
                       type="email"
                       class="form-control"
                       name="email"
                       value="{{ old('email') }}"
                       required
                       autofocus
                       autocomplete="username">
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mb-2">
                <label for="password" class="form-label">
                    PASSWORD <span class="required-star">*</span>
                </label>
                <input id="password"
                       type="password"
                       class="form-control"
                       name="password"
                       required
                       autocomplete="current-password">
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Forgot Password -->
            <div class="d-flex justify-content-end mb-3">
                @if (Route::has('password.request'))
                    <a class="forgot-link" href="{{ route('password.request') }}">
                        Forgot password?
                    </a>
                @endif
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary">
                    LOGIN
                </button>
            </div>
        </form>

        <div class="university-name">
            Bohol Island State University - Candijay Campus
        </div>
    </div>

    <script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
