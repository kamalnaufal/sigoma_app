<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sigoma - Sistem Informasi GOR Desa Majalaya</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <style>
        body {
            font-family: 'Instrument Sans', sans-serif;
        }
        .hero-section {
            position: relative;
            background-color: #212529; /* Warna fallback jika gambar gagal dimuat */
            z-index: 1;
        }
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('https://i.pinimg.com/736x/26/8c/ea/268cea9201b1ca8672d274f6e0d1125d.jpg') no-repeat center center;
            background-size: cover;
            opacity: 0.3;
            z-index: -1;
        }
    </style>
</head>
<body class="bg-light">

    <header class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bolder fs-4 text-primary" href="#">SIGOMA</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="#hero">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="#fasilitas">Fasilitas</a></li>
                    <li class="nav-item"><a class="nav-link" href="/dashboard">Jadwal</a></li>
                    <li class="nav-item"><a class="nav-link" href="#kontak">Kontak</a></li>
                </ul>
                <div class="d-flex align-items-center">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-primary">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-secondary me-2">
                            Log in
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-primary">
                                Register
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <main>
        <section id="hero" class="hero-section text-white text-center py-5">
            <div class="container py-5 my-5">
                <h1 class="display-3 fw-bolder">GOR Desa Majalaya</h1>
                <p class="lead col-lg-8 mx-auto text-white">
                    Pesan lapangan futsal dan badminton favoritmu dengan mudah dan cepat. Cek jadwal, pilih waktu, dan mainkan!
                </p>
                <a href="/dashboard" class="btn btn-primary btn-lg mt-4">
                    Pesan Lapangan Sekarang
                </a>
            </div>
        </section>

        <section id="fasilitas" class="py-5">
    <div class="container text-center">
        <h2 class="fw-bold display-5 mb-3">Fasilitas Kami</h2>
        <p class="text-muted mb-5">Pilih lapangan sesuai kebutuhan olahragamu.</p>

        <div class="row g-4 justify-content-center">

            {{-- Loop melalui setiap data lapangan yang dikirim dari controller --}}
            @forelse ($venues as $venue)
                <div class="col-md-6 col-lg-5">
                    <div class="card h-100 shadow-sm border-0">

                        {{-- Tampilkan FOTO dari storage --}}
                        <img src="{{ $venue->photo ? Storage::url($venue->photo) : 'https://placehold.co/600x400?text=No+Image' }}"
                             class="card-img-top"
                             alt="{{ $venue->name }}"
                             style="aspect-ratio: 16/9; object-fit: cover;">

                        <div class="card-body p-4">
                            {{-- Tampilkan NAMA lapangan --}}
                            <h3 class="card-title fw-bold">{{ $venue->name }}</h3>

                            {{-- Tampilkan DESKRIPSI (jika ada) --}}
                            {{-- Pastikan Anda punya kolom 'description' di tabel venues --}}
                            <p class="card-text text-muted">{{ $venue->description ?? 'Deskripsi belum tersedia.' }}</p>

                            <a href="#" class="btn btn-outline-primary mt-3">Lihat Detail</a>
                        </div>
                    </div>
                </div>
            @empty
                {{-- Tampilkan pesan ini jika tidak ada data lapangan sama sekali --}}
                <div class="col-12">
                    <p class="text-center text-muted">Saat ini belum ada fasilitas yang tersedia.</p>
                </div>
            @endforelse

        </div>
    </div>
</section>
        <section id="jadwal-cta" class="py-5 bg-white">
            <div class="container text-center">
                 <h2 class="fw-bold display-5 mb-3">Siap untuk Berolahraga?</h2>
                 <p class="lead text-muted col-lg-8 mx-auto mb-4">Cek jadwal ketersediaan lapangan secara real-time dan lakukan pemesanan sekarang juga.</p>
                 <a href="/dashboard" class="btn btn-success btn-lg">Lihat Jadwal Lengkap</a>
            </div>
        </section>
    </main>

    <footer id="kontak" class="bg-dark text-white pt-5 pb-4">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4 mb-3 mb-md-0">
                    <h3 class="fw-bold fs-4 mb-2">Sigoma</h3>
                    <p class="text-white-50">Sistem informasi dan pemesanan lapangan di GOR Desa Majalaya. Mudah, cepat, dan terpercaya.</p>
                </div>
                <div class="col-md-4 mb-3 mb-md-0">
                     <h3 class="fw-bold fs-4 mb-2">Tautan Cepat</h3>
                     <ul class="list-unstyled">
                        <li><a href="#fasilitas" class="text-white-50 text-decoration-none hover-primary">Fasilitas</a></li>
                        <li><a href="/dashboard" class="text-white-50 text-decoration-none hover-primary">Jadwal</a></li>
                        <li><a href="{{ route('login') }}" class="text-white-50 text-decoration-none hover-primary">Login</a></li>
                     </ul>
                </div>
                <div class="col-md-4">
                    <h3 class="fw-bold fs-4 mb-2">Hubungi Kami</h3>
                     <p class="text-white-50 mb-1"><i class="bi bi-geo-alt-fill me-2"></i>Jl. Raya Majalaya No. 123, Bandung</p>
                     <p class="text-white-50 mb-1"><i class="bi bi-envelope-fill me-2"></i>kontak@sigoma.id</p>
                     <p class="text-white-50 mb-1"><i class="bi bi-telephone-fill me-2"></i>(022) 123-4567</p>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center text-white-50">
                &copy; {{ date('Y') }} Sigoma. All Rights Reserved.
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
