<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john.doe@example.com',
                'phone' => '9876543210',
                'address' => '123 Main Street',
                'city' => 'Mumbai',
                'state' => 'Maharashtra',
                'zip_code' => '400001',
                'is_active' => true,
                'vehicles' => [
                    [
                        'registration_number' => 'MH-01-AB-1234',
                        'make' => 'Maruti',
                        'model' => 'Swift',
                        'year' => 2020,
                        'color' => 'White',
                        'vehicle_type' => 'hatchback',
                        'fuel_type' => 'petrol',
                    ],
                ],
            ],
            [
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'email' => 'jane.smith@example.com',
                'phone' => '9876543211',
                'address' => '456 Park Avenue',
                'city' => 'Delhi',
                'state' => 'Delhi',
                'zip_code' => '110001',
                'is_active' => true,
                'vehicles' => [
                    [
                        'registration_number' => 'DL-01-CD-5678',
                        'make' => 'Hyundai',
                        'model' => 'Creta',
                        'year' => 2021,
                        'color' => 'Black',
                        'vehicle_type' => 'suv',
                        'fuel_type' => 'diesel',
                    ],
                ],
            ],
        ];

        foreach ($customers as $customerData) {
            $vehicles = $customerData['vehicles'];
            unset($customerData['vehicles']);

            $customer = Customer::create($customerData);

            foreach ($vehicles as $vehicleData) {
                $vehicleData['customer_id'] = $customer->id;
                Vehicle::create($vehicleData);
            }
        }
    }
}
