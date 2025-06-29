@extends('admin.layouts.app')

@section('title', isset($towServiceCompany) ? t('admin.edit_tow_service_company') : t('admin.add_tow_service_company'))

@section('content')

<div class="flex justify-between items-center mb-4">
    <div>
        <h1 class="text-xl font-bold text-gray-900">
            {{ isset($towServiceCompany) ? t('admin.edit_tow_service_company') : t('admin.add_tow_service_company') }}
        </h1>
        <nav class="text-xs text-gray-600">
            <a href="{{ route('admin.dashboard') }}">{{ t('admin.dashboard') }}</a> > 
            <a href="{{ route('admin.users.tow-service-companies.index') }}">{{ t('admin.tow_service_companies') }}</a> > 
            <span class="text-gold-600">{{ isset($towServiceCompany) ? t('admin.edit') : t('admin.add_new') }}</span>
        </nav>
    </div>
    <a href="{{ route('admin.users.tow-service-companies.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-2 rounded text-sm">
        ← {{ t('admin.back_to_list') }}
    </a>
</div>

<div class="bg-white rounded-lg shadow-sm border">
    <div class="bg-gold-500 text-dark-900 px-4 py-3 rounded-t-lg">
        <h2 class="font-semibold">{{ t('admin.company_details') }}</h2>
    </div>
    
    <form method="POST" 
          action="{{ isset($towServiceCompany) ? route('admin.users.tow-service-companies.update', $towServiceCompany) : route('admin.users.tow-service-companies.store') }}" 
          class="p-4" enctype="multipart/form-data">
        @csrf
        @if(isset($towServiceCompany)) @method('PUT') @endif
        
        <!-- Basic Info -->
        <h3 class="font-semibold text-gray-900 mb-3 pb-2 border-b">{{ t('admin.basic_information') }}</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
            <!-- Legal Name -->
            <div class="md:col-span-2">
                <label for="legal_name" class="block font-medium text-gray-700 mb-1">
                    {{ t('admin.legal_name') }} <span class="text-red-500">*</span>
                </label>
                <input type="text" id="legal_name" name="legal_name" 
                       value="{{ old('legal_name', $towServiceCompany->legal_name ?? '') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-gold-500 @error('legal_name') border-red-500 @enderror"
                       required>
                @error('legal_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Phone -->
            <div>
                <label for="phone" class="block font-medium text-gray-700 mb-1">
                    {{ t('admin.primary_phone') }} <span class="text-red-500">*</span>
                </label>
                <input type="text" id="phone" name="phone" 
                       value="{{ old('phone', $towServiceCompany->phone ?? '') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-gold-500 @error('phone') border-red-500 @enderror"
                       required>
                @error('phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            
            <!-- Password -->
            <div>
                <label for="password" class="block font-medium text-gray-700 mb-1">
                    {{ t('admin.password') }} @if(!isset($towServiceCompany))<span class="text-red-500">*</span>@endif
                </label>
                <input type="password" id="password" name="password" 
                       class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-gold-500 @error('password') border-red-500 @enderror"
                       {{ !isset($towServiceCompany) ? 'required' : '' }}>
                @if(isset($towServiceCompany))<p class="text-xs text-gray-500 mt-1">{{ t('admin.leave_empty_keep_current') }}</p>@endif
                @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            
            <!-- Commercial Register -->
            <div>
                <label for="commercial_register" class="block font-medium text-gray-700 mb-1">
                    {{ t('admin.commercial_register') }} <span class="text-red-500">*</span>
                </label>
                <input type="text" id="commercial_register" name="commercial_register" 
                       value="{{ old('commercial_register', $towServiceCompany->commercial_register ?? '') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-gold-500 @error('commercial_register') border-red-500 @enderror"
                       required>
                @error('commercial_register')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            
            <!-- Tax Number -->
            <div>
                <label for="tax_number" class="block font-medium text-gray-700 mb-1">
                    {{ t('admin.tax_number') }} <span class="text-gray-400">({{ t('admin.optional') }})</span>
                </label>
                <input type="text" id="tax_number" name="tax_number" 
                       value="{{ old('tax_number', $towServiceCompany->tax_number ?? '') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-gold-500">
                @error('tax_number')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            
            <!-- Daily Capacity -->
            <div>
                <label for="daily_capacity" class="block font-medium text-gray-700 mb-1">
                    {{ t('admin.daily_capacity') }} <span class="text-gray-400">({{ t('admin.optional') }})</span>
                </label>
                <input type="number" id="daily_capacity" name="daily_capacity" min="1"
                       value="{{ old('daily_capacity', $towServiceCompany->daily_capacity ?? '') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-gold-500">
                @error('daily_capacity')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            
            <!-- Delegate Number -->
            <div>
                <label for="delegate_number" class="block font-medium text-gray-700 mb-1">
                    {{ t('admin.delegate_number') }} <span class="text-gray-400">({{ t('admin.optional') }})</span>
                </label>
                <input type="text" id="delegate_number" name="delegate_number" 
                       value="{{ old('delegate_number', $towServiceCompany->delegate_number ?? '') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-gold-500">
                @error('delegate_number')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <!-- Company Logo -->
        <h3 class="font-semibold text-gray-900 mb-3 pb-2 border-b">{{ t('admin.company_branding') }}</h3>
        <div class="mb-4">
            <label for="company_logo" class="block font-medium text-gray-700 mb-1">
                {{ t('admin.company_logo') }} <span class="text-gray-400">({{ t('admin.optional') }})</span>
            </label>
            @if(isset($towServiceCompany) && $towServiceCompany->company_logo)
                <div class="mb-2">
                    <img src="{{ $towServiceCompany->logo_url }}" alt="Current Logo" class="w-16 h-16 object-contain border rounded">
                    <p class="text-xs text-gray-500">{{ t('admin.current_logo') }}</p>
                </div>
            @endif
            <input type="file" id="company_logo" name="company_logo" accept="image/*"
                   class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-gold-500">
            <p class="text-xs text-gray-500 mt-1">PNG, JPG, GIF up to 2MB</p>
            @error('company_logo')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        
        <!-- Address -->
        <div class="mb-4">
            <label for="office_address" class="block font-medium text-gray-700 mb-1">{{ t('admin.office_address') }}</label>
            <textarea id="office_address" name="office_address" rows="2"
                      class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-gold-500"
                      placeholder="{{ t('admin.office_address_placeholder') }}">{{ old('office_address', $towServiceCompany->office_address ?? '') }}</textarea>
            @error('office_address')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        
        <!-- Additional Phones -->
        <h3 class="font-semibold text-gray-900 mb-3 pb-2 border-b">{{ t('admin.additional_phone_numbers') }}</h3>
        <div id="phoneNumbers" class="mb-3">
            @if(isset($towServiceCompany) && $towServiceCompany->additionalPhones->count() > 0)
                @foreach($towServiceCompany->additionalPhones->where('is_primary', false) as $additionalPhone)
                    <div class="phone-group grid grid-cols-1 md:grid-cols-3 gap-2 mb-2">
                        <input type="text" name="additional_phones[]" value="{{ $additionalPhone->phone }}"
                               class="px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-gold-500" placeholder="{{ t('admin.phone_number') }}">
                        <input type="text" name="phone_labels[]" value="{{ $additionalPhone->label }}"
                               class="px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-gold-500" placeholder="{{ t('admin.phone_label_placeholder') }}">
                        <button type="button" onclick="removePhone(this)" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs">{{ t('admin.delete') }}</button>
                    </div>
                @endforeach
            @else
                <div class="phone-group grid grid-cols-1 md:grid-cols-3 gap-2 mb-2">
                    <input type="text" name="additional_phones[]" class="px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-gold-500" placeholder="{{ t('admin.phone_number') }}">
                    <input type="text" name="phone_labels[]" class="px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-gold-500" placeholder="{{ t('admin.phone_label_placeholder') }}">
                    <button type="button" onclick="removePhone(this)" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs">{{ t('admin.delete') }}</button>
                </div>
            @endif
        </div>
        <button type="button" onclick="addPhone()" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs mb-4">
            + {{ t('admin.add_phone_number') }}
        </button>
        
        <!-- Status Settings -->
        <h3 class="font-semibold text-gray-900 mb-3 pb-2 border-b">{{ t('admin.status_settings') }}</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <label class="flex items-center">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $towServiceCompany->is_active ?? 1) ? 'checked' : '' }}
                       class="w-4 h-4 text-gold-500 bg-gray-100 border-gray-300 rounded focus:ring-gold-500">
                <span class="{{ $isRtl ? 'mr-3' : 'ml-3' }} text-sm">{{ t('admin.active_account') }}</span>
            </label>
            
            <label class="flex items-center">
                <input type="hidden" name="is_approved" value="0">
                <input type="checkbox" name="is_approved" value="1" {{ old('is_approved', $towServiceCompany->is_approved ?? 0) ? 'checked' : '' }}
                       class="w-4 h-4 text-gold-500 bg-gray-100 border-gray-300 rounded focus:ring-gold-500">
                <span class="{{ $isRtl ? 'mr-3' : 'ml-3' }} text-sm">{{ t('admin.approved_account') }}</span>
            </label>
        </div>
        
        <!-- Actions -->
        <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-200">
            <a href="{{ route('admin.users.tow-service-companies.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded text-sm">{{ t('admin.cancel') }}</a>
            <button type="submit" class="bg-gold-500 hover:bg-gold-600 text-dark-900 px-4 py-2 rounded text-sm">
                ✓ {{ isset($towServiceCompany) ? t('admin.update') : t('admin.save') }}
            </button>
        </div>
    </form>
</div>

<script>
function addPhone() {
    const container = document.getElementById('phoneNumbers');
    const div = document.createElement('div');
    div.className = 'phone-group grid grid-cols-1 md:grid-cols-3 gap-2 mb-2';
    div.innerHTML = `
        <input type="text" name="additional_phones[]" class="px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-gold-500" placeholder="{{ t('admin.phone_number') }}">
        <input type="text" name="phone_labels[]" class="px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-gold-500" placeholder="{{ t('admin.phone_label_placeholder') }}">
        <button type="button" onclick="removePhone(this)" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs">{{ t('admin.delete') }}</button>
    `;
    container.appendChild(div);
}

function removePhone(btn) {
    btn.closest('.phone-group').remove();
}
</script>

@endsection