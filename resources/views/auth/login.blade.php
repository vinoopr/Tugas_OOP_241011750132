<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - MyProfile</title>
    <link rel="stylesheet" href="{{ asset('bootstrap-5.3.8-dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body class="login-page d-flex align-items-center justify-content-center p-3">
    {{-- BACKGROUND LOGIN: public/images/branding/bg.jpeg, diatur di public/css/admin.css. --}}
    <main class="card login-card shadow-lg">
        <header class="login-card-header"><h1 class="h4 mb-0">Admin Login</h1></header>
        <div class="login-card-body">
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email admin</label>
                <input id="email" type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" autocomplete="username" required autofocus>
                @error('email')<div class="invalid-feedback" role="alert">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" autocomplete="current-password" required>
                @error('password')<div class="invalid-feedback" role="alert">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
        <div class="login-card-footer text-muted">
            <p class="mb-2">Gunakan akun admin yang sudah dibuat.</p>
            <a href="{{ url('/') }}" class="text-secondary">Kembali ke portofolio</a>
        </div>
        </div>
    </main>
</body>
</html>
