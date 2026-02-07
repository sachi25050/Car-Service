<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_id',
        'vehicle_id',
        'service_id',
        'package_id',
        'appointment_date',
        'appointment_time',
        'estimated_duration',
        'status',
        'notes',
        'assigned_staff_id',
        'created_by',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'estimated_duration' => 'integer',
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function package()
    {
        return $this->belongsTo(ServicePackage::class, 'package_id');
    }

    public function assignedStaff()
    {
        return $this->belongsTo(Staff::class, 'assigned_staff_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function jobCard()
    {
        return $this->hasOne(JobCard::class);
    }

    // Accessors
    public function getAppointmentDateTimeAttribute()
    {
        return $this->appointment_date->format('d/m/Y') . ' ' . $this->appointment_time;
    }

    // Helper methods
    /**
     * Check if a new appointment conflicts with existing appointments
     * 
     * This method implements the appointment conflict detection logic for a car service
     * business in Sri Lanka. It enforces two main rules:
     * 
     * RULE 1: 12-Minute Start Time Buffer
     * ------------------------------------
     * The start time of a new appointment must be at least 12 minutes before OR after
     * the start time of any existing appointment. This prevents multiple customers from
     * arriving at the same time and helps manage workflow.
     * 
     * Example:
     * - Existing appointment starts at 10:00 AM
     * - New appointment can start at:
     *   ✓ 9:48 AM or earlier (12+ minutes before)
     *   ✓ 10:12 AM or later (12+ minutes after)
     *   ✗ 9:50 AM (only 10 minutes before - CONFLICT)
     *   ✗ 10:05 AM (only 5 minutes after - CONFLICT)
     * 
     * Note: This buffer applies ONLY to start times, not end times. Appointments can
     * overlap during their duration as long as start times are properly spaced.
     * 
     * RULE 2: Maximum 5 Concurrent Appointments
     * -----------------------------------------
     * At any given moment, there can be a maximum of 5 appointments running
     * simultaneously. This reflects the physical capacity of the service center.
     * 
     * The system checks every minute during the new appointment's duration to ensure
     * that adding this appointment would not exceed the limit at any point in time.
     * 
     * Example:
     * - 4 appointments are currently running from 10:00 AM to 11:00 AM
     * - New appointment: 10:30 AM to 11:30 AM (60 minutes)
     * - At 10:30 AM: 4 existing + 1 new = 5 total ✓ (ALLOWED)
     * - At 10:45 AM: 4 existing + 1 new = 5 total ✓ (ALLOWED)
     * - If 5 appointments were already running, adding this would make 6 ✗ (CONFLICT)
     * 
     * VALIDATION PROCESS:
     * -------------------
     * 1. First, check if the start time violates the 12-minute buffer rule
     *    - If yes, return true (conflict found) immediately
     * 2. Then, check concurrent appointments throughout the duration
     *    - Iterate through each minute of the new appointment
     *    - Count how many existing appointments are active at that moment
     *    - If count >= maxConcurrent at any moment, return true (conflict found)
     * 3. If both checks pass, return false (no conflict)
     * 
     * BUSINESS CONTEXT:
     * -----------------
     * This logic is designed for a car service center where:
     * - Multiple vehicles can be serviced simultaneously (up to 5)
     * - Start times need spacing to manage customer arrivals and workflow
     * - The system will be enhanced with more advanced logic in the future
     * 
     * @param string $date Appointment date in Y-m-d format (e.g., "2026-02-09")
     * @param string $time Appointment start time in H:i format (e.g., "14:30")
     * @param int $duration Duration of the appointment in minutes (e.g., 60 for 1 hour)
     * @param int $bufferMinutes Minimum gap between appointment start times in minutes (default: 12)
     *                          This value can be configured via 'appointment_minimum_gap_minutes' setting
     * @param int $maxConcurrent Maximum number of concurrent appointments allowed (default: 5)
     *                          This value can be configured via 'appointment_max_concurrent' setting
     * @param int|null $excludeId Appointment ID to exclude from conflict check (used when updating an existing appointment)
     *                            Set to null when creating a new appointment
     * 
     * @return bool Returns true if a conflict is detected, false if the appointment can be scheduled
     * 
     * @example
     * // Check if a new appointment conflicts
     * $conflict = Appointment::checkConflict('2026-02-09', '14:30', 60);
     * if ($conflict) {
     *     // Handle conflict
     * }
     * 
     * // Check conflict when updating (exclude current appointment)
     * $conflict = Appointment::checkConflict('2026-02-09', '15:00', 60, 12, 5, $appointment->id);
     */
    public static function checkConflict($date, $time, $duration, $bufferMinutes = 12, $maxConcurrent = 5, $excludeId = null)
    {
        // Parse the new appointment's start and end times
        $newStart = \Carbon\Carbon::parse("$date $time");
        $newEnd = $newStart->copy()->addMinutes($duration);
        
        // Query existing appointments for the same date
        // Only consider non-cancelled appointments
        $query = self::where('appointment_date', $date)
            ->where('status', '!=', 'cancelled');
        
        // When updating an appointment, exclude it from conflict checking
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        // Get all existing appointments that need to be checked
        $existingAppointments = $query->get();
        
        // ====================================================================
        // CHECK 1: 12-Minute Start Time Buffer
        // ====================================================================
        // Rule: New appointment start time must be at least {bufferMinutes} minutes
        // before OR after any existing appointment's start time.
        // 
        // This prevents multiple customers from arriving at the same time,
        // helping to manage workflow and reduce congestion at the service center.
        
        $startTimeConflicts = $existingAppointments->filter(function($existing) use ($newStart, $bufferMinutes) {
            // Parse the existing appointment's start time
            $existingStart = \Carbon\Carbon::parse(
                $existing->appointment_date->format('Y-m-d') . ' ' . $existing->appointment_time
            );
            
            // Calculate the absolute time difference in minutes between start times
            $timeDiff = abs($newStart->diffInMinutes($existingStart));
            
            // If the difference is less than the required buffer, it's a conflict
            // Example: If buffer is 12 minutes and difference is 10 minutes, it's a conflict
            return $timeDiff < $bufferMinutes;
        });
        
        // If any start time conflicts are found, return immediately
        // No need to check concurrent appointments if start time is invalid
        if ($startTimeConflicts->isNotEmpty()) {
            return true; // Conflict: Start time too close to existing appointment start time
        }
        
        // ====================================================================
        // CHECK 2: Maximum Concurrent Appointments
        // ====================================================================
        // Rule: At any given moment, there can be a maximum of {maxConcurrent}
        // appointments running simultaneously.
        // 
        // This reflects the physical capacity of the service center (e.g., 5 service bays).
        // We check every minute during the new appointment's duration to ensure
        // we never exceed this limit.
        
        // Start checking from the beginning of the new appointment
        $checkTime = $newStart->copy();
        
        // Iterate through each minute of the new appointment's duration
        while ($checkTime < $newEnd) {
            // Count how many existing appointments are active at this specific moment
            $concurrentCount = $existingAppointments->filter(function($existing) use ($checkTime) {
                // Parse the existing appointment's start and end times
                $existingStart = \Carbon\Carbon::parse(
                    $existing->appointment_date->format('Y-m-d') . ' ' . $existing->appointment_time
                );
                // Calculate end time based on estimated duration (default 60 minutes if not set)
                $existingEnd = $existingStart->copy()->addMinutes($existing->estimated_duration ?? 60);
                
                // Check if the current moment (checkTime) falls within the existing appointment's duration
                // An appointment is "active" if: checkTime >= start AND checkTime < end
                // Note: We use < instead of <= for end time to handle exact boundaries correctly
                return ($checkTime >= $existingStart) && ($checkTime < $existingEnd);
            })->count();
            
            // If we already have the maximum number of concurrent appointments,
            // adding this new one would exceed the limit
            if ($concurrentCount >= $maxConcurrent) {
                return true; // Conflict: Too many concurrent appointments at this moment
            }
            
            // Move to the next minute and continue checking
            $checkTime->addMinute();
        }
        
        // ====================================================================
        // SUCCESS: No Conflicts Found
        // ====================================================================
        // Both checks passed:
        // 1. Start time has proper spacing (12+ minutes from other start times)
        // 2. Concurrent appointment limit is not exceeded at any point
        return false; // No conflicts found - appointment can be scheduled
    }
}
