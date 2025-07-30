<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Booking; // Import model Booking

class BookingConfirmed extends Notification
{
    use Queueable;

    protected $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    public function via(object $notifiable): array
    {
        // Kita akan mengirim notifikasi ini ke database
        return ['database'];
    }

    // Method ini menentukan data apa yang akan disimpan di database
    public function toDatabase(object $notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'venue_name' => $this->booking->venue->name,
            'booking_date' => $this->booking->booking_date,
            'message' => "Booking Anda untuk lapangan {$this->booking->venue->name} telah dikonfirmasi!"
        ];
    }
}
