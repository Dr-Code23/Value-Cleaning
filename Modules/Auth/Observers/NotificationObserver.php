<?php

namespace Modules\Auth\Observers;


use Modules\Auth\Entities\Notification;
use Modules\Auth\Traits;
class NotificationObserver
{
    use PushNotificationTrait;
    /**
     * Handle the NotificationO "created" event.
     *
     * @param  \App\Models\NotificationO  $notificationO
     * @return void
     */
    public function created(Notification $notification)
    {
      return $this->Notification($notification);
    }

    /**
     * Handle the NotificationO "updated" event.
     *
     * @param  \App\Models\NotificationO  $notificationO
     * @return void
     */
    public function updated(Notification $notificationO)
    {
        //
    }

    /**
     * Handle the NotificationO "deleted" event.
     *
     * @param  \App\Models\Notification  $notificationO
     * @return void
     */
    public function deleted(Notification $notificationO)
    {
        //
    }

    /**
     * Handle the NotificationO "restored" event.
     *
     * @param  \App\Models\NotificationO  $notificationO
     * @return void
     */
    public function restored(NotificationO $notificationO)
    {
        //
    }

    /**
     * Handle the NotificationO "force deleted" event.
     *
     * @param  \App\Models\NotificationO  $notificationO
     * @return void
     */
    public function forceDeleted(NotificationO $notificationO)
    {
        //
    }
}
