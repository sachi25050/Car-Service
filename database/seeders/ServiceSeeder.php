<?php

namespace Database\Seeders;

use App\Models\ServiceCategory;
use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        // Service Categories
        $washCategory = ServiceCategory::create([
            'name' => 'Car Wash',
            'description' => 'Car washing services',
            'is_active' => true,
            'display_order' => 1,
        ]);

        $maintenanceCategory = ServiceCategory::create([
            'name' => 'Maintenance',
            'description' => 'Vehicle maintenance services',
            'is_active' => true,
            'display_order' => 2,
        ]);

        // Services
        $services = [
            [
                'category_id' => $washCategory->id,
                'name' => 'Basic Wash',
                'description' => 'Exterior wash and dry',
                'service_type' => 'car_wash',
                'duration_minutes' => 30,
                'base_price' => 200.00,
                'is_active' => true,
            ],
            [
                'category_id' => $washCategory->id,
                'name' => 'Premium Wash',
                'description' => 'Exterior wash, interior vacuum, and tire shine',
                'service_type' => 'car_wash',
                'duration_minutes' => 60,
                'base_price' => 500.00,
                'is_active' => true,
            ],
            [
                'category_id' => $washCategory->id,
                'name' => 'Full Service',
                'description' => 'Complete interior and exterior detailing',
                'service_type' => 'car_wash',
                'duration_minutes' => 120,
                'base_price' => 1500.00,
                'is_active' => true,
            ],
            [
                'category_id' => $maintenanceCategory->id,
                'name' => 'Oil Change',
                'description' => 'Engine oil and filter change',
                'service_type' => 'maintenance',
                'duration_minutes' => 45,
                'base_price' => 800.00,
                'is_active' => true,
            ],
            [
                'category_id' => $maintenanceCategory->id,
                'name' => 'Tire Rotation',
                'description' => 'Tire rotation and balance',
                'service_type' => 'maintenance',
                'duration_minutes' => 30,
                'base_price' => 300.00,
                'is_active' => true,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
