<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Jadwal Saya') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="container">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title mb-4">Riwayat Booking Anda</h3>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Lapangan</th>
                                    <th>Tanggal</th>
                                    <th>Jam</th>
                                    <th>Status</th>
                                    <th>Total Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Gunakan @forelse untuk menangani kasus jika data kosong --}}
                                @forelse ($myBookings as $booking)
                                    <tr>
                                        {{-- Gunakan $loop->iteration untuk nomor urut paginasi --}}
                                        <td>{{ $myBookings->firstItem() + $loop->index }}</td>
                                        {{-- Akses nama venue melalui relasi yang sudah kita buat --}}
                                        <td>{{ $booking->venue->name }}</td>
                                        {{-- Format tanggal agar lebih mudah dibaca --}}
                                        <td>{{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('d F Y') }}</td>
                                        {{-- Format jam --}}
                                        <td>{{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }}</td>
                                        <td><span class="badge bg-success text-capitalize">{{ $booking->status }}</span></td>
                                        {{-- Format harga --}}
                                        <td>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Anda belum memiliki riwayat booking.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        {{-- Menampilkan link untuk paginasi --}}
                        <div class="mt-3">
                            {{ $myBookings->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
