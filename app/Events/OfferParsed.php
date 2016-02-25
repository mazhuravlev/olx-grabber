<?php

namespace App\Events;

use App\Models\Offer;
use Illuminate\Queue\SerializesModels;

class OfferParsed extends Event
{
    use SerializesModels;

    public $offer;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Offer $offer)
    {
        $this->offer = $offer;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
