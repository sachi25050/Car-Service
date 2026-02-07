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
                'first_name' => 'Kamal',
                'last_name' => 'Perera',
                'email' => 'kamal.perera@example.com',
                'phone' => '0771234567',
                'alternate_phone' => '0112345678',
                'address' => '123 Galle Road',
                'city' => 'Colombo',
                'state' => 'Western Province',
                'zip_code' => '00300',
                'is_active' => true,
                'vehicles' => [
                    [
                        'registration_number' => '19-2523',
                        'make' => 'Toyota',
                        'model' => 'Corolla',
                        'year' => 2020,
                        'color' => 'White',
                        'vehicle_type' => 'sedan',
                        'fuel_type' => 'petrol',
                        'mileage' => 45000,
                    ],
                ],
            ],
            [
                'first_name' => 'Nimal',
                'last_name' => 'Fernando',
                'email' => 'nimal.fernando@example.com',
                'phone' => '0772345678',
                'address' => '456 Kandy Road',
                'city' => 'Kandy',
                'state' => 'Central Province',
                'zip_code' => '20000',
                'is_active' => true,
                'vehicles' => [
                    [
                        'registration_number' => 'KS-8804',
                        'make' => 'Honda',
                        'model' => 'Civic',
                        'year' => 2021,
                        'color' => 'Black',
                        'vehicle_type' => 'sedan',
                        'fuel_type' => 'petrol',
                        'mileage' => 32000,
                    ],
                ],
            ],
            [
                'first_name' => 'Sunil',
                'last_name' => 'Jayawardena',
                'email' => 'sunil.jayawardena@example.com',
                'phone' => '0773456789',
                'address' => '789 Negombo Road',
                'city' => 'Negombo',
                'state' => 'Western Province',
                'zip_code' => '11500',
                'is_active' => true,
                'vehicles' => [
                    [
                        'registration_number' => 'ABB-1223',
                        'make' => 'Nissan',
                        'model' => 'Sunny',
                        'year' => 2019,
                        'color' => 'Silver',
                        'vehicle_type' => 'sedan',
                        'fuel_type' => 'petrol',
                        'mileage' => 55000,
                    ],
                ],
            ],
            [
                'first_name' => 'Priyanka',
                'last_name' => 'Wickramasinghe',
                'email' => 'priyanka.wickramasinghe@example.com',
                'phone' => '0774567890',
                'address' => '321 Matara Road',
                'city' => 'Galle',
                'state' => 'Southern Province',
                'zip_code' => '80000',
                'is_active' => true,
                'vehicles' => [
                    [
                        'registration_number' => '32-5678',
                        'make' => 'Suzuki',
                        'model' => 'Swift',
                        'year' => 2022,
                        'color' => 'Red',
                        'vehicle_type' => 'hatchback',
                        'fuel_type' => 'petrol',
                        'mileage' => 15000,
                    ],
                ],
            ],
            [
                'first_name' => 'Dilshan',
                'last_name' => 'Silva',
                'email' => 'dilshan.silva@example.com',
                'phone' => '0775678901',
                'address' => '654 Anuradhapura Road',
                'city' => 'Kurunegala',
                'state' => 'North Western Province',
                'zip_code' => '60000',
                'is_active' => true,
                'vehicles' => [
                    [
                        'registration_number' => 'NW-3456',
                        'make' => 'Toyota',
                        'model' => 'Land Cruiser',
                        'year' => 2020,
                        'color' => 'White',
                        'vehicle_type' => 'suv',
                        'fuel_type' => 'diesel',
                        'mileage' => 38000,
                    ],
                ],
            ],
            [
                'first_name' => 'Chamari',
                'last_name' => 'Bandara',
                'email' => 'chamari.bandara@example.com',
                'phone' => '0776789012',
                'address' => '987 Ratnapura Road',
                'city' => 'Ratnapura',
                'state' => 'Sabaragamuwa Province',
                'zip_code' => '70000',
                'is_active' => true,
                'vehicles' => [
                    [
                        'registration_number' => 'SP-7890',
                        'make' => 'Mitsubishi',
                        'model' => 'Montero',
                        'year' => 2018,
                        'color' => 'Black',
                        'vehicle_type' => 'suv',
                        'fuel_type' => 'diesel',
                        'mileage' => 65000,
                    ],
                ],
            ],
            [
                'first_name' => 'Tharindu',
                'last_name' => 'Gunasekara',
                'email' => 'tharindu.gunasekara@example.com',
                'phone' => '0777890123',
                'address' => '147 Jaffna Road',
                'city' => 'Vavuniya',
                'state' => 'Northern Province',
                'zip_code' => '43000',
                'is_active' => true,
                'vehicles' => [
                    [
                        'registration_number' => 'NP-4567',
                        'make' => 'Hyundai',
                        'model' => 'Elantra',
                        'year' => 2021,
                        'color' => 'Blue',
                        'vehicle_type' => 'sedan',
                        'fuel_type' => 'petrol',
                        'mileage' => 28000,
                    ],
                ],
            ],
            [
                'first_name' => 'Sanduni',
                'last_name' => 'Ratnayake',
                'email' => 'sanduni.ratnayake@example.com',
                'phone' => '0778901234',
                'address' => '258 Batticaloa Road',
                'city' => 'Batticaloa',
                'state' => 'Eastern Province',
                'zip_code' => '30000',
                'is_active' => true,
                'vehicles' => [
                    [
                        'registration_number' => 'EP-2345',
                        'make' => 'Toyota',
                        'model' => 'Vitz',
                        'year' => 2019,
                        'color' => 'White',
                        'vehicle_type' => 'hatchback',
                        'fuel_type' => 'petrol',
                        'mileage' => 42000,
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
