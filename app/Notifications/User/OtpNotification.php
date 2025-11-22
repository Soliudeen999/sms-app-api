<?php

declare(strict_types=1);

namespace App\Notifications\User;

use App\Enums\Otp\OtpChannel;
use App\Models\Otp;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OtpNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Otp $otp)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $channel = match ($this->otp->channel) {
            OtpChannel::MAIL => ['mail'],
            // OtpChannel::SMS => ['sms'], // To be implemented later
            OtpChannel::IN_APP => ['in_app'],
            default => ['mail'],
        };

        return $channel;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->line('Otp: ' . $this->otp->code)
            ->line('Expiring in: ' . $this->otp->expires_at?->diffForHumans() ?? 0 . ' minutes')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'OTP Notification',
            'message' => 'Your OTP code is ' . $this->otp->code . ' expiring in ' . $this->otp->expires_at->diffForHumans() . ' minutes.',
        ];
    }
}
