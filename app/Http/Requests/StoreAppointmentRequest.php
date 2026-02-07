<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
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
