<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentConfirmedMail extends Notification
{
    use Queueable;

    protected $appointment;
    /**
     * Create a new notification instance.
     */
    public function __construct($appointment)
    {
        $this->appointment = $appointment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $doctorName = $this->appointment->doctor->user->name ?? 'Bác sĩ';
        $date = \Carbon\Carbon::parse($this->appointment->appointment_date)->format('d/m/Y');
        $startTime = $this->appointment->schedule->schedule->start_time ?? '';
        $endTime = $this->appointment->schedule->schedule->end_time ?? '';

        return (new MailMessage)
            ->subject('Xác nhận lịch hẹn khám bệnh của bạn')
            ->greeting('Xin chào ' . $notifiable->name . ',')
            ->line('Lịch hẹn của bạn đã được xác nhận thành công ✅')
            ->line('👨‍⚕️ Bác sĩ: ' . $doctorName)
            ->line('📅 Ngày khám: ' . $date)
            ->line('🕒 Thời gian: ' . $startTime . ' - ' . $endTime)
            ->action('Xem chi tiết lịch hẹn', url('/client/hospital/appointments/' . $this->appointment->id))
            ->line('Cảm ơn bạn đã tin tưởng sử dụng dịch vụ của bệnh viện chúng tôi.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
