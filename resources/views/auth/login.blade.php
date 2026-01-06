

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Scholarship Information Management System - Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom Blue Theme Styles -->
    <style>
        body {
            background: #f0f4f8; /* Light blue background */
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }
        .login-container {
            background: #ffffff; /* White background for readability */
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
            margin-bottom: 0.5rem; /* Reduced margin to accommodate "Student Login" */
        }
        .student-login {
            font-size: 1.2rem;
            font-weight: 600;
            color: #007bff; /* Blue to match theme */
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
        .text-danger {
            color: #dc3545 !important; /* Ensure error text is red */
        }
        .logo-style {
            max-width: 100px; /* Adjust size as needed */
            height: auto; /* Maintain aspect ratio */
            border-radius: 50px; /* Rounded corners */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Subtle shadow */
            border: 2px solid #007bff; /* Blue border */
        }
        .university-name {
            font-size: 0.9rem;
            color: #6c757d; /* Muted gray for subtlety */
            text-align: center;
            margin-top: 1rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <img src="{{ asset('images/scholarship_logo.jpg') }}" alt="Scholarship System Logo" class="logo-style mb-3 d-block mx-auto">
        <!-- System Name -->
        <div class="system-name">
            Scholarship Information Management System
        </div>
        <!-- Student Login Heading -->
        <div class="student-login">
            Student Login:
        </div>

        <!-- Session Status (from Breeze, kept for success messages) -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Login Form -->
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- BISU Email (Username) -->
            <div class="mb-3">
                <label for="email" class="form-label">USERNAME  </label>
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Student ID (Password) -->
            <div class="mb-3">
                <label for="password" class="form-label">PASSWORD</label>
                <input id="password" type="password" class="form-control" name="password" required autocomplete="current-password">
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Submit Button -->
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">
                    {{ __('LOGIN') }}
                </button>
            </div>
        </form>

        <!-- University Name -->
        <div class="university-name">
            Bohol Island State University - Candijay Campus
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>