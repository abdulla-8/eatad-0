<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClaimRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'policy_number' => 'required|string|max:100',
            'vehicle_plate_number' => 'nullable|string|max:50',
            'chassis_number' => 'nullable|string|max:100',
            'vehicle_location' => 'required|string|max:500',
            'vehicle_location_lat' => 'nullable|numeric|between:-90,90',
            'vehicle_location_lng' => 'nullable|numeric|between:-180,180',
            'is_vehicle_working' => 'required|boolean',
            'repair_receipt_ready' => 'required|boolean',
            'notes' => 'nullable|string|max:1000',
        ];

        // File validation rules
        $fileRules = [
            'policy_image.*' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'registration_form.*' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'repair_receipt.*' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'damage_report.*' => 'required|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'estimation_report.*' => 'required|file|mimes:jpeg,png,jpg,pdf|max:5120',
        ];

        // If updating a rejected claim, make damage_report and estimation_report optional
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $fileRules['damage_report.*'] = 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120';
            $fileRules['estimation_report.*'] = 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120';
        }

        return array_merge($rules, $fileRules);
    }

    public function messages()
    {
        return [
            'policy_number.required' => 'Policy number is required',
            'vehicle_location.required' => 'Vehicle location is required',
            'is_vehicle_working.required' => 'Please specify if the vehicle is working',
            'repair_receipt_ready.required' => 'Please specify if repair receipt is ready',
            'damage_report.*.required' => 'Damage report (Najm report) is required',
            'damage_report.*.mimes' => 'Damage report must be an image or PDF file',
            'damage_report.*.max' => 'Damage report file size must not exceed 5MB',
            'estimation_report.*.required' => 'Estimation report is required',
            'estimation_report.*.mimes' => 'Estimation report must be an image or PDF file',
            'estimation_report.*.max' => 'Estimation report file size must not exceed 5MB',
            '*.file' => 'The uploaded file is invalid',
            '*.mimes' => 'File must be an image (JPEG, PNG, JPG) or PDF',
            '*.max' => 'File size must not exceed 5MB',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Check that either vehicle plate number or chassis number is provided
            if (!$this->vehicle_plate_number && !$this->chassis_number) {
                $validator->errors()->add('vehicle_info', 'Either vehicle plate number or chassis number is required');
            }
        });
    }

    protected function prepareForValidation()
    {
        // Convert checkbox values to boolean
        $this->merge([
            'is_vehicle_working' => $this->boolean('is_vehicle_working'),
            'repair_receipt_ready' => $this->boolean('repair_receipt_ready'),
        ]);
    }
}