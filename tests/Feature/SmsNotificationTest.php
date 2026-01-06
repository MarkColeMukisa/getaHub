<?php

use App\Models\Bill;
use App\Models\Tenant;
use App\Services\NotificationService;
use App\Contracts\SmsServiceInterface;
use App\Jobs\AutomateSmsNotificationsJob;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->tenant = Tenant::factory()->create([
        'name' => 'Test Tenant',
        'contact' => '0702262806'
    ]);

    $this->bill = Bill::factory()->create([
        'tenant_id' => $this->tenant->id,
        'grand_total' => 50000,
        'month' => 'January',
        'year' => 2026,
        'notified_at' => null
    ]);
});

it('can send a notification via the service', function () {
    // Mock the SMS Service Interface
    $smsMock = mock(SmsServiceInterface::class);
    $smsMock->shouldReceive('send')
        ->once()
        ->with('0702262806', Mockery::type('string'))
        ->andReturn(response()->json(['status' => 'success']));

    $service = new NotificationService($smsMock);
    $result = $service->notify($this->bill);

    expect($result)->toBeTrue();
    $this->bill->refresh();
    expect($this->bill->notified_at)->not->toBeNull();
    expect($this->bill->notification_attempts)->toBe(1);
});

it('logs failure when contact is missing', function () {
    $this->tenant->update(['contact' => '']);

    $smsMock = mock(SmsServiceInterface::class);
    $smsMock->shouldNotReceive('send');

    $service = new NotificationService($smsMock);
    $result = $service->notify($this->bill);

    expect($result)->toBeFalse();
    $this->bill->refresh();
    expect($this->bill->notification_error)->toBe('Missing contact information');
});

it('automates notifications via job', function () {
    $smsMock = mock(SmsServiceInterface::class);
    $smsMock->shouldReceive('send')->once()->andReturn(response()->json(['status' => 'success']));

    // Bind mock to container for the job to pick up
    $this->app->instance(SmsServiceInterface::class, $smsMock);

    (new AutomateSmsNotificationsJob())->handle(app(NotificationService::class));

    $this->bill->refresh();
    expect($this->bill->notified_at)->not->toBeNull();
});
