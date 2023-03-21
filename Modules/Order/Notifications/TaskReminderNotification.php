<?php

namespace Modules\Order\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;


class TaskReminderNotification extends Notification
{
    public function via($notifiable)
    {
        if ($notifiable === null) {
            return [];
        }
        return ['mail'];
    }

    public function toMail($notifiable)
    {

        $schedule = $notifiable->newschedule ?? null;
        if ($schedule) {
            return (new MailMessage)
                ->subject('Reminder: Schedule ' . $schedule->order_id . ' starts tomorrow')
                ->line('Your schedule ' . $schedule->order_id . ' is starting tomorrow, on ' . $schedule->date->format('Y-m-d') . '.')
                ->action('View Schedule', url('/schedules/' . $schedule->id))
                ->line('Thank you for using our application!');
        }
    }

}
