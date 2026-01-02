<?php

use App\Events\BillCreated;
use App\Models\Bill;
use App\Models\Tenant;
use Illuminate\Broadcasting\Channel;

it('broadcasts on public.metrics with alias bill.created', function () {
    $tenant = Tenant::factory()->create();
    $bill = Bill::factory()->create(['tenant_id' => $tenant->id]);
    $event = new BillCreated($bill);
    $channels = $event->broadcastOn();
    expect($channels)->toHaveCount(1)
        ->and($channels[0])->toBeInstanceOf(Channel::class)
        ->and($channels[0]->name)->toBe('public.metrics')
        ->and($event->broadcastAs())->toBe('bill.created')
        ->and($event->broadcastWith())
            ->id->toBe($bill->id);
});
