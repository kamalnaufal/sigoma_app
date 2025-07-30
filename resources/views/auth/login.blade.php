<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - {{ config('app.name', 'Laravel') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* Mengatur background agar full screen dan terpusat */
        .bg-container {
            min-height: 100vh;
            background-image: url('https://i.pinimg.com/736x/26/8c/ea/268cea9201b1ca8672d274f6e0d1125d.jpg');
            background-size: cover;
            background-position: center;
        }

        /* Membuat input hanya memiliki garis bawah, mirip desain asli */
        .form-control-underline {
            border: none;
            border-bottom: 2px solid #ced4da; /* Warna border abu-abu Bootstrap */
            border-radius: 0;
            padding-left: 0.25rem;
            padding-right: 0.25rem;
        }

        .form-control-underline:focus {
            box-shadow: none;
            border-color: #84cc16; /* Warna hijau limau saat focus */
        }
    </style>
</head>

<body class="antialiased">
    <div class="bg-container d-flex align-items-center justify-content-center p-4">

        {{-- Kotak Login --}}
        <div class="card shadow-lg" style="max-width: 24rem; width: 100%;">
            <div class="card-body p-5">

                <div class="text-center">
                    <h2 class="fw-bold text-primary">Login</h2>
                </div>

                {{-- Tombol Kembali ke Beranda --}}
                <a href="{{ url('/') }}" class="btn btn-outline-secondary w-100 mt-4">
                    ← Kembali ke Beranda
                </a>

                {{-- Menampilkan status sesi (jika ada) --}}
                <x-auth-session-status class="my-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="mt-4">
                    @csrf

                    {{-- Input Email --}}
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input id="email"
                               class="form-control form-control-underline"
                               type="email"
                               name="email"
                               value="{{ old('email') }}"
                               required
                               autofocus>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    {{-- Input Password --}}
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input id="password"
                               class="form-control form-control-underline"
                               type="password"
                               name="password"
                               required>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />

                    </div>

                    {{-- Link Lupa Sandi --}}
                    <div class="text-start small mt-3">
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-muted text-decoration-none hover-link">
                                Lupa Sandi?
                            </a>
                        @endif
                    </div>

                    {{-- Tombol Login --}}
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary w-100 rounded-pill shadow-sm">
                            Login
                        </button>
                    </div>
                </form>

                <p class="mt-4 text-center text-muted small">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="fw-bold text-primary text-decoration-none">
                        Daftar
                    </a>
                </p>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
