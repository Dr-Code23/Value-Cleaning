<?php

namespace Modules\Order\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Order\Entities\Order;

class OrderCanceled implements ShouldBroadcast
{
    /**
     * @var Order
     */
    public Order $order;

    /**
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * @return string[]
     */
    public function broadcastOn(): array
    {
        return ['order-canceled'];
    }

    public function broadcastAs()
    {
        return 'order-canceled';
    }

    /**
     * @return string[]
     */
    public function broadcastWith()
    {
        return [
            'message' => 'Order #' . $this->order->id . ' has been canceled.',
        ];
    }
}
