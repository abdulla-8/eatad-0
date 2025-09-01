<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ t($locationRequest->claim->insuranceCompany->translation_group . '.location_submitted_successfully') ?? 'Location Submitted Successfully' }}</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-8 max-w-2xl">
        <!-- Success Message -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 mx-auto mb-4 bg-green-100 rounded-full flex items-center justify-center">
                <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">
                {{ t($locationRequest->claim->insuranceCompany->translation_group . '.location_submitted_successfully') ?? 'Location Submitted Successfully!' }}
            </h1>
            <p class="text-gray-600">
                {{ t($locationRequest->claim->insuranceCompany->translation_group . '.thank_you_for_location') ?? 'Thank you for providing your vehicle location. Our towing service will be in touch soon.' }}
            </p>
        </div>

        <!-- Submitted Information -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">
                {{ t($locationRequest->claim->insuranceCompany->translation_group . '.submitted_location_details') ?? 'Submitted Location Details' }}
            </h2>
            <div class="space-y-4">
                <div class="flex justify-between items-center py-3 border-b border-gray-200">
                    <span class="font-medium text-gray-700">{{ t($locationRequest->claim->insuranceCompany->translation_group . '.city') ?? 'City' }}:</span>
                    <span class="text-gray-900">{{ $locationRequest->city }}</span>
                </div>
                <div class="flex justify-between items-center py-3 border-b border-gray-200">
                    <span class="font-medium text-gray-700">{{ t($locationRequest->claim->insuranceCompany->translation_group . '.district') ?? 'District' }}:</span>
                    <span class="text-gray-900">{{ $locationRequest->district }}</span>
                </div>
                @if($locationRequest->notes)
                <div class="flex justify-between items-center py-3 border-b border-gray-200">
                    <span class="font-medium text-gray-700">{{ t($locationRequest->claim->insuranceCompany->translation_group . '.notes') ?? 'Notes' }}:</span>
                    <span class="text-gray-900">{{ $locationRequest->notes }}</span>
                </div>
                @endif
                @if($locationRequest->location_lat && $locationRequest->location_lng)
                <div class="flex justify-between items-center py-3 border-b border-gray-200">
                    <span class="font-medium text-gray-700">{{ t($locationRequest->claim->insuranceCompany->translation_group . '.coordinates') ?? 'GPS Coordinates' }}:</span>
                    <span class="text-gray-900 text-sm">
                        {{ $locationRequest->location_lat }}, {{ $locationRequest->location_lng }}
                    </span>
                </div>
                @endif
                <div class="flex justify-between items-center py-3">
                    <span class="font-medium text-gray-700">{{ t($locationRequest->claim->insuranceCompany->translation_group . '.submitted_at') ?? 'Submitted At' }}:</span>
                    <span class="text-gray-900">{{ $locationRequest->completed_at->format('Y-m-d H:i') }}</span>
                </div>
            </div>
        </div>

        <!-- Claim Information -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">
                {{ t($locationRequest->claim->insuranceCompany->translation_group . '.claim_information') ?? 'Claim Information' }}
            </h2>
            <div class="grid md:grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="font-medium text-gray-700">{{ t($locationRequest->claim->insuranceCompany->translation_group . '.policy_number') ?? 'Policy Number' }}:</span>
                    <span class="text-gray-900">{{ $locationRequest->claim->policy_number }}</span>
                </div>
                <div>
                    <span class="font-medium text-gray-700">{{ t($locationRequest->claim->insuranceCompany->translation_group . '.vehicle_plate') ?? 'Vehicle Plate' }}:</span>
                    <span class="text-gray-900">{{ $locationRequest->claim->vehicle_plate_number ?: 'N/A' }}</span>
                </div>
                <div>
                    <span class="font-medium text-gray-700">{{ t($locationRequest->claim->insuranceCompany->translation_group . '.vehicle_brand') ?? 'Vehicle Brand' }}:</span>
                    <span class="text-gray-900">{{ $locationRequest->claim->vehicle_brand ?: 'N/A' }}</span>
                </div>
                <div>
                    <span class="font-medium text-gray-700">{{ t($locationRequest->claim->insuranceCompany->translation_group . '.vehicle_model') ?? 'Vehicle Model' }}:</span>
                    <span class="text-gray-900">{{ $locationRequest->claim->vehicle_model ?: 'N/A' }}</span>
                </div>
            </div>
        </div>

        <!-- Next Steps -->
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-blue-900 mb-3">
                {{ t($locationRequest->claim->insuranceCompany->translation_group . '.what_happens_next') ?? 'What Happens Next?' }}
            </h3>
            <ul class="text-blue-800 space-y-2">
                <li class="flex items-start">
                    <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ t($locationRequest->claim->insuranceCompany->translation_group . '.towing_service_contact') ?? 'Our towing service will contact you shortly' }}
                </li>
                <li class="flex items-start">
                    <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ t($locationRequest->claim->insuranceCompany->translation_group . '.vehicle_inspection') ?? 'Vehicle will be inspected and transported to service center' }}
                </li>
                <li class="flex items-start">
                    <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ t($locationRequest->claim->insuranceCompany->translation_group . '.claim_processing') ?? 'Your claim will be processed and updated' }}
                </li>
            </ul>
        </div>

        <!-- Contact Information -->
        <div class="bg-gray-50 rounded-xl p-6 text-center">
            <p class="text-gray-600 mb-2">
                {{ t($locationRequest->claim->insuranceCompany->translation_group . '.questions_contact') ?? 'Have questions? Contact us:' }}
            </p>
            <p class="text-gray-900 font-semibold">
                {{ $locationRequest->claim->insuranceCompany->phone }}
            </p>
        </div>
    </div>
</body>
</html> 