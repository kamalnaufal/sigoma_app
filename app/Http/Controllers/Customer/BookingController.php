<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Venue;
use App\Models\Booking;
use App\Models\WaitingList;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /**
     * Menampilkan halaman booking dengan jadwal ketersediaan.
     */
    public function index(Request $request)
    {
        // Ambil tanggal dari request, jika tidak ada, gunakan tanggal hari ini
        $selectedDate = $request->input('date', Carbon::today()->toDateString());

        // Ambil semua data lapangan
        $venues = Venue::all();

        // Ambil data booking pada tanggal yang dipilih
        // dan muat data antrean (waiting lists) beserta user yang ada di dalamnya
        $bookings = Booking::where('booking_date', $selectedDate)
                           ->with(['waitingLists.user'])
                           ->get();

        // Jam operasional (misal: dari jam 8 pagi sampai 10 malam)
        $operatingHours = range(8, 22);

        return view('pages.customer.booking.index', compact('venues', 'bookings', 'selectedDate', 'operatingHours'));
    }

    /**
     * Menyimpan data booking baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'venue_id' => 'required|exists:venues,id',
            'booking_date' => 'required|date',
            'start_time' => 'required',
        ]);

        // [VALIDASI BARU] Cek apakah waktu yang diminta sudah berlalu.
        $requestedBookingTime = Carbon::parse($request->booking_date . ' ' . $request->start_time);
        if ($requestedBookingTime->isPast()) {
            return back()->with('error', 'Anda tidak bisa booking waktu yang sudah berlalu.');
        }

        // Cek lagi untuk mencegah double booking jika ada yang booking di waktu bersamaan
        $isAlreadyBooked = Booking::where('venue_id', $request->venue_id)
            ->where('booking_date', $request->booking_date)
            ->where('start_time', $request->start_time)
            ->exists();

        if ($isAlreadyBooked) {
            return back()->with('error', 'Maaf, jadwal ini sudah dibooking oleh orang lain.');
        }

        $venue = Venue::findOrFail($request->venue_id);
        $startTime = Carbon::parse($request->start_time);

        Booking::create([
            'user_id' => Auth::id(),
            'venue_id' => $venue->id,
            'booking_date' => $request->booking_date,
            'start_time' => $startTime->format('H:i:s'),
            'end_time' => $startTime->copy()->addHour()->format('H:i:s'),
            'total_price' => $venue->price_per_hour,
            'status' => 'confirmed',
        ]);

        return redirect()->route('booking.index', ['date' => $request->booking_date])
                         ->with('success', 'Booking berhasil dibuat!');
    }

    /**
     * Menambahkan user ke dalam daftar tunggu (waiting list).
     */
    public function joinWaitingList(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
        ]);

        // [VALIDASI BARU] Cek apakah jadwal yang diantrekan sudah berlalu.
        $booking = Booking::findOrFail($request->booking_id);
        $bookingTime = Carbon::parse($booking->booking_date . ' ' . $booking->start_time);

        if ($bookingTime->isPast()) {
            return back()->with('error', 'Anda tidak bisa mengantre untuk jadwal yang sudah berlalu.');
        }

        // Cek agar user tidak mengantre dua kali untuk slot yang sama
        $isAlreadyWaiting = WaitingList::where('user_id', Auth::id())
                                         ->where('booking_id', $request->booking_id)
                                         ->exists();

        if ($isAlreadyWaiting) {
            return back()->with('error', 'Anda sudah berada di dalam antrean untuk jadwal ini.');
        }

        WaitingList::create([
            'user_id' => Auth::id(),
            'booking_id' => $request->booking_id,
        ]);

        return back()->with('success', 'Anda berhasil masuk ke dalam antrean!');
    }


    /**
     * Menampilkan riwayat booking milik user yang sedang login.
     */
    public function myBookings()
    {
        $myBookings = Booking::where('user_id', Auth::id())
                               ->with('venue')
                               ->orderBy('booking_date', 'desc')
                               ->orderBy('start_time', 'asc')
                               ->paginate(10);

        return view('pages.customer.booking.my_bookings', compact('myBookings'));
    }

    /**
     * Membatalkan booking dan menerapkan logika FIFO untuk waiting list.
     */
    public function cancel(Booking $booking)
    {
        // Pastikan hanya pemilik booking yang bisa membatalkan
        if ($booking->user_id !== Auth::id()) {
            return back()->with('error', 'Anda tidak berhak membatalkan booking ini.');
        }

        // Ambil orang pertama di antrean (FIFO)
        $firstInLine = $booking->waitingLists()->orderBy('created_at', 'asc')->first();

        // Jika ada orang di antrean
        if ($firstInLine) {
            // Buat booking baru untuk user yang mengantre
            Booking::create([
                'user_id' => $firstInLine->user_id,
                'venue_id' => $booking->venue_id,
                'booking_date' => $booking->booking_date,
                'start_time' => $booking->start_time,
                'end_time' => $booking->end_time,
                'total_price' => $booking->total_price,
                'status' => 'confirmed',
            ]);

            // Hapus user tersebut dari antrean
            $firstInLine->delete();
        }

        // Hapus booking lama
        $booking->delete();

        return back()->with('success', 'Booking berhasil dibatalkan.');
    }
}
