<?php

use Database\Seeders\BillsSeeder;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

uses(TestCase::class);

it('syncs the bills id sequence after seeding on postgres', function () {
    DB::shouldReceive('table')
        ->once()
        ->with('bills')
        ->andReturnSelf();
    DB::shouldReceive('insert')
        ->once()
        ->andReturnTrue();
    DB::shouldReceive('getDriverName')
        ->once()
        ->andReturn('pgsql');
    DB::shouldReceive('statement')
        ->once()
        ->withArgs(function (string $sql): bool {
            return str_contains($sql, "pg_get_serial_sequence('bills', 'id')");
        })
        ->andReturnTrue();

    (new BillsSeeder)->run();
});

it('skips sequence sync when not using postgres', function () {
    DB::shouldReceive('table')
        ->once()
        ->with('bills')
        ->andReturnSelf();
    DB::shouldReceive('insert')
        ->once()
        ->andReturnTrue();
    DB::shouldReceive('getDriverName')
        ->once()
        ->andReturn('sqlite');
    DB::shouldReceive('statement')
        ->never();

    (new BillsSeeder)->run();
});
