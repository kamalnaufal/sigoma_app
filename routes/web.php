<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VenueController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Customer\BookingController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Models\Venue;

Route::get('/', function () {
    // Ambil semua data lapangan dari database
    $venues = Venue::latest()->get();

    // Kirim data ke view 'welcome'
    return view('welcome', ['venues' => $venues]);
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // RUTE UNTUK CUSTOMER
    Route::get('/booking', [BookingController::class, 'index'])->name('booking.index');
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/my-bookings', [BookingController::class, 'myBookings'])->name('booking.my');
   Route::post('/booking/waiting-list', [BookingController::class, 'joinWaitingList'])->name('booking.joinWaitingList');
    Route::delete('/my-bookings/{booking}', [BookingController::class, 'cancel'])->name('booking.cancel');
});

// GRUP ROUTE UNTUK ADMIN PANEL
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('venues', VenueController::class);
    Route::resource('users', UserController::class);
    Route::get('bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
    Route::delete('bookings/{booking}', [AdminBookingController::class, 'destroy'])->name('bookings.destroy');
});
require __DIR__.'/auth.php';
