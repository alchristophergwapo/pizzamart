<?php

    namespace App\Events;

    use Illuminate\Broadcasting\Channel;
    use Illuminate\Broadcasting\InteractsWithSockets;
    use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
    use Illuminate\Foundation\Events\Dispatchable;
    use Illuminate\Queue\SerializesModels;

    class OrderEvent implements ShouldBroadcastNow
    {
        use Dispatchable, InteractsWithSockets, SerializesModels;

        public $order;

        /**
         * Create a new event instance.
         *
         * @param  $order
         *
         * @return void
         */

        public function __construct($order)
        {
            //
            $this->order = $order;
        }

        /**
         * Get the channels the event should broadcast on.
         *
         * @return \Illuminate\Broadcasting\Channel|array
         */

        public function broadcastOn()
            {
                return ['pizzamart-channel'];
            }

        public function broadcastAs()
        {
            
            return 'neworder';
        }


    }