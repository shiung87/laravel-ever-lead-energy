<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login | Everlead Energy</title>
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>
<body class="bg-dark-surface d-flex align-items-center min-vh-100">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="el-card p-4 p-lg-5">
                <div class="text-center mb-4">
                    <span class="bg-warning text-dark rounded-3 px-3 py-2 fw-bold d-inline-block mb-3"><i class="fa-solid fa-bolt"></i></span>
                    <h4 class="fw-bold text-white mb-1">Everlead Admin Portal</h4>
                    <p class="small text-secondary mb-0">Sign in to manage leads, dealers & catalog</p>
                </div>

                <x-alerts />

                <form method="POST" action="{{ route('login.attempt') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small text-light fw-semibold">Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control el-form-control" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-light fw-semibold">Password</label>
                        <input type="password" name="password" class="form-control el-form-control" required>
                    </div>
                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label small text-secondary" for="remember">Keep me signed in</label>
                    </div>
                    <button type="submit" class="btn btn-amber w-100 py-2">Sign In</button>
                </form>

                <div class="text-center mt-4">
                    <a href="{{ route('home') }}" class="small text-secondary text-decoration-none">
                        <i class="fa-solid fa-arrow-left me-1"></i>Back to Public Website
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
