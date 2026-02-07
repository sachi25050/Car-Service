# Appointment Conflict Detection Logic

## Overview

This document describes the appointment conflict detection logic implemented in the Car Service Management System. The logic is designed for a car service business in Sri Lanka where multiple vehicles can be serviced simultaneously, but start times need to be spaced to manage workflow and customer arrivals.

## Business Rules

### Rule 1: 12-Minute Start Time Buffer

**Requirement:** The start time of a new appointment must be at least 12 minutes before OR after the start time of any existing appointment.

**Purpose:** 
- Prevents multiple customers from arriving at the same time
- Helps manage workflow and reduces congestion at the service center
- Allows staff to prepare for each appointment properly

**Implementation:**
- Buffer applies **ONLY to start times**, not end times
- Appointments can overlap during their duration
- The 12-minute value is configurable via the `appointment_minimum_gap_minutes` setting

**Examples:**

✅ **Allowed:**
- Existing appointment: 10:00 AM
- New appointment: 9:48 AM (12 minutes before) ✓
- New appointment: 10:12 AM (12 minutes after) ✓
- New appointment: 9:00 AM (60 minutes before) ✓

❌ **Not Allowed:**
- Existing appointment: 10:00 AM
- New appointment: 9:50 AM (only 10 minutes before) ✗
- New appointment: 10:05 AM (only 5 minutes after) ✗
- New appointment: 10:00 AM (same time) ✗

### Rule 2: Maximum 5 Concurrent Appointments

**Requirement:** At any given moment, there can be a maximum of 5 appointments running simultaneously.

**Purpose:**
- Reflects the physical capacity of the service center (e.g., 5 service bays)
- Prevents overbooking beyond operational capacity
- Ensures quality service delivery

**Implementation:**
- The system checks **every minute** during the new appointment's duration
- Counts how many existing appointments are active at each moment
- If the count reaches 5 at any point, the new appointment is rejected
- The value 5 is configurable via the `appointment_max_concurrent` setting

**Examples:**

✅ **Allowed:**
```
Time: 10:00 AM - 11:00 AM
Existing appointments: 4 running
New appointment: 10:30 AM - 11:30 AM
Result: At 10:30 AM = 4 existing + 1 new = 5 total ✓ ALLOWED
```

❌ **Not Allowed:**
```
Time: 10:00 AM - 11:00 AM
Existing appointments: 5 running
New appointment: 10:30 AM - 11:30 AM
Result: At 10:30 AM = 5 existing + 1 new = 6 total ✗ CONFLICT
```

## Technical Implementation

### Method Signature

```php
public static function checkConflict(
    string $date,           // Appointment date (Y-m-d format)
    string $time,           // Appointment start time (H:i format)
    int $duration,          // Duration in minutes
    int $bufferMinutes = 12,      // Buffer time (default: 12)
    int $maxConcurrent = 5,       // Max concurrent (default: 5)
    int|null $excludeId = null    // Exclude ID for updates
): bool
```

### Validation Process

The conflict detection follows a two-step validation process:

#### Step 1: Start Time Buffer Check

1. Query all non-cancelled appointments for the same date
2. For each existing appointment, calculate the time difference between start times
3. If any difference is less than the buffer (12 minutes), return `true` (conflict)

**Pseudocode:**
```
FOR each existing appointment:
    timeDiff = |newStartTime - existingStartTime|
    IF timeDiff < bufferMinutes:
        RETURN true (CONFLICT)
END FOR
```

#### Step 2: Concurrent Appointment Check

1. For each minute during the new appointment's duration:
   - Count how many existing appointments are active at that moment
   - An appointment is "active" if: `checkTime >= startTime AND checkTime < endTime`
2. If the count reaches `maxConcurrent` at any moment, return `true` (conflict)

**Pseudocode:**
```
checkTime = newStartTime
WHILE checkTime < newEndTime:
    concurrentCount = 0
    FOR each existing appointment:
        IF checkTime is within existing appointment duration:
            concurrentCount++
    END FOR
    
    IF concurrentCount >= maxConcurrent:
        RETURN true (CONFLICT)
    
    checkTime += 1 minute
END WHILE
```

### Return Values

- `true`: Conflict detected - appointment cannot be scheduled
- `false`: No conflict - appointment can be scheduled

## Configuration

The conflict detection logic uses system settings that can be configured:

### Settings Table

| Key | Type | Default | Description |
|-----|------|---------|-------------|
| `appointment_minimum_gap_minutes` | number | 12 | Minimum gap between appointment start times (in minutes) |
| `appointment_max_concurrent` | number | 5 | Maximum number of concurrent appointments allowed |

### Accessing Settings

```php
// Get buffer time (default: 12 minutes)
$bufferMinutes = \App\Models\Setting::get('appointment_minimum_gap_minutes', 12);

// Get max concurrent (default: 5)
$maxConcurrent = \App\Models\Setting::get('appointment_max_concurrent', 5);
```

### Updating Settings

```php
// Update buffer time
\App\Models\Setting::set('appointment_minimum_gap_minutes', 15, 'number', 'Appointments');

// Update max concurrent
\App\Models\Setting::set('appointment_max_concurrent', 6, 'number', 'Appointments');
```

## Usage Examples

### Creating a New Appointment

```php
use App\Models\Appointment;

$date = '2026-02-09';
$time = '14:30';
$duration = 60; // 60 minutes

// Get settings
$bufferMinutes = \App\Models\Setting::get('appointment_minimum_gap_minutes', 12);
$maxConcurrent = \App\Models\Setting::get('appointment_max_concurrent', 5);

// Check for conflicts
$conflict = Appointment::checkConflict($date, $time, $duration, $bufferMinutes, $maxConcurrent);

if ($conflict) {
    // Handle conflict - show error message
    return back()->withErrors([
        'appointment_time' => 'This time slot conflicts with existing appointments.'
    ]);
}

// No conflict - create appointment
$appointment = Appointment::create([...]);
```

### Updating an Existing Appointment

```php
// When updating, exclude the current appointment from conflict check
$conflict = Appointment::checkConflict(
    $date, 
    $time, 
    $duration, 
    $bufferMinutes, 
    $maxConcurrent,
    $appointment->id // Exclude this appointment
);
```

## Edge Cases

### Same Start Time
- Two appointments cannot have the same start time (violates 12-minute buffer)
- The buffer check will catch this immediately

### Overlapping Durations
- Appointments can overlap during their duration as long as:
  1. Start times are at least 12 minutes apart
  2. Total concurrent appointments never exceed 5

### Appointment Updates
- When updating an appointment, it's excluded from conflict checking
- This prevents an appointment from conflicting with itself

### Cancelled Appointments
- Cancelled appointments are excluded from conflict checking
- Only active appointments (pending, confirmed, in_progress, completed) are considered

## Future Enhancements

The current implementation is designed to be simple and effective for the current business needs. Future enhancements may include:

- Staff-specific capacity limits
- Service-specific duration requirements
- Time-of-day based capacity (e.g., more capacity during off-peak hours)
- Resource allocation (e.g., specific equipment or tools)
- Customer priority levels
- Automatic rescheduling suggestions

## Related Files

- **Model:** `app/Models/Appointment.php` - Contains the `checkConflict()` method
- **Controller:** `app/Http/Controllers/AppointmentController.php` - Uses conflict checking in `store()` and `update()` methods
- **Settings:** `app/Models/Setting.php` - Manages system configuration

## Testing

To test the conflict detection logic:

1. Create an appointment at 10:00 AM (60 minutes duration)
2. Try to create another appointment at 10:05 AM - should fail (buffer violation)
3. Try to create another appointment at 10:12 AM - should succeed (12+ minutes gap)
4. Create 5 appointments that overlap - should succeed
5. Try to create a 6th overlapping appointment - should fail (max concurrent violation)

---

**Last Updated:** February 2026  
**Version:** 1.0  
**Author:** Car Service Management System
