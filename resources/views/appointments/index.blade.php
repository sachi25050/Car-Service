@extends('layouts.app')

@section('title', 'Appointments')
@section('page-title', 'Appointments')

@section('content')
<div class="space-y-6 w-full max-w-full">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Appointments</h1>
            <p class="mt-1 text-sm text-gray-500">Manage customer appointments</p>
        </div>
        <a href="{{ route('appointments.create') }}" class="btn-primary-modern">
            <i class="bi bi-plus-circle"></i>
            New Appointment
        </a>
    </div>

    <!-- Calendar Day View -->
    @if(isset($preparedCalendarAppointments) && count($preparedCalendarAppointments) > 0)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
                <div class="flex items-center gap-4">
                    <button onclick="navigateDate(-1)" class="p-2 rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors" title="Previous Day">
                        <i class="bi bi-chevron-left text-lg"></i>
                    </button>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">
                            <i class="bi bi-calendar3 mr-2"></i>
                            {{ \Carbon\Carbon::parse($selectedDate ?? now())->format('l, F j, Y') }}
                        </h2>
                        <p class="text-sm text-gray-500 mt-1">{{ count($preparedCalendarAppointments) }} appointment(s) scheduled</p>
                    </div>
                    <button onclick="navigateDate(1)" class="p-2 rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors" title="Next Day">
                        <i class="bi bi-chevron-right text-lg"></i>
                    </button>
                    <button onclick="navigateDate(0)" class="p-2 rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors text-sm" title="Today">
                        Today
                    </button>
                </div>
            </div>
            
            <!-- Filters inside calendar header -->
            <form method="GET" action="{{ route('appointments.index') }}" id="filterForm" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                    <input type="date" 
                           name="date" 
                           id="date"
                           value="{{ $selectedDate ?? request('date') }}"
                           class="input-modern"
                           onchange="document.getElementById('filterForm').submit()">
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" id="status" class="input-modern" onchange="document.getElementById('filterForm').submit()">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
            </form>
        </div>
        
        <div class="relative overflow-x-auto" id="calendarContainer" style="min-height: 2400px;">
            <!-- Toggle for collapsing empty slots -->
            <div class="absolute top-2 right-2 z-20">
                <button onclick="toggleCollapse()" 
                        id="collapseToggle"
                        class="px-3 py-1.5 text-xs bg-white hover:bg-gray-50 rounded-lg border border-gray-300 shadow-sm transition-colors">
                    <i class="bi bi-arrows-expand"></i> <span id="collapseToggleText">Expand All</span>
                </button>
            </div>
            
            <!-- Time slots (8 AM to 8 PM) -->
            @php
                $startHour = 8;
                $endHour = 20;
                $totalMinutes = ($endHour - $startHour) * 60; // 720 minutes (12 hours)
                // Minimum appointment is 30 minutes = 100px, so 1 minute = 100/30 = 3.33px
                // Total height = 720 minutes * 3.33px = 2400px
                $minutesPerPixel = 30 / 100; // 30 minutes per 100px
                $defaultHourHeight = 60 / $minutesPerPixel; // 200px per hour
            @endphp
            
            <!-- Time labels -->
            <div class="absolute left-0 top-0 bottom-0 w-20 border-r border-gray-200 bg-gray-50 z-10" id="timeLabels">
                @for($hour = $startHour; $hour <= $endHour; $hour++)
                    @php
                        $hasAppointments = isset($occupiedHours) && isset($occupiedHours[$hour]) && $occupiedHours[$hour];
                    @endphp
                    <div class="relative time-slot" 
                         data-hour="{{ $hour }}"
                         data-has-appointments="{{ $hasAppointments ? 'true' : 'false' }}"
                         style="height: {{ $defaultHourHeight }}px;">
                        <div class="absolute top-0 left-2 text-xs font-medium text-gray-600">
                            {{ $hour > 12 ? $hour - 12 : ($hour == 12 ? 12 : $hour) }}:00 {{ $hour >= 12 ? 'PM' : 'AM' }}
                        </div>
                        @if($hour < $endHour)
                            <div class="absolute left-2 text-xs text-gray-400 half-hour-label" 
                                 style="top: {{ $defaultHourHeight / 2 }}px; margin-top: -8px;">
                                {{ $hour > 12 ? $hour - 12 : ($hour == 12 ? 12 : $hour) }}:30
                            </div>
                        @endif
                    </div>
                @endfor
            </div>
            
            <!-- Appointments area -->
            <div class="ml-20 relative" id="appointmentsArea" style="min-height: 2400px;">
                <!-- Hour lines -->
                @for($hour = $startHour; $hour <= $endHour; $hour++)
                    <div class="absolute left-0 right-0 border-t border-gray-200 hour-line" 
                         data-hour="{{ $hour }}"
                         style="top: {{ (($hour - $startHour) * 60) / $minutesPerPixel }}px;">
                    </div>
                @endfor
                
                <!-- Half-hour lines -->
                @for($hour = $startHour; $hour < $endHour; $hour++)
                    <div class="absolute left-0 right-0 border-t border-dashed border-gray-100 half-hour-line" 
                         data-hour="{{ $hour }}"
                         style="top: {{ ((($hour - $startHour) * 60) + 30) / $minutesPerPixel }}px;">
                    </div>
                @endfor
                
                <!-- Appointments -->
                @foreach($preparedCalendarAppointments as $calApp)
                    @php
                        $app = $calApp['appointment'];
                        $startMinutes = $calApp['startMinutes'];
                        $duration = $calApp['duration'];
                        $column = $calApp['column'];
                        
                        // Calculate position - handle appointments outside 8 AM - 8 PM range
                        $startHourMinutes = $startHour * 60;
                        $endHourMinutes = $endHour * 60;
                        $endMinutes = $startMinutes + $duration;
                        
                        // If appointment starts before 8 AM, clamp to 0
                        $topPosition = max(0, ($startMinutes - $startHourMinutes) / $minutesPerPixel);
                        
                        // Calculate height - if appointment extends beyond 8 PM, adjust
                        $visibleStartMinutes = max($startHourMinutes, $startMinutes);
                        $visibleEndMinutes = min($endHourMinutes, $endMinutes);
                        $visibleDuration = max(0, $visibleEndMinutes - $visibleStartMinutes);
                        $height = max(100, $visibleDuration / $minutesPerPixel); // Minimum 100px height (30 minutes)
                        
                        // Column width (5 columns max) with spacing
                        $columnWidth = (100 / 5);
                        $columnSpacing = 2; // 2% spacing between columns
                        $leftPosition = $column * $columnWidth;
                        $width = $columnWidth - $columnSpacing;
                        $leftPosition = $leftPosition + ($columnSpacing / 2); // Center the spacing
                        
                        // Status colors
                        $statusColors = [
                            'pending' => 'bg-yellow-50 border-yellow-200 text-yellow-900',
                            'confirmed' => 'bg-blue-50 border-blue-200 text-blue-900',
                            'in_progress' => 'bg-purple-50 border-purple-200 text-purple-900',
                            'completed' => 'bg-green-50 border-green-200 text-green-900',
                            'cancelled' => 'bg-red-50 border-red-200 text-red-900',
                        ];
                        $colorClass = $statusColors[$app->status] ?? 'bg-gray-50 border-gray-200 text-gray-900';
                    @endphp
                    
                    <div class="absolute rounded-lg border-l-4 shadow-sm hover:shadow-md transition-all group calendar-appointment"
                         data-appointment-id="{{ $app->id }}"
                         data-start-minutes="{{ $startMinutes }}"
                         data-duration="{{ $duration }}"
                         style="top: {{ $topPosition }}px; left: {{ $leftPosition }}%; width: {{ $width }}%; height: {{ $height }}px; min-height: 125px;"
                         title="{{ $app->customer->full_name }} - {{ $app->vehicle->make }} {{ $app->vehicle->model }}">
                        <div class="h-full p-2 {{ $colorClass }} rounded-r-lg overflow-hidden flex flex-col">
                            <div class="flex-1 cursor-pointer" onclick="window.location.href='{{ route('appointments.show', $app) }}'">
                                <div class="font-semibold text-xs truncate">
                                    {{ $app->customer->full_name }}
                                </div>
                                <div class="text-xs text-gray-600 truncate mt-0.5">
                                    {{ $app->vehicle->make }} {{ $app->vehicle->model }}
                                </div>
                                <div class="text-xs font-medium mt-1">
                                    {{ $calApp['start']->format('h:i A') }} - {{ $calApp['end']->format('h:i A') }}
                                </div>
                                <div class="text-xs text-gray-500 truncate mt-0.5">
                                    {{ $app->service->name ?? $app->package->name ?? 'N/A' }}
                                </div>
                                @if($app->assignedStaff)
                                <div class="text-xs text-gray-500 mt-0.5">
                                    <i class="bi bi-person"></i> {{ $app->assignedStaff->user->name ?? 'N/A' }}
                                </div>
                                @endif
                            </div>
                            <div class="mt-2 pt-2 border-t border-gray-200 flex gap-1">
                                <a href="{{ route('appointments.show', $app) }}" 
                                   class="flex-1 text-center text-xs py-1 px-2 bg-white hover:bg-gray-100 rounded transition-colors border border-gray-300"
                                   onclick="event.stopPropagation();">
                                    <i class="bi bi-eye"></i> View
                                </a>
                                <a href="{{ route('appointments.edit', $app) }}" 
                                   class="flex-1 text-center text-xs py-1 px-2 bg-white hover:bg-blue-50 rounded transition-colors border border-gray-300 text-blue-700"
                                   onclick="event.stopPropagation();">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @else
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
                <div class="flex items-center gap-4">
                    <button onclick="navigateDate(-1)" class="p-2 rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors" title="Previous Day">
                        <i class="bi bi-chevron-left text-lg"></i>
                    </button>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">
                            <i class="bi bi-calendar3 mr-2"></i>
                            {{ \Carbon\Carbon::parse($selectedDate ?? now())->format('l, F j, Y') }}
                        </h2>
                        <p class="text-sm text-gray-500 mt-1">No appointments scheduled</p>
                    </div>
                    <button onclick="navigateDate(1)" class="p-2 rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors" title="Next Day">
                        <i class="bi bi-chevron-right text-lg"></i>
                    </button>
                    <button onclick="navigateDate(0)" class="p-2 rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors text-sm" title="Today">
                        Today
                    </button>
                </div>
            </div>
            
            <!-- Filters inside calendar header -->
            <form method="GET" action="{{ route('appointments.index') }}" id="filterFormEmpty" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="date_empty" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                    <input type="date" 
                           name="date" 
                           id="date_empty"
                           value="{{ $selectedDate ?? request('date') }}"
                           class="input-modern"
                           onchange="document.getElementById('filterFormEmpty').submit()">
                </div>
                <div>
                    <label for="status_empty" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" id="status_empty" class="input-modern" onchange="document.getElementById('filterFormEmpty').submit()">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
            </form>
        </div>
        <div class="p-12 text-center">
            <i class="bi bi-calendar-x text-5xl text-gray-300 mb-4"></i>
            <p class="text-sm text-gray-500 mb-4">No appointments found for {{ \Carbon\Carbon::parse($selectedDate ?? now())->format('F j, Y') }}</p>
            <a href="{{ route('appointments.create') }}" class="btn-primary-modern">
                <i class="bi bi-plus-circle"></i>
                Create Appointment
            </a>
        </div>
    </div>
    @endif

    @if($appointments->hasPages())
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
        {{ $appointments->links() }}
    </div>
    @endif
</div>

@push('styles')
<style>
    /* Ensure appointments don't overlap visually */
    .calendar-appointment {
        z-index: 1;
    }
    .calendar-appointment:hover {
        z-index: 10;
    }
    
    /* Smooth transitions for collapsing */
    .time-slot {
        transition: height 0.3s ease;
    }
    
    .calendar-appointment {
        transition: top 0.3s ease, height 0.3s ease;
    }
    
    .hour-line, .half-hour-line {
        transition: top 0.3s ease;
    }
</style>
@endpush

@push('scripts')
<script>
    let isCollapsed = false;
    const minutesPerPixel = 30 / 100; // 30 minutes per 100px
    const defaultHourHeight = 60 / minutesPerPixel; // 200px
    const collapsedHourHeight = 40; // 40px when collapsed
    const startHour = 8;
    const endHour = 20;

    function navigateDate(days) {
        // Get the date input (could be 'date' or 'date_empty')
        const dateInput = document.getElementById('date') || document.getElementById('date_empty');
        const form = document.getElementById('filterForm') || document.getElementById('filterFormEmpty');
        
        if (!dateInput || !form) return;
        
        const currentDate = dateInput.value || '{{ $selectedDate ?? now()->format('Y-m-d') }}';
        const date = new Date(currentDate);
        
        if (days === 0) {
            // Go to today
            const today = new Date();
            date.setFullYear(today.getFullYear(), today.getMonth(), today.getDate());
        } else {
            // Navigate forward or backward
            date.setDate(date.getDate() + days);
        }
        
        // Format date as YYYY-MM-DD
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        const formattedDate = `${year}-${month}-${day}`;
        
        // Update date input and submit form
        dateInput.value = formattedDate;
        form.submit();
    }

    function toggleCollapse() {
        isCollapsed = !isCollapsed;
        const button = document.getElementById('collapseToggle');
        const buttonText = document.getElementById('collapseToggleText');
        if (button && buttonText) {
            buttonText.textContent = isCollapsed ? 'Expand All' : 'Collapse Empty';
            button.querySelector('i').className = isCollapsed ? 'bi bi-arrows-expand' : 'bi bi-arrows-collapse';
        }
        
        collapseEmptySlots();
    }

    function collapseEmptySlots() {
        const timeSlots = document.querySelectorAll('.time-slot');
        const hourLines = document.querySelectorAll('.hour-line');
        const halfHourLines = document.querySelectorAll('.half-hour-line');
        const appointments = document.querySelectorAll('.calendar-appointment');
        
        if (!timeSlots.length) return;
        
        let cumulativeHeight = 0;
        const hourOffsets = {};
        const hourHeights = {};
        
        // Calculate new positions and heights for each hour
        for (let hour = startHour; hour <= endHour; hour++) {
            const timeSlot = document.querySelector(`.time-slot[data-hour="${hour}"]`);
            if (!timeSlot) continue;
            
            const hasAppointments = timeSlot.getAttribute('data-has-appointments') === 'true';
            
            let hourHeight;
            if (isCollapsed && !hasAppointments) {
                hourHeight = collapsedHourHeight;
            } else {
                hourHeight = defaultHourHeight;
            }
            
            hourOffsets[hour] = cumulativeHeight;
            hourHeights[hour] = hourHeight;
            
            // Update time slot height
            timeSlot.style.height = hourHeight + 'px';
            
            // Update half-hour label position
            const halfHourLabel = timeSlot.querySelector('.half-hour-label');
            if (halfHourLabel) {
                halfHourLabel.style.top = (hourHeight / 2) + 'px';
            }
            
            cumulativeHeight += hourHeight;
        }
        
        // Update hour lines
        hourLines.forEach(line => {
            const hour = parseInt(line.getAttribute('data-hour'));
            if (hourOffsets[hour] !== undefined) {
                line.style.top = hourOffsets[hour] + 'px';
            }
        });
        
        // Update half-hour lines
        halfHourLines.forEach(line => {
            const hour = parseInt(line.getAttribute('data-hour'));
            if (hourOffsets[hour] !== undefined && hourHeights[hour] !== undefined) {
                const halfHourOffset = hourOffsets[hour] + (hourHeights[hour] / 2);
                line.style.top = halfHourOffset + 'px';
            }
        });
        
        // Recalculate appointment positions
        appointments.forEach(appointment => {
            const startMinutes = parseInt(appointment.getAttribute('data-start-minutes'));
            const duration = parseInt(appointment.getAttribute('data-duration'));
            const appointmentStartHour = Math.floor(startMinutes / 60);
            const minutesInHour = startMinutes % 60;
            
            // Calculate new top position based on collapsed layout
            let newTop = 0;
            
            // Add heights of all hours before the appointment's hour
            for (let h = startHour; h < appointmentStartHour; h++) {
                if (hourHeights[h] !== undefined) {
                    newTop += hourHeights[h];
                }
            }
            
            // Add offset within the current hour
            if (hourHeights[appointmentStartHour] !== undefined) {
                const currentHourHeight = hourHeights[appointmentStartHour];
                newTop += (minutesInHour / 60) * currentHourHeight;
            }
            
            appointment.style.top = newTop + 'px';
        });
        
        // Update container height
        const container = document.getElementById('calendarContainer');
        const appointmentsArea = document.getElementById('appointmentsArea');
        if (container) {
            container.style.minHeight = cumulativeHeight + 'px';
        }
        if (appointmentsArea) {
            appointmentsArea.style.minHeight = cumulativeHeight + 'px';
        }
    }

    // Initialize on page load - default to collapsed
    document.addEventListener('DOMContentLoaded', function() {
        // Set initial state to collapsed
        isCollapsed = true;
        const button = document.getElementById('collapseToggle');
        const buttonText = document.getElementById('collapseToggleText');
        if (button && buttonText) {
            buttonText.textContent = 'Expand All';
            const icon = button.querySelector('i');
            if (icon) {
                icon.className = 'bi bi-arrows-expand';
            }
        }
        // Apply the collapsed state
        collapseEmptySlots();
    });
</script>
@endpush
@endsection
