@extends('insurance.layouts.app')

@section('title', t('insurance_company' . '.new_claim'))

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    .user-section {
        transition: all 0.3s ease;
    }
    
    .user-section.hidden {
        display: none;
    }
    
    .form-radio:checked + span {
        color: #2563eb;
        font-weight: 600;
    }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
@endpush

@section('content')
<div class="max-w-4xl mx-auto space-y-6 ">
    <!-- Header -->
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('insurance.claims.index', $company->company_slug) }}" 
           class="w-12 h-12 rounded-xl border-2 border-gray-200 flex items-center justify-center hover:bg-gray-50 hover:border-gray-300 transition-all duration-300">
            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <div>
            <h1 class="text-4xl font-bold text-gray-900 mb-2">{{ t('insurance_company' . '.submit_new_claim') }}</h1>
            <p class="text-gray-600 text-lg">{{ t('insurance_company' . '.fill_claim_details') }}</p>
        </div>
    </div>

    <form method="POST" action="{{ route('insurance.claims.store', $company->company_slug) }}" 
          enctype="multipart/form-data" class="space-y-8" id="claimForm">
        @csrf

        <!-- Basic Information -->
        <div class="form-section">
            <div class="section-header">
                <h2 class="text-xl font-bold flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background: {{ $company->primary_color }}20;">
                        <svg class="w-6 h-6" style="color: {{ $company->primary_color }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    {{ t('insurance_company' . '.basic_information') }}
                </h2>
                <p class="text-gray-600 mt-2">{{ t('insurance_company' . '.basic_info_description') }}</p>
            </div>
            
            <div class="section-content">
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="input-group">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            {{ t('insurance_company' . '.insurance_user') }} 
                            <span class="text-red-500">*</span>
                        </label>
                        
                        <!-- User Type Toggle -->
                        <div class="mb-4">
                            <div class="flex items-center space-x-4">
                                <label class="flex items-center">
                                    <input type="radio" name="user_type" value="existing" checked 
                                           class="form-radio text-blue-600" 
                                           onchange="toggleUserType('existing')">
                                    <span class="ml-2 text-sm font-medium text-gray-700">
                                        {{ t('insurance_company' . '.existing_user') ?? 'Existing User' }}
                                    </span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="user_type" value="new" 
                                           class="form-radio text-blue-600" 
                                           onchange="toggleUserType('new')">
                                    <span class="ml-2 text-sm font-medium text-gray-700">
                                        {{ t('insurance_company' . '.new_user') ?? 'New User' }}
                                    </span>
                                </label>
                            </div>
                        </div>

                        <!-- Existing User Selection -->
                        <div id="existing-user-section" class="user-section">
                            <div class="relative">
                                <svg class="input-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <select name="insurance_user_id" class="form-input input-with-icon w-full @error('insurance_user_id') border-red-500 @enderror">
                                    <option value="">{{ t('insurance_company' . '.select_user') }}</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('insurance_user_id') == $user->id ? 'selected' : '' }}>{{ $user->full_name }} ({{ $user->phone }})</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('insurance_user_id')<p class="text-red-500 text-sm mt-2">{{ $message }}</p>@enderror
                        </div>

                        <!-- New User Registration -->
                        <div id="new-user-section" class="user-section hidden">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ t('insurance_company' . '.full_name') ?? 'Full Name' }}
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="new_user_full_name" value="{{ old('new_user_full_name') }}" 
                                           class="form-input w-full @error('new_user_full_name') border-red-500 @enderror" 
                                           placeholder="{{ t('insurance_company' . '.enter_full_name') ?? 'Enter full name' }}">
                                    @error('new_user_full_name')<p class="text-red-500 text-sm mt-2">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ t('insurance_company' . '.phone') ?? 'Phone Number' }}
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="new_user_phone" value="{{ old('new_user_phone') }}" 
                                           class="form-input w-full @error('new_user_phone') border-red-500 @enderror" 
                                           placeholder="{{ t('insurance_company' . '.enter_phone') ?? 'Enter phone number' }}">
                                    @error('new_user_phone')<p class="text-red-500 text-sm mt-2">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ t('insurance_company' . '.national_id') ?? 'National ID' }}
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="new_user_national_id" value="{{ old('new_user_national_id') }}" 
                                           class="form-input w-full @error('new_user_national_id') border-red-500 @enderror" 
                                           placeholder="{{ t('insurance_company' . '.enter_national_id') ?? 'Enter national ID' }}">
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ t('insurance_company' . '.national_id_password_note') ?? 'This will be used as the login password' }}
                                    </p>
                                    @error('new_user_national_id')<p class="text-red-500 text-sm mt-2">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ t('insurance_company' . '.policy_number') ?? 'Policy Number' }}
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="new_user_policy_number" value="{{ old('new_user_policy_number') }}" 
                                           class="form-input w-full @error('new_user_policy_number') border-red-500 @enderror" 
                                           placeholder="{{ t('insurance_company' . '.enter_policy_number') ?? 'Enter policy number' }}">
                                    @error('new_user_policy_number')<p class="text-red-500 text-sm mt-2">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="input-group">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            {{ t('insurance_company' . '.policy_number') }} 
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <svg class="input-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <input type="text" name="policy_number" value="{{ old('policy_number') }}" 
                                   class="form-input input-with-icon w-full @error('policy_number') border-red-500 @enderror" 
                                   placeholder="{{ t('insurance_company' . '.enter_policy_number') }}" required>
                        </div>
                        @error('policy_number')<p class="text-red-500 text-sm mt-2">{{ $message }}</p>@enderror
                    </div>

                    <div class="input-group">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            {{ t('insurance_company' . '.vehicle_working') }} 
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <svg class="input-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <select name="is_vehicle_working" id="is_vehicle_working" class="form-input input-with-icon w-full over-flow-hidden" required>
                                <option  value="1" {{ old('is_vehicle_working') == '1' ? 'selected' : '' }}>{{ t('insurance_company' . '.yes') }}</option>
                                <option  value="0" {{ old('is_vehicle_working') == '0' ? 'selected' : '' }}>{{ t('insurance_company' . '.no') }}</option>
                            </select>
                        </div>
                    </div>


<div class="input-group">
    <label class="block text-sm font-semibold text-gray-700 mb-3">
        {{ t('insurance_company' . '.vehicle_plate_number') }}
    </label>
    <div class="flex border border-gray-300 p-2 rounded-lg bg-white shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 w-fit {{ $isRtl ? 'flex-row-reverse' : 'flex-row-reverse' }}">
        <!-- محتوى اللوحة -->
        <div class="flex flex-col h-24 md:h-28">
            <!-- الصف الأول - العربية -->
            <div class="flex items-center justify-center gap-0 h-1/2 px-2 border-b border-black">
                <!-- خانة الحروف العربية -->
                <div class="flex gap-1 bg-gray-50 rounded p-1 h-full items-center">
                    <input type="text" class="plate-input w-6 h-6 md:w-8 md:h-8 text-center border border-gray-300 rounded text-sm md:text-base font-bold bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:bg-blue-50 outline-none transition-all duration-300 transform focus:scale-110" maxlength="1" placeholder="ر" oninput="handleArabicInput(this)" id="plate_char_ar1">
                    <input type="text" class="plate-input w-6 h-6 md:w-8 md:h-8 text-center border border-gray-300 rounded text-sm md:text-base font-bold bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:bg-blue-50 outline-none transition-all duration-300 transform focus:scale-110" maxlength="1" placeholder="ج" oninput="handleArabicInput(this)" id="plate_char_ar2">
                    <input type="text" class="plate-input w-6 h-6 md:w-8 md:h-8 text-center border border-gray-300 rounded text-sm md:text-base font-bold bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:bg-blue-50 outline-none transition-all duration-300 transform focus:scale-110" maxlength="1" placeholder="ب" oninput="handleArabicInput(this)" id="plate_char_ar3">
                </div>
                <!-- فاصل عمودي -->
                <div class="w-0.5 h-8 bg-black mx-1"></div>
                <!-- خانة الأرقام العربية -->
                <div class="flex gap-1 bg-gray-50 rounded p-1 h-full items-center">
                    <input type="text" class="plate-input w-6 h-6 md:w-8 md:h-8 text-center border border-gray-300 rounded text-sm md:text-base font-bold bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:bg-blue-50 outline-none transition-all duration-300 transform focus:scale-110" maxlength="1" placeholder="٧" oninput="convertToArabicNum(this)" id="plate_num_ar1">
                    <input type="text" class="plate-input w-6 h-6 md:w-8 md:h-8 text-center border border-gray-300 rounded text-sm md:text-base font-bold bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:bg-blue-50 outline-none transition-all duration-300 transform focus:scale-110" maxlength="1" placeholder="٩" oninput="convertToArabicNum(this)" id="plate_num_ar2">
                    <input type="text" class="plate-input w-6 h-6 md:w-8 md:h-8 text-center border border-gray-300 rounded text-sm md:text-base font-bold bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:bg-blue-50 outline-none transition-all duration-300 transform focus:scale-110" maxlength="1" placeholder="٢" oninput="convertToArabicNum(this)" id="plate_num_ar3">
                    <input type="text" class="plate-input w-6 h-6 md:w-8 md:h-8 text-center border border-gray-300 rounded text-sm md:text-base font-bold bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:bg-blue-50 outline-none transition-all duration-300 transform focus:scale-110" maxlength="1" placeholder="١" oninput="convertToArabicNum(this)" id="plate_num_ar4">
                </div>
            </div>
            
            <!-- الصف الثاني - الإنجليزية -->
            <div class="flex items-center justify-center gap-0 h-1/2 px-2">
                <!-- خانة الحروف الإنجليزية -->
                <div class="flex gap-1 bg-gray-50 rounded p-1 h-full items-center">
                    <input type="text" class="plate-input w-6 h-6 md:w-8 md:h-8 text-center border border-gray-300 rounded text-sm md:text-base font-bold bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:bg-blue-50 outline-none transition-all duration-300 transform focus:scale-110" maxlength="1" placeholder="D" oninput="handleEnglishInput(this)" id="plate_char_en1">
                    <input type="text" class="plate-input w-6 h-6 md:w-8 md:h-8 text-center border border-gray-300 rounded text-sm md:text-base font-bold bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:bg-blue-50 outline-none transition-all duration-300 transform focus:scale-110" maxlength="1" placeholder="B" oninput="handleEnglishInput(this)" id="plate_char_en2">
                    <input type="text" class="plate-input w-6 h-6 md:w-8 md:h-8 text-center border border-gray-300 rounded text-sm md:text-base font-bold bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:bg-blue-50 outline-none transition-all duration-300 transform focus:scale-110" maxlength="1" placeholder="R" oninput="handleEnglishInput(this)" id="plate_char_en3">
                </div>
                <!-- فاصل عمودي -->
                <div class="w-0.5 h-8 bg-black mx-1"></div>
                <!-- خانة الأرقام الإنجليزية -->
                <div class="flex gap-1 bg-gray-50 rounded p-1 h-full items-center">
                    <input type="text" class="plate-input w-6 h-6 md:w-8 md:h-8 text-center border border-gray-300 rounded text-sm md:text-base font-bold bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:bg-blue-50 outline-none transition-all duration-300 transform focus:scale-110" maxlength="1" placeholder="7" oninput="convertToEnglishNum(this)" id="plate_num_en1">
                    <input type="text" class="plate-input w-6 h-6 md:w-8 md:h-8 text-center border border-gray-300 rounded text-sm md:text-base font-bold bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:bg-blue-50 outline-none transition-all duration-300 transform focus:scale-110" maxlength="1" placeholder="9" oninput="convertToEnglishNum(this)" id="plate_num_en2">
                    <input type="text" class="plate-input w-6 h-6 md:w-8 md:h-8 text-center border border-gray-300 rounded text-sm md:text-base font-bold bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:bg-blue-50 outline-none transition-all duration-300 transform focus:scale-110" maxlength="1" placeholder="2" oninput="convertToEnglishNum(this)" id="plate_num_en3">
                    <input type="text" class="plate-input w-6 h-6 md:w-8 md:h-8 text-center border border-gray-300 rounded text-sm md:text-base font-bold bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:bg-blue-50 outline-none transition-all duration-300 transform focus:scale-110" maxlength="1" placeholder="1" oninput="convertToEnglishNum(this)" id="plate_num_en4">
                </div>
            </div>
        </div>
        
        <!-- قسم العلم السعودي -->
        <div class="flex flex-col items-center justify-center w-12 md:w-16 text-gray-700 border-r rtl:border-r-0 rtl:border-l border-black px-1 py-2 h-24 md:h-28">
            <div class="flex items-center mb-1">
                <span class="text-xs md:text-sm">⚔️</span>
                <span class="text-xs">🌴</span>
            </div>
            <div class="text-xs font-bold mb-1">السعودية</div>
            <div class="flex flex-col items-center text-xs font-bold">
                <div>K</div>
                <div>S</div>
                <div>A</div>
            </div>
            <div class="text-xs mt-1">●</div>
        </div>
    </div>
    
    <!-- حقل مخفي يجمع القيم عند الإرسال -->
    <input type="hidden" name="vehicle_plate_number" id="plate_full">
</div>




                    <div class="input-group">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            {{ t('insurance_company' . '.chassis_number') }}
                        </label>
                        <div class="relative">
                            <svg class="input-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v6a2 2 0 002 2h2m5 0h2a2 2 0 002-2V7a2 2 0 00-2-2h-2m-5 4h4"></path>
                            </svg>
                            <input type="text" name="chassis_number" value="{{ old('chassis_number') }}" 
                                   class="form-input input-with-icon w-full"
                                   placeholder="{{ t('insurance_company' . '.enter_chassis_number') }}">
                        </div>
                    </div>
             
                    <div class="input-group">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            {{ t('insurance_company' . '.vehicle_brand') }}
                        </label>
                        <div class="relative">
                            <svg class="input-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <input type="text" name="vehicle_brand" value="{{ old('vehicle_brand') }}"
                                   class="form-input input-with-icon w-full"
                                   placeholder="{{ t('insurance_company' . '.enter_vehicle_brand') }}">
                        </div>
                    </div>

                    <div class="input-group">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            {{ t('insurance_company' . '.vehicle_type') }}
                        </label>
                        <div class="relative">
                            <svg class="input-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            <input type="text" name="vehicle_type" value="{{ old('vehicle_type') }}"
                                   class="form-input input-with-icon w-full"
                                   placeholder="{{ t('insurance_company' . '.enter_vehicle_type') }}">
                        </div>
                    </div>

                    <div class="input-group md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            {{ t('insurance_company' . '.vehicle_model') }}
                        </label>
                        <div class="relative">
                            <svg class="input-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                            </svg>
                            <input type="text" name="vehicle_model" value="{{ old('vehicle_model') }}"
                                   class="form-input input-with-icon w-full"
                                   placeholder="{{ t('insurance_company' . '.enter_vehicle_model') }}">
                        </div>
                    </div>
                </div>

                @error('vehicle_info')
                    <div class="info-card bg-red-50 border-red-200 text-red-700 mt-4">
                        <p class="text-sm">{{ $message }}</p>
                    </div>
                @enderror

                <div class="input-group mt-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">
                        {{ t('insurance_company' . '.repair_receipt') }} 
                        <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <svg class="input-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v6a2 2 0 002 2h2m5 0h2a2 2 0 002-2V7a2 2 0 00-2-2h-2m-5 4h4"></path>
                        </svg>
                        <select name="repair_receipt_ready" class="form-input input-with-icon w-full" required>
                            <option value="1" {{ old('repair_receipt_ready') == '1' ? 'selected' : '' }}>{{ t('insurance_company' . '.ready_now') }}</option>
                            <option value="0" {{ old('repair_receipt_ready') == '0' ? 'selected' : '' }}>{{ t('insurance_company' . '.will_add_later') }}</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Vehicle Location -->
        <div class="form-section" id="vehicle-location-section">
            <div class="section-header">
                <h2 class="text-xl font-bold flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background: {{ $company->primary_color }}20;">
                        <svg class="w-6 h-6" style="color: {{ $company->primary_color }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    {{ t('insurance_company' . '.vehicle_location') }}
                    <span class="text-red-500 text-sm" id="location-required-indicator">*</span>
                </h2>
                <p class="text-gray-600 mt-2">{{ t('insurance_company' . '.location_description') }}</p>
            </div>
            
            <div class="section-content">
                <div class="input-group mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">
                        {{ t('insurance_company' . '.location_description') }}
                        <span class="text-red-500" id="location-text-required">*</span>
                    </label>
                    <textarea name="vehicle_location" id="vehicle_location" rows="4"
                        class="form-input w-full"
                        placeholder="{{ t('insurance_company' . '.describe_vehicle_location') }}">{{ old('vehicle_location') }}</textarea>
                </div>

                <div class="grid md:grid-cols-2 gap-4 mb-6">
                    <input type="hidden" name="vehicle_location_lat" id="lat">
                    <input type="hidden" name="vehicle_location_lng" id="lng">
                    
                    <button type="button" onclick="getLocation()" 
                            class="btn-primary text-white flex items-center justify-center gap-3 w-full">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        {{ t('insurance_company' . '.use_current_location') }}
                    </button>
                    
                    <button type="button" onclick="openMap()" 
                            class="bg-green-500 hover:bg-green-600 text-white flex items-center justify-center gap-3 w-full border-2 border-green-500 rounded-xl p-3 font-semibold transition-all duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                        </svg>
                        {{ t('insurance_company' . '.pick_on_map') }}
                    </button>
                </div>

                <div id="map" style="height: 350px; display: none;" class="border-2 border-gray-200 rounded-xl"></div>
                <div id="location-info" class="hidden info-card bg-green-50 border-green-200 text-green-700 mt-4">
                    <p class="text-sm"></p>
                </div>
                
                <div id="location-error" class="hidden info-card bg-red-50 border-red-200 text-red-700 mt-4">
                    <p class="text-sm">{{ t('insurance_company' . '.location_required_when_not_working') }}</p>
                </div>
            </div>
        </div>

        <!-- Required Documents -->
        <div class="form-section">
            <div class="section-header">
                <h2 class="text-xl font-bold flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background: {{ $company->primary_color }}20;">
                        <svg class="w-6 h-6" style="color: {{ $company->primary_color }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                        </svg>
                    </div>
                    {{ t('insurance_company' . '.required_documents') }}
                </h2>
                <p class="text-gray-600 mt-2">{{ t('insurance_company' . '.upload_required_files') }}</p>
            </div>
            
            <div class="section-content">
                <div class="grid md:grid-cols-2 gap-6">
                    <!-- Policy Image -->
                    <div class="input-group">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            {{ t('insurance_company' . '.policy_image') }} 
                            <span class="text-gray-500 text-xs">({{ t('insurance_company' . '.optional') }})</span>
                        </label>
                        <div class="file-input-wrapper">
                            <div class="file-input-display" onclick="document.getElementById('policy_image').click()">
                                <svg class="w-8 h-8 mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <p class="text-gray-600 font-medium">{{ t('insurance_company' . '.click_to_upload') }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ t('insurance_company' . '.file_types_supported') }}</p>
                            </div>
                            <input type="file" id="policy_image" name="policy_image[]" multiple accept="image/*,.pdf" 
                                   class="file-input-hidden" onchange="handleFileSelect(this, 'policy_image_list')">
                        </div>
                        <div id="policy_image_list" class="file-list" style="display: none;"></div>
                    </div>

                    <!-- Registration Form -->
                    <div class="input-group">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            {{ t('insurance_company' . '.registration_form') }}
                            <span class="text-yellow-600 text-xs">({{ t('insurance_company' . '.conditional') }})</span>
                        </label>
                        <div class="file-input-wrapper">
                            <div class="file-input-display" onclick="document.getElementById('registration_form').click()">
                                <svg class="w-8 h-8 mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="text-gray-600 font-medium">{{ t('insurance_company' . '.click_to_upload') }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ t('insurance_company' . '.required_if_no_plate_chassis') }}</p>
                            </div>
                            <input type="file" id="registration_form" name="registration_form[]" multiple accept="image/*,.pdf" 
                                   class="file-input-hidden" onchange="handleFileSelect(this, 'registration_form_list')">
                        </div>
                        <div id="registration_form_list" class="file-list" style="display: none;"></div>
                    </div>

                    <!-- Damage Report -->
                    <div class="input-group">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            {{ t('insurance_company' . '.damage_report') }} 
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="file-input-wrapper">
                            <div class="file-input-display" onclick="document.getElementById('damage_report').click()">
                                <svg class="w-8 h-8 mb-2 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.94-.833-2.664 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                                <p class="text-gray-600 font-medium">{{ t('insurance_company' . '.click_to_upload') }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ t('insurance_company' . '.report_required') }}</p>
                            </div>
                            <input type="file" id="damage_report" name="damage_report[]" multiple accept="image/*,.pdf" 
                                   class="file-input-hidden @error('damage_report.*') border-red-500 @enderror" 
                                   required onchange="handleFileSelect(this, 'damage_report_list')">
                        </div>
                        <div id="damage_report_list" class="file-list" style="display: none;"></div>
                        @error('damage_report.*')<p class="text-red-500 text-sm mt-2">{{ $message }}</p>@enderror
                    </div>

                    <!-- Estimation Report -->
                    <div class="input-group">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            {{ t('insurance_company' . '.estimation_report') }} 
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="file-input-wrapper">
                            <div class="file-input-display" onclick="document.getElementById('estimation_report').click()">
                                <svg class="w-8 h-8 mb-2 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-gray-600 font-medium">{{ t('insurance_company' . '.click_to_upload') }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ t('insurance_company' . '.cost_estimation_required') }}</p>
                            </div>
                            <input type="file" id="estimation_report" name="estimation_report[]" multiple accept="image/*,.pdf" 
                                   class="file-input-hidden @error('estimation_report.*') border-red-500 @enderror" 
                                   required onchange="handleFileSelect(this, 'estimation_report_list')">
                        </div>
                        <div id="estimation_report_list" class="file-list" style="display: none;"></div>
                        @error('estimation_report.*')<p class="text-red-500 text-sm mt-2">{{ $message }}</p>@enderror
                    </div>
                </div>

                <!-- Repair Receipt Section -->
                <div id="repair-receipt-section" style="display: none;" class="mt-6">
                    <div class="input-group">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">{{ t('insurance_company' . '.repair_receipt') }}</label>
                        <div class="file-input-wrapper">
                            <div class="file-input-display" onclick="document.getElementById('repair_receipt').click()">
                                <svg class="w-8 h-8 mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="text-gray-600 font-medium">{{ t('insurance_company' . '.click_to_upload') }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ t('insurance_company' . '.repair_receipt_description') }}</p>
                            </div>
                            <input type="file" id="repair_receipt" name="repair_receipt[]" multiple accept="image/*,.pdf" 
                                   class="file-input-hidden" onchange="handleFileSelect(this, 'repair_receipt_list')">
                        </div>
                        <div id="repair_receipt_list" class="file-list" style="display: none;"></div>
                    </div>
                </div>

                <!-- File Upload Info -->
                <div class="info-card bg-blue-50 border-blue-200 text-blue-700 mt-6">
                    <div class="flex items-start gap-3">
                        <svg class="w-6 h-6 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <h4 class="font-semibold mb-2">{{ t('insurance_company' . '.file_upload_tips') }}</h4>
                            <ul class="text-sm space-y-1">
                                <li>• {{ t('insurance_company' . '.max_file_size_5mb') }}</li>
                                <li>• {{ t('insurance_company' . '.supported_formats') }}: JPEG, PNG, PDF</li>
                                <li>• {{ t('insurance_company' . '.multiple_files_allowed') }}</li>
                                <li>• {{ t('insurance_company' . '.clear_readable_images') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Notes -->
        <div class="form-section">
            <div class="section-header">
                <h2 class="text-xl font-bold flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background: {{ $company->primary_color }}20;">
                        <svg class="w-6 h-6" style="color: {{ $company->primary_color }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </div>
                    {{ t('insurance_company' . '.additional_notes') }}
                </h2>
                <p class="text-gray-600 mt-2">{{ t('insurance_company' . '.notes_optional_description') }}</p>
            </div>
            <div class="section-content">
                <textarea name="notes" rows="5" 
                          class="form-input w-full"
                          placeholder="{{ t('insurance_company' . '.any_additional_information') }}">{{ old('notes') }}</textarea>
            </div>
        </div>

        <!-- Summary & Submit Actions -->
        <div class="form-section">
            <div class="section-header">
                <h2 class="text-xl font-bold flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background: {{ $company->primary_color }}20;">
                        <svg class="w-6 h-6" style="color: {{ $company->primary_color }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    {{ t('insurance_company' . '.review_submit') }}
                </h2>
                <p class="text-gray-600 mt-2">{{ t('insurance_company' . '.review_before_submit') }}</p>
            </div>
            <div class="section-content">
                <!-- Important Notes -->
                <div class="info-card bg-yellow-50 border-yellow-200 text-yellow-800 mb-6">
                    <div class="flex items-start gap-3">
                        <svg class="w-6 h-6 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.94-.833-2.664 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        <div>
                            <h4 class="font-semibold mb-2">{{ t('insurance_company' . '.important_notes') }}</h4>
                            <ul class="text-sm space-y-1">
                                <li>• {{ t('insurance_company' . '.ensure_all_info_correct') }}</li>
                                <li>• {{ t('insurance_company' . '.required_files_uploaded') }}</li>
                                <li>• {{ t('insurance_company' . '.claim_review_time') }}</li>
                                <li>• {{ t('insurance_company' . '.notification_updates') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4">
                    <button type="submit" 
                            class="btn-primary text-white flex items-center justify-center gap-3 flex-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                        {{ t('insurance_company' . '.submit_claim') }}
                    </button>
                    <a href="{{ route('insurance.claims.index', $company->company_slug) }}" 
                       class="btn-secondary text-white flex items-center justify-center gap-3 flex-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        {{ t('insurance_company' . '.cancel') }}
                    </a>
                </div>
                
                @if($errors->any())
                    <div class="info-card bg-red-50 border-red-200 text-red-700 mt-4">
                        <h4 class="font-semibold mb-2">{{ t('insurance_company' . '.please_fix_errors') }}</h4>
                        <ul class="text-sm space-y-1">
                            @foreach($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </form>

    <style>
    .form-section {
        background: white;
        border-radius: 16px;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        border: 1px solid #e5e7eb;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .form-section:hover {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    
    .form-input {
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        padding: 12px 16px;
        transition: all 0.3s ease;
        background: #fafafa;
    }
    
    .form-input:focus {
        background: white;
        border-color: {{ $company->primary_color }};
        box-shadow: 0 0 0 3px {{ $company->primary_color }}20;
        outline: none;
    }
    
    .form-input:hover {
        border-color: #d1d5db;
        background: white;
    }
    
    .section-header {
        background: linear-gradient(135deg, {{ $company->primary_color }}10, {{ $company->primary_color }}05);
        border-bottom: 1px solid #e5e7eb;
        padding: 24px;
    }
    
    .section-content {
        padding: 24px;
    }
    
    .input-group {
        position: relative;
    }
    
    .input-icon {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        color: #6b7280;
        pointer-events: none;
        z-index: 10;
    }
    
    /* RTL Support for Icons */
    [dir="rtl"] .input-icon {
        right: 12px;
        left: auto;
    }
    
    [dir="ltr"] .input-icon,
    html:not([dir="rtl"]) .input-icon {
        left: 12px;
        right: auto;
    }
    
    .input-with-icon {
        padding-left: 44px;
        padding-right: 16px;
    }
    
    [dir="rtl"] .input-with-icon {
        padding-right: 44px;
        padding-left: 16px;
    }
    
    /* File Input Styling */
    .file-input-wrapper {
        position: relative;
        overflow: hidden;
        display: inline-block;
        width: 100%;
    }
    
    .file-input-display {
        border: 2px dashed #d1d5db;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        transition: all 0.3s ease;
        background: #fafafa;
        cursor: pointer;
        min-height: 120px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    
    .file-input-display:hover {
        border-color: {{ $company->primary_color }};
        background: {{ $company->primary_color }}05;
    }
    
    .file-input-display.has-files {
        border-color: {{ $company->primary_color }};
        background: {{ $company->primary_color }}10;
    }
    
    .file-input-hidden {
        position: absolute;
        left: -9999px;
        opacity: 0;
    }
    
    .file-list {
        margin-top: 12px;
        padding: 12px;
        background: #f8fafc;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
    }
    
    .file-item {
        display: flex;
        align-items: center;
        justify-content: between;
        padding: 8px 12px;
        background: white;
        border-radius: 6px;
        margin-bottom: 8px;
        border: 1px solid #e2e8f0;
        transition: all 0.2s ease;
    }
    
    .file-item:hover {
        border-color: {{ $company->primary_color }};
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .file-item:last-child {
        margin-bottom: 0;
    }
    
    .file-info {
        flex: 1;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .file-name {
        font-weight: 500;
        color: #374151;
        font-size: 14px;
    }
    
    .file-size {
        color: #6b7280;
        font-size: 12px;
    }
    
    .file-remove {
        background: #ef4444;
        color: white;
        border: none;
        border-radius: 4px;
        padding: 4px 8px;
        font-size: 12px;
        cursor: pointer;
        transition: background 0.2s ease;
    }
    
    .file-remove:hover {
        background: #dc2626;
    }
    
    .info-card {
        border-radius: 12px;
        padding: 16px;
        border: 1px solid;
    }
    
    .btn-primary {
        background: {{ $company->primary_color }};
        border: 2px solid {{ $company->primary_color }};
        border-radius: 12px;
        padding: 12px 24px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        background: {{ $company->primary_color }}dd;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px {{ $company->primary_color }}40;
    }
    
    .btn-secondary {
        background: #6b7280;
        border: 2px solid #6b7280;
        border-radius: 12px;
        padding: 12px 24px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-secondary:hover {
        background: #4b5563;
        transform: translateY(-1px);
    }
</style>
<style>
.file-list {
    margin-top: 1rem;
}

.file-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.75rem;
    background-color: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 0.5rem;
    margin-bottom: 0.5rem;
}

.file-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex: 1;
    min-width: 0;
}

.file-name {
    font-weight: 500;
    color: #374151;
    font-size: 0.875rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.file-size {
    color: #6b7280;
    font-size: 0.75rem;
}

.file-remove {
    background-color: #ef4444;
    color: white;
    border: none;
    padding: 0.25rem 0.75rem;
    border-radius: 0.375rem;
    font-size: 0.75rem;
    cursor: pointer;
    transition: background-color 0.2s;
}

.file-remove:hover {
    background-color: #dc2626;
}

.file-input-display.has-files {
    border-color: #10b981;
    background-color: #f0fdf4;
}
</style>
</div>


<script>
// تحويل الرقم الإنجليزي إلى عربي مع الانتقال التلقائي
function convertToArabicNum(input) {
    let val = input.value;
    
    // تحويل الرقم الإنجليزي إلى عربي
    if (/[0-9]/.test(val)) {
        const arabicNums = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
        input.value = arabicNums[parseInt(val, 10)];
    }
    // إذا لم يكن رقم صحيح، امسح الحقل
    else if (!/[٠-٩]/.test(val)) {
        input.value = '';
    }
    
    // إضافة التأثيرات البصرية والانتقال التلقائي
    applyVisualEffects(input);
    moveToNextInput(input);
    updatePlateNumber();
}

// تحويل الرقم العربي إلى إنجليزي مع الانتقال التلقائي
function convertToEnglishNum(input) {
    let val = input.value;
    
    // تحويل الرقم العربي إلى إنجليزي
    if (/[٠-٩]/.test(val)) {
        const englishNums = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        const arabicNums = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
        input.value = englishNums[arabicNums.indexOf(val)];
    }
    // إذا لم يكن رقم صحيح، امسح الحقل
    else if (!/[0-9]/.test(val)) {
        input.value = '';
    }
    
    // إضافة التأثيرات البصرية والانتقال التلقائي
    applyVisualEffects(input);
    moveToNextInput(input);
    updatePlateNumber();
}

// معالجة الحروف العربية
function handleArabicInput(input) {
    // السماح بالحروف العربية فقط
    input.value = input.value.replace(/[^ء-ي]/g, '');
    
    // إضافة التأثيرات البصرية والانتقال التلقائي
    applyVisualEffects(input);
    moveToNextInput(input);
    updatePlateNumber();
}

// معالجة الحروف الإنجليزية
function handleEnglishInput(input) {
    // السماح بالحروف الإنجليزية فقط وتحويلها إلى أحرف كبيرة
    input.value = input.value.replace(/[^A-Za-z]/g, '').toUpperCase();
    
    // إضافة التأثيرات البصرية والانتقال التلقائي
    applyVisualEffects(input);
    moveToNextInput(input);
    updatePlateNumber();
}

// إضافة التأثيرات البصرية
function applyVisualEffects(input) {
    if (input.value) {
        input.classList.add('border-green-500', 'bg-green-50');
        input.classList.remove('border-gray-300');
    } else {
        input.classList.remove('border-green-500', 'bg-green-50');
        input.classList.add('border-gray-300');
    }
}

// الانتقال التلقائي للحقل التالي مع تأثير بصري
function moveToNextInput(currentInput) {
    if (currentInput.value) {
        setTimeout(() => {
            const allInputs = document.querySelectorAll('.plate-input');
            const currentIndex = Array.from(allInputs).indexOf(currentInput);
            
            if (currentIndex < allInputs.length - 1) {
                // تأثير انتقال سلس
                currentInput.classList.add('scale-105', 'ring-2', 'ring-green-400');
                setTimeout(() => {
                    currentInput.classList.remove('scale-105', 'ring-2', 'ring-green-400');
                    allInputs[currentIndex + 1].focus();
                }, 150);
            }
        }, 100);
    }
}

// تجميع القيم وتحديث الحقل المخفي
function updatePlateNumber() {
    const ar1 = document.getElementById('plate_char_ar1').value;
    const ar2 = document.getElementById('plate_char_ar2').value;
    const ar3 = document.getElementById('plate_char_ar3').value;
    const arNum1 = document.getElementById('plate_num_ar1').value;
    const arNum2 = document.getElementById('plate_num_ar2').value;
    const arNum3 = document.getElementById('plate_num_ar3').value;
    const arNum4 = document.getElementById('plate_num_ar4').value;
    const en1 = document.getElementById('plate_char_en1').value;
    const en2 = document.getElementById('plate_char_en2').value;
    const en3 = document.getElementById('plate_char_en3').value;
    const enNum1 = document.getElementById('plate_num_en1').value;
    const enNum2 = document.getElementById('plate_num_en2').value;
    const enNum3 = document.getElementById('plate_num_en3').value;
    const enNum4 = document.getElementById('plate_num_en4').value;

    document.getElementById('plate_full').value = 
        ar1 + ar2 + ar3 + arNum1 + arNum2 + arNum3 + arNum4 + 
        en1 + en2 + en3 + enNum1 + enNum2 + enNum3 + enNum4;
}

// إضافة مستمعي الأحداث
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('.plate-input');
    
    inputs.forEach((input, index) => {
        // معالجة Backspace للرجوع للحقل السابق
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace' && this.value === '' && index > 0) {
                inputs[index - 1].focus();
            }
        });
        
        // تأثير hover
        input.addEventListener('mouseenter', function() {
            this.classList.add('shadow-md', 'shadow-blue-200');
        });
        
        input.addEventListener('mouseleave', function() {
            this.classList.remove('shadow-md', 'shadow-blue-200');
        });
        
        // تأثير focus
        input.addEventListener('focus', function() {
            this.classList.add('ring-2', 'ring-blue-400');
        });
        
        input.addEventListener('blur', function() {
            this.classList.remove('ring-2', 'ring-blue-400');
        });
    });
});

// إضافة مستمع حدث للنموذج عند الإرسال
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('claimForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            updatePlateNumber();
        });
    }
})
</script>
<script>
let map, marker;
let fileData = {}; // لحفظ بيانات الملفات

// File handling functions
function handleFileSelect(input, listId) {
    const files = Array.from(input.files);
    const listContainer = document.getElementById(listId);
    const displayArea = input.parentNode.querySelector('.file-input-display');
    
    // حفظ الملفات في البيانات
    fileData[input.name] = files;
    
    if (files.length > 0) {
        // تحديث مظهر منطقة الرفع
        displayArea.classList.add('has-files');
        displayArea.innerHTML = `
            <svg class="w-8 h-8 mb-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-green-600 font-medium">${files.length} {{ t('insurance_company' . '.files_selected') }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ t('insurance_company' . '.click_to_change') }}</p>
        `;
        
        // عرض قائمة الملفات
        listContainer.style.display = 'block';
        listContainer.innerHTML = files.map((file, index) => `
            <div class="file-item">
                <div class="file-info">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <div>
                        <div class="file-name">${file.name}</div>
                        <div class="file-size">${formatFileSize(file.size)}</div>
                    </div>
                </div>
                <button type="button" class="file-remove" onclick="removeFile('${input.name}', ${index}, '${listId}')">
                    {{ t('insurance_company' . '.remove') }}
                </button>
            </div>
        `).join('');
    } else {
        // إعادة تعيين المظهر
        resetFileDisplay(displayArea, listContainer);
    }
}

function removeFile(inputName, fileIndex, listId) {
    const input = document.querySelector(`[name="${inputName}"]`);
    const files = Array.from(fileData[inputName]);
    
    // إزالة الملف من المصفوفة
    files.splice(fileIndex, 1);
    fileData[inputName] = files;
    
    // تحديث input
    const dt = new DataTransfer();
    files.forEach(file => dt.items.add(file));
    input.files = dt.files;
    
    // تحديث العرض
    handleFileSelect(input, listId);
}

function resetFileDisplay(displayArea, listContainer) {
    displayArea.classList.remove('has-files');
    displayArea.innerHTML = `
        <svg class="w-8 h-8 mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
        </svg>
        <p class="text-gray-600 font-medium">{{ t('insurance_company' . '.click_to_upload') }}</p>
        <p class="text-xs text-gray-500 mt-1">{{ t('insurance_company' . '.file_types_supported') }}</p>
    `;
    listContainer.style.display = 'none';
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// Location functions
function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            document.getElementById('lat').value = position.coords.latitude;
            document.getElementById('lng').value = position.coords.longitude;

            const locationInfo = document.getElementById('location-info');
            locationInfo.innerHTML = `<p class="text-sm">${'{{ t('insurance_company' . ".location_set") }}'}: ${position.coords.latitude.toFixed(6)}, ${position.coords.longitude.toFixed(6)}</p>`;
            locationInfo.classList.remove('hidden');
            
            document.getElementById('location-error').classList.add('hidden');
        }, function(error) {
            alert('{{ t('insurance_company' . ".location_error") }}');
        });
    } else {
        alert('{{ t('insurance_company' . ".geolocation_not_supported") }}');
    }
}

function openMap() {
    document.getElementById('map').style.display = 'block';
    if (!map) {
        map = L.map('map').setView([30.0444, 31.2357], 10);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        map.on('click', function(e) {
            if (marker) map.removeLayer(marker);
            marker = L.marker([e.latlng.lat, e.latlng.lng]).addTo(map);
            document.getElementById('lat').value = e.latlng.lat;
            document.getElementById('lng').value = e.latlng.lng;

            const locationInfo = document.getElementById('location-info');
            locationInfo.innerHTML = `<p class="text-sm">${'{{ t('insurance_company' . ".location_set") }}'}: ${e.latlng.lat.toFixed(6)}, ${e.latlng.lng.toFixed(6)}</p>`;
            locationInfo.classList.remove('hidden');
            
            document.getElementById('location-error').classList.add('hidden');
        });
    }
    setTimeout(() => map.invalidateSize(), 100);
}

// Show/hide repair receipt section
document.querySelector('[name="repair_receipt_ready"]').addEventListener('change', function() {
    document.getElementById('repair-receipt-section').style.display =
        this.value === '1' ? 'block' : 'none';
});

// Trigger initial state
document.querySelector('[name="repair_receipt_ready"]').dispatchEvent(new Event('change'));

// إظهار/إخفاء قسم اللوكيشن حسب حالة السيارة
function toggleLocationSection() {
    var working = document.getElementById('is_vehicle_working').value;
    var locationSection = document.getElementById('vehicle-location-section');
    var locationRequired = document.getElementById('location-required-indicator');
    var locationTextRequired = document.getElementById('location-text-required');
    var vehicleLocationField = document.querySelector('[name="vehicle_location"]');
    
    if (working === '0') {
        locationSection.style.display = '';
        locationRequired.style.display = 'inline';
        locationTextRequired.style.display = 'inline';
        vehicleLocationField.required = true;
    } else {
        locationSection.style.display = 'none';
        locationRequired.style.display = 'none';
        locationTextRequired.style.display = 'none';
        vehicleLocationField.required = false;
        
        document.querySelector('[name="vehicle_location"]').value = '';
        document.getElementById('lat').value = '';
        document.getElementById('lng').value = '';
        const locationInfo = document.getElementById('location-info');
        if (locationInfo) locationInfo.classList.add('hidden');
        document.getElementById('location-error').classList.add('hidden');
    }
}

document.getElementById('is_vehicle_working').addEventListener('change', toggleLocationSection);
window.addEventListener('DOMContentLoaded', toggleLocationSection);

// Form validation before submit
document.getElementById('claimForm').addEventListener('submit', function(e) {
    const plateNumber = document.querySelector('[name="vehicle_plate_number"]').value.trim();
    const chassisNumber = document.querySelector('[name="chassis_number"]').value.trim();
    const isWorking = document.getElementById('is_vehicle_working').value;
    const vehicleLocation = document.querySelector('[name="vehicle_location"]').value.trim();
    const lat = document.getElementById('lat').value;
    const lng = document.getElementById('lng').value;

    if (!plateNumber && !chassisNumber) {
        e.preventDefault();
        alert('{{ t('insurance_company' . ".vehicle_info_required") }}');
        if (!plateNumber) {
            document.querySelector('[name="vehicle_plate_number"]').focus();
        } else {
            document.querySelector('[name="vehicle_plate_number"]').focus();
        }
        return false;
    }

    if (isWorking === '0') {
        if (!vehicleLocation) {
            e.preventDefault();
            document.getElementById('location-error').classList.remove('hidden');
            document.querySelector('[name="vehicle_location"]').focus();
            document.querySelector('[name="vehicle_location"]').scrollIntoView({ behavior: 'smooth', block: 'center' });
            alert('{{ t('insurance_company' . ".location_required_when_not_working") }}');
            return false;
        }
        
        if (!lat || !lng) {
            const confirmSubmit = confirm('{{ t('insurance_company' . ".location_coordinates_missing_confirm") }}');
            if (!confirmSubmit) {
                e.preventDefault();
                return false;
            }
        }
    }
    
    document.getElementById('location-error').classList.add('hidden');
});

document.querySelector('[name="vehicle_location"]').addEventListener('input', function() {
    if (this.value.trim()) {
        document.getElementById('location-error').classList.add('hidden');
    }
});

// Toggle between existing and new user sections
function toggleUserType(userType) {
    const existingSection = document.getElementById('existing-user-section');
    const newSection = document.getElementById('new-user-section');
    const existingSelect = document.querySelector('[name="insurance_user_id"]');
    const newUserFields = document.querySelectorAll('[name^="new_user_"]');
    
    if (userType === 'existing') {
        existingSection.classList.remove('hidden');
        newSection.classList.add('hidden');
        existingSelect.required = true;
        newUserFields.forEach(field => field.required = false);
    } else {
        existingSection.classList.add('hidden');
        newSection.classList.remove('hidden');
        existingSelect.required = false;
        newUserFields.forEach(field => field.required = true);
    }
}

// Initialize form validation for user type toggle
document.addEventListener('DOMContentLoaded', function() {
    // Set initial state
    toggleUserType('existing');
    
    // Add validation for user type selection
    document.getElementById('claimForm').addEventListener('submit', function(e) {
        const userType = document.querySelector('input[name="user_type"]:checked').value;
        
        if (userType === 'existing') {
            const userId = document.querySelector('[name="insurance_user_id"]').value;
            if (!userId) {
                e.preventDefault();
                alert('{{ t('insurance_company' . ".please_select_user") ?? "Please select an existing user" }}');
                document.querySelector('[name="insurance_user_id"]').focus();
                return false;
            }
        } else if (userType === 'new') {
            const fullName = document.querySelector('[name="new_user_full_name"]').value.trim();
            const phone = document.querySelector('[name="new_user_phone"]').value.trim();
            const nationalId = document.querySelector('[name="new_user_national_id"]').value.trim();
            const policyNumber = document.querySelector('[name="new_user_policy_number"]').value.trim();
            
            if (!fullName || !phone || !nationalId || !policyNumber) {
                e.preventDefault();
                alert('{{ t('insurance_company' . ".please_fill_all_new_user_fields") ?? "Please fill all new user fields" }}');
                return false;
            }
        }
    });
});
</script>
@endsection
