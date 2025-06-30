<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ParkingSlotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = ['available', 'occupied'];
        for ($i = 1; $i <= 10; $i++) {
            \App\Models\ParkingSlot::create([
                'slot_code' => 'A-' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'status' => $statuses[array_rand($statuses)],
                'last_detection' => now(),
            ]);
        }
    }
}
