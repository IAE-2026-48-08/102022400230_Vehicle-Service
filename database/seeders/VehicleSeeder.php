<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    public function run(): void
    {
        $vehicles = [
            [
                'vehicle_code' => 'VH-001',
                'plate_number' => 'B 1022 TIA',
                'brand' => 'Toyota',
                'model' => 'Avanza',
                'vehicle_type' => 'MPV',
                'capacity' => 7,
                'fuel_type' => 'Gasoline',
                'status' => 'Available',
                'last_service_date' => '2026-05-10',
                'notes' => 'Siap digunakan untuk perjalanan dinas dalam kota.',
            ],
            [
                'vehicle_code' => 'VH-002',
                'plate_number' => 'B 230 IAE',
                'brand' => 'Mitsubishi',
                'model' => 'Xpander',
                'vehicle_type' => 'MPV',
                'capacity' => 7,
                'fuel_type' => 'Gasoline',
                'status' => 'In-Use',
                'last_service_date' => '2026-04-22',
                'notes' => 'Sedang digunakan untuk penugasan perjalanan dinas.',
            ],
            [
                'vehicle_code' => 'VH-003',
                'plate_number' => 'D 4002 KDR',
                'brand' => 'Isuzu',
                'model' => 'Elf',
                'vehicle_type' => 'Minibus',
                'capacity' => 15,
                'fuel_type' => 'Diesel',
                'status' => 'Maintenance',
                'last_service_date' => '2026-05-28',
                'notes' => 'Menunggu pemeriksaan rem dan oli mesin.',
            ],
            [
                'vehicle_code' => 'VH-004',
                'plate_number' => 'B 7788 OPS',
                'brand' => 'Honda',
                'model' => 'BR-V',
                'vehicle_type' => 'SUV',
                'capacity' => 7,
                'fuel_type' => 'Gasoline',
                'status' => 'Available',
                'last_service_date' => '2026-05-18',
                'notes' => 'Direkomendasikan untuk perjalanan luar kota jarak sedang.',
            ],
        ];

        foreach ($vehicles as $vehicle) {
            Vehicle::updateOrCreate(
                ['vehicle_code' => $vehicle['vehicle_code']],
                $vehicle
            );
        }
    }
}
