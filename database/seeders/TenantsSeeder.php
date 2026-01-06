<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TenantsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tenants')->insert([
            [
                'id' => 1,
                'name' => 'Liberty and Comfort',
                'room_number' => 'rn-01',
                'contact' => '+256786033764',
                'created_at' => '2025-08-14 11:31:28',
                'updated_at' => '2026-01-03 14:21:27',
            ],
            [
                'id' => 2,
                'name' => 'Ayo Danstant Innocent',
                'room_number' => 'rn-02',
                'contact' => '+256783954381',
                'created_at' => '2025-08-14 11:32:09',
                'updated_at' => '2026-01-03 10:17:55',
            ],
            [
                'id' => 3,
                'name' => 'Mwebaze Hillary',
                'room_number' => 'rn-03',
                'contact' => '+256706188561',
                'created_at' => '2025-08-14 11:33:18',
                'updated_at' => '2026-01-03 10:18:53',
            ],
            [
                'id' => 4,
                'name' => 'Madam Nakalembe Christine',
                'room_number' => 'rn-04',
                'contact' => '+256781699770',
                'created_at' => '2025-08-14 11:34:08',
                'updated_at' => '2026-01-03 10:20:06',
            ],
            [
                'id' => 5,
                'name' => 'Mark Cole',
                'room_number' => 'rn-05',
                'contact' => '+256702262806',
                'created_at' => '2026-01-03 11:12:00',
                'updated_at' => '2026-01-03 11:12:00',
            ],
        ]);
    }
}
