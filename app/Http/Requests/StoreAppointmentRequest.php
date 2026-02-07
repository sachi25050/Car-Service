<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        // Normalize time to H:i (strip seconds / convert 12-hour inputs)
        $time = $this->input('appointment_time');
        if (is_string($time) && $time !== '') {
            $time = trim($time);

            // "HH:MM:SS" -> "HH:MM"
            if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $time)) {
                $time = substr($time, 0, 5);
            }

            // "hh:mm AM/PM" -> "HH:MM"
            if (preg_match('/^\d{1,2}:\d{2}\s?(AM|PM)$/i', $time)) {
                try {
                    $time = \Carbon\Carbon::createFromFormat('g:i A', strtoupper(str_replace('  ', ' ', $time)))->format('H:i');
                } catch (\Exception $e) {
                    // leave as-is; validation will handle error
                }
            }

            $this->merge(['appointment_time' => $time]);
        }
    }

    public function rules(): array
    {
        return [
            'customer_id' => 'required|exists:customers,id',
            'vehicle_id' => 'required|exists:vehicles,id',
            'service_id' => 'nullable|exists:services,id',
            'package_id' => 'nullable|exists:service_packages,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required|date_format:H:i',
            'estimated_duration' => 'nullable|integer|min:15',
            'status' => 'nullable|in:pending,confirmed,in_progress,completed,cancelled,no_show',
            'notes' => 'nullable|string',
            'assigned_staff_id' => 'nullable|exists:staff,id',
        ];
    }

    public function messages(): array
    {
        return [
            'appointment_date.after_or_equal' => 'Appointment date must be today or in the future.',
        ];
    }
}
