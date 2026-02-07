<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => 'required|exists:customers,id',
            'registration_number' => 'required|string|max:50|unique:vehicles,registration_number',
            'make' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'color' => 'nullable|string|max:50',
            'vehicle_type' => 'required|in:sedan,suv,hatchback,coupe,convertible,truck,van,motorcycle,other',
            'fuel_type' => 'required|in:petrol,diesel,electric,hybrid,cng,lpg',
            'mileage' => 'nullable|integer|min:0',
            'vin_number' => 'nullable|string|max:50',
            'insurance_number' => 'nullable|string|max:100',
            'insurance_expiry' => 'nullable|date',
            'notes' => 'nullable|string',
        ];
    }
}
