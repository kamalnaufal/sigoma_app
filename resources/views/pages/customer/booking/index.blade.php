<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Booking Lapangan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="container">
            {{-- Menampilkan notifikasi sukses atau error --}}
            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <h3 class="card-title mb-4">Pilih Tanggal Booking</h3>

                    <!-- Form Pemilih Tanggal -->
                    <form action="{{ route('booking.index') }}" method="GET" class="mb-4">
                        <div class="input-group" style="max-width: 300px;">
                            {{-- [MODIFIKASI] Tambahkan atribut 'min' untuk mencegah memilih tanggal lampau --}}
                            <input type="date" name="date" class="form-control" value="{{ $selectedDate }}" min="{{ \Carbon\Carbon::today()->toDateString() }}" onchange="this.form.submit()">
                        </div>
                    </form>

                    <!-- Tabel Jadwal -->
                    <div class="table-responsive">
                        <table class="table table-bordered text-center">
                            <thead>
                                <tr>
                                    <th style="width: 15%; vertical-align: middle;">Lapangan</th>
                                    @foreach ($operatingHours as $hour)
                                        <th>{{ sprintf('%02d:00', $hour) }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                {{-- [BARU] Ambil waktu sekarang sekali saja untuk efisiensi --}}
                                @php $now = \Carbon\Carbon::now(); @endphp

                                @forelse ($venues as $venue)
                                    <tr>
                                        <td class="fw-bold" style="vertical-align: middle;">{{ $venue->name }}</td>
                                        @foreach ($operatingHours as $hour)
                                            <td>
                                                @php
                                                    // Membuat objek DateTime lengkap untuk slot ini
                                                    $slotDateTime = \Carbon\Carbon::parse($selectedDate . ' ' . $hour . ':00');
                                                @endphp

                                                {{-- [LOGIKA BARU] Cek apakah slot waktu sudah terlewat --}}
                                                @if ($now->gt($slotDateTime))
                                                    <span class="badge bg-secondary">Lewat</span>
                                                @else
                                                    {{-- Jika waktu belum lewat, jalankan logika booking seperti biasa --}}
                                                    @php
                                                        $startTime = sprintf('%02d:00:00', $hour);
                                                        // Cari booking untuk slot ini
                                                        $booking = $bookings->first(function($b) use ($venue, $startTime) {
                                                            return $b->venue_id == $venue->id && $b->start_time == $startTime;
                                                        });
                                                    @endphp

                                                    @if ($booking)
                                                        {{-- JIKA SLOT SUDAH DI-BOOKING --}}
                                                        @php
                                                            $isOnWaitingList = $booking->waitingLists->contains('user_id', auth()->id());
                                                        @endphp

                                                        @if ($isOnWaitingList)
                                                            <span class="badge bg-info">Anda di Antrean</span>
                                                        @else
                                                            {{-- Form untuk masuk antrean --}}
                                                            <form action="{{ route('booking.joinWaitingList') }}" method="POST" onsubmit="return confirm('Jadwal ini sudah penuh. Masuk ke daftar tunggu?');">
                                                                @csrf
                                                                <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                                                                <button type="submit" class="btn btn-sm btn-warning">
                                                                    Antre ({{ $booking->waitingLists->count() }})
                                                                </button>
                                                            </form>
                                                        @endif
                                                    @else
                                                        {{-- JIKA SLOT TERSEDIA --}}
                                                        <form action="{{ route('booking.store') }}" method="POST" onsubmit="return confirm('Anda yakin ingin booking jadwal ini?');">
                                                            @csrf
                                                            <input type="hidden" name="venue_id" value="{{ $venue->id }}">
                                                            <input type="hidden" name="booking_date" value="{{ $selectedDate }}">
                                                            <input type="hidden" name="start_time" value="{{ sprintf('%02d:00', $hour) }}">
                                                            <button type="submit" class="btn btn-sm btn-success">Booking</button>
                                                        </form>
                                                    @endif
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ count($operatingHours) + 1 }}" class="text-center">
                                            Belum ada data lapangan. Silakan hubungi admin.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
