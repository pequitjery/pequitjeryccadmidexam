<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreColdTripRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'truck_plate' => ['required', 'string', 'max:32'],
            'driver_name' => ['required', 'string', 'max:255'],
            'cargo_type' => ['required', 'string', 'max:255'],
            'departure_hub' => ['required', 'string', 'max:255'],
            'destination_hub' => ['required', 'string', 'max:255', 'different:departure_hub'],
            'required_temp_c' => ['required', 'numeric', 'between:-100,100'],
            'min_logged_temp' => ['nullable', 'numeric', 'between:-100,100'],
            'max_logged_temp' => ['nullable', 'numeric', 'between:-100,100'],
            'dispatch_time' => ['required', 'date'],
            'arrival_time' => ['nullable', 'date', 'after_or_equal:dispatch_time'],
        ];
    }
}
