<?php

namespace Modules\Order\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Modules\Order\Entities\Order;


class TaskReminderNotification extends Notification
{

    use Queueable;


    /**
     * Create a new notification instance.
     *
     * @param $order
     */
    public function __construct($order)
    {
        $this->order = $order;
    }


    public function via($notifiable): array
    {

        return ['mail'];
    }

    public function toMail($notifiable)
    {

        return (new MailMessage)
            ->line('You have a task reminder for order #' . $this->order['id'] . '!')
            ->line('Thank you for using our application!');
    }

}
