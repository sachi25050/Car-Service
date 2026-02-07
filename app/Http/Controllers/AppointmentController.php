<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAppointmentRequest;
use App\Models\Appointment;
use App\Models\Customer;
use App\Models\Vehicle;
use App\Models\Service;
use App\Models\ServicePackage;
use App\Models\Staff;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointment::with(['customer', 'vehicle', 'service', 'package', 'assignedStaff']);

        // Default to today if no date is provided
        $selectedDate = $request->filled('date') ? $request->date : now()->format('Y-m-d');
        
        if ($request->filled('date')) {
            $query->whereDate('appointment_date', $request->date);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // For calendar view, we need all appointments for the selected date, not paginated
        $calendarQuery = clone $query;
        $calendarAppointments = $calendarQuery->whereDate('appointment_date', $selectedDate)
            ->where('status', '!=', 'cancelled') // Exclude cancelled for calendar view
            ->orderBy('appointment_time', 'asc')
            ->get();

        // Prepare appointments for calendar display with column assignments
        $preparedCalendarAppointments = $this->prepareCalendarAppointments($calendarAppointments);

        // Identify which hours have appointments (for collapsible time slots)
        $occupiedHours = [];
        if (count($preparedCalendarAppointments) > 0) {
            foreach ($preparedCalendarAppointments as $calApp) {
                $startHour = (int) $calApp['start']->format('H');
                $endHour = (int) $calApp['end']->format('H');
                // Mark all hours that the appointment spans
                for ($hour = $startHour; $hour <= $endHour; $hour++) {
                    if ($hour >= 8 && $hour <= 20) { // Only within our calendar range
                        $occupiedHours[$hour] = true;
                    }
                }
            }
        }

        // For backward compatibility, also provide paginated version if needed
        $appointments = $query->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc')
            ->paginate(15);

        return view('appointments.index', compact('appointments', 'preparedCalendarAppointments', 'selectedDate', 'occupiedHours'));
    }

    public function create()
    {
        $customers = Customer::where('is_active', true)->get();
        $services = Service::where('is_active', true)->get();
        $packages = ServicePackage::where('is_active', true)->get();
        $staff = Staff::with('user')->get();

        return view('appointments.create', compact('customers', 'services', 'packages', 'staff'));
    }

    /**
     * Store a newly created appointment
     * 
     * Validates the appointment data and checks for conflicts using the appointment
     * conflict detection logic. See Appointment::checkConflict() and 
     * docs/APPOINTMENT_CONFLICT_LOGIC.md for detailed conflict rules.
     * 
     * Conflict Rules:
     * - 12-minute buffer between appointment start times
     * - Maximum 5 concurrent appointments at any moment
     * 
     * @param StoreAppointmentRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreAppointmentRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = auth()->id();

        // Get settings for conflict checking
        // These values can be configured in the settings table
        $minGap = \App\Models\Setting::get('appointment_minimum_gap_minutes', 12);
        $maxConcurrent = \App\Models\Setting::get('appointment_max_concurrent', 5);
        $duration = $data['estimated_duration'] ?? 60;

        // Check for conflicts using the conflict detection logic
        // See Appointment::checkConflict() for implementation details
        $conflict = Appointment::checkConflict(
            $data['appointment_date'],
            $data['appointment_time'],
            $duration,
            $minGap,
            $maxConcurrent
        );

        if ($conflict) {
            return back()->withErrors([
                'appointment_time' => "This time slot conflicts with existing appointments. Please ensure at least {$minGap} minutes gap between appointment start times, and maximum {$maxConcurrent} concurrent appointments."
            ])->withInput();
        }

        $appointment = Appointment::create($data);

        ActivityLog::log('appointment_created', "Appointment created for {$appointment->customer->full_name}", $appointment);

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment created successfully.');
    }

    public function show(Appointment $appointment)
    {
        $appointment->load(['customer', 'vehicle', 'service', 'package', 'assignedStaff', 'jobCard']);
        return view('appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment)
    {
        $customers = Customer::where('is_active', true)->get();
        $vehicles = Vehicle::where('customer_id', $appointment->customer_id)->get();
        $services = Service::where('is_active', true)->get();
        $packages = ServicePackage::where('is_active', true)->get();
        $staff = Staff::with('user')->get();

        return view('appointments.edit', compact('appointment', 'customers', 'vehicles', 'services', 'packages', 'staff'));
    }

    /**
     * Update an existing appointment
     * 
     * Validates the appointment data and checks for conflicts using the appointment
     * conflict detection logic. The current appointment is excluded from conflict
     * checking to prevent it from conflicting with itself.
     * 
     * See Appointment::checkConflict() and docs/APPOINTMENT_CONFLICT_LOGIC.md 
     * for detailed conflict rules.
     * 
     * @param StoreAppointmentRequest $request
     * @param Appointment $appointment The appointment to update
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(StoreAppointmentRequest $request, Appointment $appointment)
    {
        $data = $request->validated();

        // Get settings for conflict checking
        // These values can be configured in the settings table
        $minGap = \App\Models\Setting::get('appointment_minimum_gap_minutes', 12);
        $maxConcurrent = \App\Models\Setting::get('appointment_max_concurrent', 5);
        $duration = $data['estimated_duration'] ?? 60;

        // Check for conflicts using the conflict detection logic
        // Exclude the current appointment from conflict checking (prevents self-conflict)
        // See Appointment::checkConflict() for implementation details
        $conflict = Appointment::checkConflict(
            $data['appointment_date'],
            $data['appointment_time'],
            $duration,
            $minGap,
            $maxConcurrent,
            $appointment->id // Exclude this appointment from conflict check
        );

        if ($conflict) {
            return back()->withErrors([
                'appointment_time' => "This time slot conflicts with existing appointments. Please ensure at least {$minGap} minutes gap between appointment start times, and maximum {$maxConcurrent} concurrent appointments."
            ])->withInput();
        }

        $appointment->update($data);

        ActivityLog::log('appointment_updated', "Appointment updated for {$appointment->customer->full_name}", $appointment);

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment updated successfully.');
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        ActivityLog::log('appointment_deleted', "Appointment deleted", $appointment);

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment deleted successfully.');
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,in_progress,completed,cancelled,no_show'
        ]);

        $appointment->update(['status' => $request->status]);

        ActivityLog::log('appointment_status_changed', "Appointment status changed to {$request->status}", $appointment);

        return back()->with('success', 'Appointment status updated.');
    }

    /**
     * Prepare appointments for calendar day view with column assignments
     * Handles up to 5 concurrent appointments with proper positioning
     */
    private function prepareCalendarAppointments($appointments)
    {
        $prepared = [];
        
        foreach ($appointments as $appointment) {
            $startTime = \Carbon\Carbon::parse(
                $appointment->appointment_date->format('Y-m-d') . ' ' . $appointment->appointment_time
            );
            $endTime = $startTime->copy()->addMinutes($appointment->estimated_duration ?? 60);
            
            $prepared[] = [
                'id' => $appointment->id,
                'appointment' => $appointment,
                'start' => $startTime,
                'end' => $endTime,
                'startMinutes' => $startTime->hour * 60 + $startTime->minute,
                'duration' => $appointment->estimated_duration ?? 60,
                'column' => 0, // Will be assigned below
            ];
        }
        
        // Sort by start time
        usort($prepared, function($a, $b) {
            return $a['startMinutes'] <=> $b['startMinutes'];
        });
        
        // Assign columns based on overlaps (max 5 columns)
        $maxColumns = 5;
        for ($i = 0; $i < count($prepared); $i++) {
            $usedColumns = [];
            
            // Find all appointments that overlap with this one
            for ($j = 0; $j < $i; $j++) {
                if ($this->appointmentsOverlap($prepared[$i], $prepared[$j])) {
                    $usedColumns[] = $prepared[$j]['column'];
                }
            }
            
            // Assign the first available column (0-4)
            for ($col = 0; $col < $maxColumns; $col++) {
                if (!in_array($col, $usedColumns)) {
                    $prepared[$i]['column'] = $col;
                    break;
                }
            }
        }
        
        return $prepared;
    }

    /**
     * Check if two appointments overlap in time
     */
    private function appointmentsOverlap($app1, $app2)
    {
        return $app1['start'] < $app2['end'] && $app2['start'] < $app1['end'];
    }
}
