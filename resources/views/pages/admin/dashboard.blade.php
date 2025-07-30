<x-app-layout>
    {{-- [BARU] Menambahkan CDN untuk Bootstrap Icons --}}
    @push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        /* [BARU] Style kustom untuk kartu statistik dan chart */
        .stat-card {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }
        .icon-circle {
            width: 60px;
            height: 60px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }
        #bookingChartContainer {
            position: relative;
            height: 350px;
        }
    </style>
    @endpush

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="container">
            <!-- [MODIFIKASI] Kartu Statistik dengan Desain Baru -->
            <div class="row g-4 mb-5">
                {{-- Total Customer --}}
                <div class="col-lg-4 col-md-6">
                    <div class="card stat-card border-0 shadow-sm rounded-3 h-100">
                        <div class="card-body d-flex align-items-center p-4">
                            <div class="icon-circle bg-primary-subtle me-4">
                                <i class="bi bi-people-fill fs-2 text-primary"></i>
                            </div>
                            <div>
                                <p class="card-text text-muted mb-1">Total Customer</p>
                                <h3 class="card-title fw-bold mb-0">{{ $totalCustomers }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Total Lapangan --}}
                <div class="col-lg-4 col-md-6">
                    <div class="card stat-card border-0 shadow-sm rounded-3 h-100">
                        <div class="card-body d-flex align-items-center p-4">
                            <div class="icon-circle bg-success-subtle me-4">
                                <i class="bi bi-grid-1x2-fill fs-2 text-success"></i>
                            </div>
                            <div>
                                <p class="card-text text-muted mb-1">Total Lapangan</p>
                                <h3 class="card-title fw-bold mb-0">{{ $totalVenues }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Booking Bulan Ini --}}
                <div class="col-lg-4 col-md-6">
                    <div class="card stat-card border-0 shadow-sm rounded-3 h-100">
                        <div class="card-body d-flex align-items-center p-4">
                            <div class="icon-circle bg-info-subtle me-4">
                                <i class="bi bi-calendar-check-fill fs-2 text-info"></i>
                            </div>
                            <div>
                                <p class="card-text text-muted mb-1">Booking Bulan Ini</p>
                                <h3 class="card-title fw-bold mb-0">{{ $totalBookingsThisMonth }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- [MODIFIKASI] Diagram Booking dengan Desain Baru -->
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <h5 class="card-title fw-bold mb-3">Jumlah Booking (7 Hari Terakhir)</h5>
                    <div id="bookingChartContainer">
                        <canvas id="bookingChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <!-- Import Chart.js dari CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('bookingChart').getContext('2d');
            const chartData = @json($chartData);

            // [BARU] Membuat gradasi warna untuk background chart
            const gradient = ctx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(54, 162, 235, 0.5)');
            gradient.addColorStop(1, 'rgba(54, 162, 235, 0)');

            new Chart(ctx, {
                // [MODIFIKASI] Tipe chart diubah menjadi 'line' untuk tampilan lebih modern
                type: 'line',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        label: 'Jumlah Booking',
                        data: chartData.data,
                        backgroundColor: gradient, // Menggunakan gradasi
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 2,
                        fill: true, // Mengisi area di bawah garis
                        tension: 0.4, // Membuat garis lebih melengkung (smooth)
                        pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                // Memastikan angka di sumbu Y adalah bilangan bulat
                                precision: 0
                            },
                            grid: {
                                // Membuat garis grid lebih samar
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false // Menghilangkan garis grid vertikal
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false // Menyembunyikan legenda karena sudah jelas dari judul
                        },
                        tooltip: {
                            backgroundColor: '#333',
                            titleFont: { size: 14 },
                            bodyFont: { size: 12 },
                            padding: 10,
                            cornerRadius: 4,
                            displayColors: false
                        }
                    }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
