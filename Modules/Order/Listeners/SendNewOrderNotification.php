<?php

namespace Modules\Order\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;
use Modules\Order\Notifications\CancelOrderNotification;
use Modules\Order\Notifications\NewOrderNotification;

class SendNewOrderNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)

    {

        $admin = User::whereHas('roles', function ($query) {

            $query->where('name', '=', 'admin');

        })->get();

        Notification::send($admin, new NewOrderNotification($event->order));
        Notification::send($admin, new CancelOrderNotification($event->order));

    }
}
