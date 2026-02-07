<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Customer;
use App\Models\Service;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all customers and their vehicles
        $customers = Customer::with('vehicles')->get();
        
        if ($customers->isEmpty()) {
            $this->command->warn('No customers found. Please run CustomerSeeder first.');
            return;
        }

        // Get available services
        $services = Service::where('is_active', true)->get();
        
        if ($services->isEmpty()) {
            $this->command->warn('No services found. Please run ServiceSeeder first.');
            return;
        }

        // Create appointments for the next 30 days
        $appointments = [];
        $baseDate = Carbon::today();
        $timeSlots = ['09:00', '10:00', '11:00', '13:00', '14:00', '15:00', '16:00', '17:00'];
        $statuses = ['pending', 'confirmed', 'in_progress', 'completed'];
        
        $appointmentIndex = 0;
        
        // Create appointments for each customer
        foreach ($customers as $customer) {
            if ($customer->vehicles->isEmpty()) {
                continue;
            }
            
            $vehicle = $customer->vehicles->first();
            $service = $services->random();
            
            // Create 2-4 appointments per customer spread over the next 30 days
            $numAppointments = rand(2, 4);
            
            for ($i = 0; $i < $numAppointments; $i++) {
                // Random date within next 30 days
                $daysOffset = rand(0, 29);
                $appointmentDate = $baseDate->copy()->addDays($daysOffset);
                
                // Random time slot
                $timeSlot = $timeSlots[array_rand($timeSlots)];
                
                // Random status (weighted towards pending and confirmed)
                $status = $statuses[array_rand($statuses)];
                
                // If status is completed, make sure date is in the past
                if ($status === 'completed' && $appointmentDate->isFuture()) {
                    $appointmentDate = $baseDate->copy()->subDays(rand(1, 30));
                }
                
                // If status is in_progress, make sure date is today or recent past
                if ($status === 'in_progress' && $appointmentDate->isFuture()) {
                    $appointmentDate = $baseDate->copy()->subDays(rand(0, 2));
                }
                
                $appointments[] = [
                    'customer_id' => $customer->id,
                    'vehicle_id' => $vehicle->id,
                    'service_id' => $service->id,
                    'package_id' => null,
                    'appointment_date' => $appointmentDate->format('Y-m-d'),
                    'appointment_time' => $timeSlot,
                    'estimated_duration' => $service->duration_minutes ?? 60,
                    'status' => $status,
                    'notes' => $this->getRandomNotes(),
                    'assigned_staff_id' => null,
                    'created_by' => null,
                    'created_at' => now()->subDays(rand(0, 60)),
                    'updated_at' => now()->subDays(rand(0, 60)),
                ];
                
                $appointmentIndex++;
            }
        }
        
        // Insert appointments in batches
        foreach (array_chunk($appointments, 50) as $chunk) {
            Appointment::insert($chunk);
        }
        
        $this->command->info('Created ' . count($appointments) . ' appointments with Sri Lankan data.');
    }
    
    private function getRandomNotes(): ?string
    {
        $notes = [
            'Customer requested early morning service',
            'Please check air conditioning system',
            'Regular maintenance service',
            'Customer mentioned unusual noise from engine',
            'First time service at this location',
            'Follow up from previous service',
            'Customer prefers morning appointment',
            'Vehicle needs thorough inspection',
            null, // Some appointments may not have notes
            null,
        ];
        
        return $notes[array_rand($notes)];
    }
}
