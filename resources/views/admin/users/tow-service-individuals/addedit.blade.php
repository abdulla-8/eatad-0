@extends('admin.layouts.app')

@section('title', isset($towServiceIndividual) ? t('admin.edit_tow_service_individual') : t('admin.add_tow_service_individual'))

@section('content')

<div class="flex justify-between items-center mb-4">
    <div>
        <h1 class="text-xl font-bold text-gray-900">
            {{ isset($towServiceIndividual) ? t('admin.edit_tow_service_individual') : t('admin.add_tow_service_individual') }}
        </h1>
        <nav class="text-xs text-gray-600">
            <a href="{{ route('admin.dashboard') }}">{{ t('admin.dashboard') }}</a> > 
            <a href="{{ route('admin.users.tow-service-individuals.index') }}">{{ t('admin.tow_service_individuals') }}</a> > 
            <span class="text-gold-600">{{ isset($towServiceIndividual) ? t('admin.edit') : t('admin.add_new') }}</span>
        </nav>
    </div>
    <a href="{{ route('admin.users.tow-service-individuals.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-2 rounded text-sm">
        ← {{ t('admin.back_to_list') }}
    </a>
</div>

<div class="bg-white rounded-lg shadow-sm border">
    <div class="bg-gold-500 text-dark-900 px-4 py-3 rounded-t-lg">
        <h2 class="font-semibold">{{ t('admin.individual_details') }}</h2>
    </div>
    
    <form method="POST" 
          action="{{ isset($towServiceIndividual) ? route('admin.users.tow-service-individuals.update', $towServiceIndividual) : route('admin.users.tow-service-individuals.store') }}" 
          class="p-4" enctype="multipart/form-data">
        @csrf
        @if(isset($towServiceIndividual)) @method('PUT') @endif
        
        <!-- Personal Info -->
        <h3 class="font-semibold text-gray-900 mb-3 pb-2 border-b">{{ t('admin.personal_information') }}</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-4">
            <!-- Full Name -->
            <div>
                <label for="full_name" class="block font-medium text-gray-700 mb-1">
                    {{ t('admin.full_name') }} <span class="text-red-500">*</span>
                </label>
                <input type="text" id="full_name" name="full_name" 
                       value="{{ old('full_name', $towServiceIndividual->full_name ?? '') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-gold-500 @error('full_name') border-red-500 @enderror"
                       required>
                @error('full_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- National ID -->
            <div>
                <label for="national_id" class="block font-medium text-gray-700 mb-1">
                    {{ t('admin.national_id') }} <span class="text-red-500">*</span>
                </label>
                <input type="text" id="national_id" name="national_id" 
                       value="{{ old('national_id', $towServiceIndividual->national_id ?? '') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-gold-500 @error('national_id') border-red-500 @enderror"
                       maxlength="14" required>
                @error('national_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Phone -->
            <div>
                <label for="phone" class="block font-medium text-gray-700 mb-1">
                    {{ t('admin.phone') }} <span class="text-red-500">*</span>
                </label>
                <input type="text" id="phone" name="phone" 
                       value="{{ old('phone', $towServiceIndividual->phone ?? '') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-gold-500 @error('phone') border-red-500 @enderror"
                       required>
                @error('phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            
            <!-- Password -->
            <div>
                <label for="password" class="block font-medium text-gray-700 mb-1">
                    {{ t('admin.password') }} @if(!isset($towServiceIndividual))<span class="text-red-500">*</span>@endif
                </label>
                <input type="password" id="password" name="password" 
                       class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-gold-500 @error('password') border-red-500 @enderror"
                       {{ !isset($towServiceIndividual) ? 'required' : '' }}>
                @if(isset($towServiceIndividual))<p class="text-xs text-gray-500 mt-1">{{ t('admin.leave_empty_keep_current') }}</p>@endif
                @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <!-- Truck Info -->
        <h3 class="font-semibold text-gray-900 mb-3 pb-2 border-b">{{ t('admin.truck_information') }}</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-4">
            <!-- Truck Plate Number -->
            <div>
                <label for="tow_truck_plate_number" class="block font-medium text-gray-700 mb-1">
                    {{ t('admin.tow_truck_plate_number') }} <span class="text-red-500">*</span>
                </label>
                <input type="text" id="tow_truck_plate_number" name="tow_truck_plate_number" 
                       value="{{ old('tow_truck_plate_number', $towServiceIndividual->tow_truck_plate_number ?? '') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-gold-500 @error('tow_truck_plate_number') border-red-500 @enderror"
                       required>
                @error('tow_truck_plate_number')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Truck Form Upload -->
            <div>
                <label for="tow_truck_form" class="block font-medium text-gray-700 mb-1">
                    {{ t('admin.tow_truck_form') }} <span class="text-gray-400">({{ t('admin.optional') }})</span>
                </label>
                @if(isset($towServiceIndividual) && $towServiceIndividual->tow_truck_form)
                    <div class="mb-2">
                        <a href="{{ $towServiceIndividual->tow_truck_form_url }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm">
                            {{ t('admin.view_current_form') }}
                        </a>
                    </div>
                @endif
                <input type="file" id="tow_truck_form" name="tow_truck_form" accept="image/*,application/pdf"
                       class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-gold-500">
                <p class="text-xs text-gray-500 mt-1">PNG, JPG, PDF up to 5MB</p>
                @error('tow_truck_form')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <!-- Profile Image -->
        <div class="mb-4">
            <label for="profile_image" class="block font-medium text-gray-700 mb-1">
                {{ t('admin.profile_image') }} <span class="text-gray-400">({{ t('admin.optional') }})</span>
            </label>
            @if(isset($towServiceIndividual) && $towServiceIndividual->profile_image)
                <div class="mb-2">
                    <img src="{{ $towServiceIndividual->profile_image_url }}" alt="Current Profile" class="w-16 h-16 object-cover border rounded-full">
                    <p class="text-xs text-gray-500">{{ t('admin.current_image') }}</p>
                </div>
            @endif
            <input type="file" id="profile_image" name="profile_image" accept="image/*"
                   class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-gold-500">
            <p class="text-xs text-gray-500 mt-1">PNG, JPG, GIF up to 2MB</p>
            @error('profile_image')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        
        <!-- Address -->
        <div class="mb-4">
            <label for="address" class="block font-medium text-gray-700 mb-1">{{ t('admin.address') }}</label>
            <textarea id="address" name="address" rows="2"
                      class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-gold-500"
                      placeholder="{{ t('admin.address_placeholder') }}">{{ old('address', $towServiceIndividual->address ?? '') }}</textarea>
            @error('address')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        
        <!-- Status Settings -->
        <h3 class="font-semibold text-gray-900 mb-3 pb-2 border-b">{{ t('admin.status_settings') }}</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <label class="flex items-center">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $towServiceIndividual->is_active ?? 1) ? 'checked' : '' }}
                       class="w-4 h-4 text-gold-500 bg-gray-100 border-gray-300 rounded focus:ring-gold-500">
                <span class="{{ $isRtl ? 'mr-3' : 'ml-3' }} text-sm">{{ t('admin.active_account') }}</span>
            </label>
            
            <label class="flex items-center">
                <input type="hidden" name="is_approved" value="0">
                <input type="checkbox" name="is_approved" value="1" {{ old('is_approved', $towServiceIndividual->is_approved ?? 0) ? 'checked' : '' }}
                       class="w-4 h-4 text-gold-500 bg-gray-100 border-gray-300 rounded focus:ring-gold-500">
                <span class="{{ $isRtl ? 'mr-3' : 'ml-3' }} text-sm">{{ t('admin.approved_account') }}</span>
            </label>
        </div>
        
        <!-- Actions -->
        <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-200">
            <a href="{{ route('admin.users.tow-service-individuals.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded text-sm">{{ t('admin.cancel') }}</a>
            <button type="submit" class="bg-gold-500 hover:bg-gold-600 text-dark-900 px-4 py-2 rounded text-sm">
                ✓ {{ isset($towServiceIndividual) ? t('admin.update') : t('admin.save') }}
            </button>
        </div>
    </form>
</div>

<script>
// Format national ID as user types
document.getElementById('national_id').addEventListener('input', function(e) {
    e.target.value = e.target.value.replace(/\D/g, '').substring(0, 14);
});

// Format phone as user types
document.getElementById('phone').addEventListener('input', function(e) {
    e.target.value = e.target.value.replace(/\D/g, '').substring(0, 11);
});
</script>

@endsection