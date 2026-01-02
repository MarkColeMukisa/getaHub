<?php

namespace App\Events;

use App\Models\Bill;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BillCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Bill $bill)
    {
        $this->bill->loadMissing('tenant');
    }

    public function broadcastOn(): array
    {
        return [new Channel('public.metrics')];
    }

    public function broadcastAs(): string
    {
        return 'bill.created';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->bill->id,
            'tenant' => $this->bill->tenant?->name,
            'room' => $this->bill->tenant?->room_number,
            'units' => $this->bill->units_used,
            'amount' => $this->bill->total_amount,
            'created_at' => $this->bill->created_at->toIso8601String(),
        ];
    }
}
