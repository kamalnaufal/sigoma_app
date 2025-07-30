<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Venue;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalCustomers = User::where('role', 'customer')->count();
        $totalVenues = Venue::count();
        $totalBookingsThisMonth = Booking::whereYear('created_at', Carbon::now()->year)
                                         ->whereMonth('created_at', Carbon::now()->month)
                                         ->count();

        // Data untuk Chart.js (7 hari terakhir)
        $startDate = Carbon::today()->subDays(6);
        $endDate = Carbon::today();

        // [REVISED] Logika yang lebih tangguh untuk mengambil data chart
        // 1. Ambil semua booking dalam rentang tanggal
        $bookings = Booking::whereBetween('booking_date', [$startDate, $endDate])->get();

        // 2. Inisialisasi data chart dengan 0 untuk setiap hari
        $chartDataArray = [];
        for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
            $chartDataArray[$date->toDateString()] = 0;
        }

        // 3. Loop melalui booking dan hitung jumlahnya per hari
        foreach ($bookings as $booking) {
            $bookingDate = Carbon::parse($booking->booking_date)->toDateString();
            if (isset($chartDataArray[$bookingDate])) {
                $chartDataArray[$bookingDate]++;
            }
        }

        // 4. Siapkan data akhir untuk dikirim ke view
        $chartData = [
            'labels' => array_map(function($date) {
                return Carbon::parse($date)->format('d M');
            }, array_keys($chartDataArray)),
            'data' => array_values($chartDataArray),
        ];

        return view('pages.admin.dashboard', compact('totalCustomers', 'totalVenues', 'totalBookingsThisMonth', 'chartData'));
    }
}
