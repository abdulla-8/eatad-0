{{-- resources/views/insurance/users/edit.blade.php --}}
@extends('insurance.layouts.app')

@section('title', t($company->translation_group . '.edit_user'))

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header with Breadcrumb -->
        <div class="mb-8">
            <nav class="flex items-center space-x-2 rtl:space-x-reverse text-sm text-gray-500 mb-4">
                <a href="{{ route('insurance.dashboard', $company->company_slug) }}" class="hover:text-gray-700 transition-colors">
                    {{ t($company->translation_group . '.dashboard') }}
                </a>
                <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <a href="{{ route('insurance.users.index', $company->company_slug) }}" class="hover:text-gray-700 transition-colors">
                    {{ t($company->translation_group . '.users_management') }}
                </a>
                <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <span class="text-gray-900 font-medium">{{ t($company->translation_group . '.edit_user') }}</span>
            </nav>
            
            <div class="flex items-center gap-4 rtl:gap-4">
                <a href="{{ route('insurance.users.index', $company->company_slug) }}" 
                   class="group p-3 rounded-xl bg-white shadow-sm border hover:shadow-md transition-all duration-200 hover:border-gray-300">
                    <svg class="w-5 h-5 text-gray-600 group-hover:text-gray-800 transition-colors rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">{{ t($company->translation_group . '.edit_user') }}</h1>
                    <p class="text-lg text-gray-600">{{ t($company->translation_group . '.edit_user_data') }} <span class="font-semibold" style="color: {{ $company->primary_color }};">{{ $user->full_name }}</span></p>
                </div>
            </div>
        </div>

        <!-- Main Form Card -->
        <div class="bg-white rounded-2xl shadow-xl border-0 overflow-hidden">
            <!-- Form Header -->
            <div class="px-8 py-6 border-b border-gray-100" style="background: linear-gradient(135deg, {{ $company->primary_color }}15 0%, {{ $company->primary_color }}05 100%);">
                <div class="flex items-center gap-4 rtl:gap-4">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center text-white font-bold text-lg"
                         style="background: {{ $company->primary_color }};">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">{{ t($company->translation_group . '.update_user_information') }}</h2>
                        <p class="text-sm text-gray-600">{{ t($company->translation_group . '.modify_user_details') }}</p>
                    </div>
                </div>
            </div>

            <!-- Form Body -->
            <form method="POST" action="{{ route('insurance.users.update', [$company->company_slug, $user->id]) }}" class="p-8">
                @csrf
                @method('PUT')
                
                <!-- Personal Information Section -->
                <div class="mb-8">
                    <div class="flex items-center gap-3 rtl:gap-3 mb-6">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center text-white text-sm font-bold"
                             style="background: {{ $company->primary_color }};">
                            1
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ t($company->translation_group . '.personal_information') }}</h3>
                    </div>
                    
                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="group">
                            <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2 rtl:gap-2">
                                <svg class="w-4 h-4" style="color: {{ $company->primary_color }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                {{ t($company->translation_group . '.full_name') }} 
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="text" name="full_name" value="{{ old('full_name', $user->full_name) }}" required
                                    class="w-full border-2 border-gray-200 rounded-xl focus:ring-4 focus:border-transparent px-4 py-3.5 text-gray-900 placeholder-gray-400 transition-all duration-200 @error('full_name') border-red-300 focus:ring-red-100 @else focus:ring-blue-100 @enderror ltr:pr-12 rtl:pl-12"
                                    style="focus:border-color: {{ $company->primary_color }};"
                                    placeholder="{{ t($company->translation_group . '.enter_full_name') }}">
                                <div class="absolute inset-y-0 ltr:right-3 rtl:left-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                            </div>
                            @error('full_name')
                                <div class="flex items-center gap-2 rtl:gap-2 mt-2 text-red-600 text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="group">
                            <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2 rtl:gap-2">
                                <svg class="w-4 h-4" style="color: {{ $company->primary_color }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                {{ t($company->translation_group . '.phone_number') }} 
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" required
                                    class="w-full border-2 border-gray-200 rounded-xl focus:ring-4 focus:border-transparent px-4 py-3.5 text-gray-900 placeholder-gray-400 transition-all duration-200 @error('phone') border-red-300 focus:ring-red-100 @else focus:ring-blue-100 @enderror ltr:pr-12 rtl:pl-12"
                                    style="focus:border-color: {{ $company->primary_color }};"
                                    placeholder="{{ t($company->translation_group . '.enter_phone_number') }}">
                                <div class="absolute inset-y-0 ltr:right-3 rtl:left-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                </div>
                            </div>
                            @error('phone')
                                <div class="flex items-center gap-2 rtl:gap-2 mt-2 text-red-600 text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Official Information Section -->
                <div class="mb-8">
                    <div class="flex items-center gap-3 rtl:gap-3 mb-6">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center text-white text-sm font-bold"
                             style="background: {{ $company->primary_color }};">
                            2
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ t($company->translation_group . '.official_information') }}</h3>
                    </div>
                    
                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="group">
                            <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2 rtl:gap-2">
                                <svg class="w-4 h-4" style="color: {{ $company->primary_color }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V4a2 2 0 114 0v2m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                                </svg>
                                {{ t($company->translation_group . '.national_id') }} 
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="text" name="national_id" value="{{ old('national_id', $user->national_id) }}" required maxlength="14"
                                    class="w-full border-2 border-gray-200 rounded-xl focus:ring-4 focus:border-transparent px-4 py-3.5 text-gray-900 placeholder-gray-400 transition-all duration-200 @error('national_id') border-red-300 focus:ring-red-100 @else focus:ring-blue-100 @enderror ltr:pr-12 rtl:pl-12"
                                    style="focus:border-color: {{ $company->primary_color }};"
                                    placeholder="{{ t($company->translation_group . '.enter_national_id') }}">
                                <div class="absolute inset-y-0 ltr:right-3 rtl:left-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V4a2 2 0 114 0v2m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                                    </svg>
                                </div>
                            </div>
                            @error('national_id')
                                <div class="flex items-center gap-2 rtl:gap-2 mt-2 text-red-600 text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="group">
                            <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2 rtl:gap-2">
                                <svg class="w-4 h-4" style="color: {{ $company->primary_color }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                {{ t($company->translation_group . '.policy_number') }} 
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="text" name="policy_number" value="{{ old('policy_number', $user->policy_number) }}" required
                                    class="w-full border-2 border-gray-200 rounded-xl focus:ring-4 focus:border-transparent px-4 py-3.5 text-gray-900 placeholder-gray-400 transition-all duration-200 @error('policy_number') border-red-300 focus:ring-red-100 @else focus:ring-blue-100 @enderror ltr:pr-12 rtl:pl-12"
                                    style="focus:border-color: {{ $company->primary_color }};"
                                    placeholder="{{ t($company->translation_group . '.enter_policy_number') }}">
                                <div class="absolute inset-y-0 ltr:right-3 rtl:left-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            @error('policy_number')
                                <div class="flex items-center gap-2 rtl:gap-2 mt-2 text-red-600 text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Security Information Section -->
                <div class="mb-8">
                    <div class="flex items-center gap-3 rtl:gap-3 mb-6">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center text-white text-sm font-bold"
                             style="background: {{ $company->primary_color }};">
                            3
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ t($company->translation_group . '.change_password_optional') }}</h3>
                    </div>
                    
                    <div class="bg-gray-50 rounded-xl p-6 border-2 border-dashed border-gray-200">
                        <div class="flex items-center gap-3 rtl:gap-3 mb-4">
                            <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>
                            <p class="text-sm text-gray-600">{{ t($company->translation_group . '.password_change_note') }}</p>
                        </div>
                        
                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="group">
                                <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2 rtl:gap-2">
                                    <svg class="w-4 h-4" style="color: {{ $company->primary_color }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                    {{ t($company->translation_group . '.new_password') }}
                                </label>
                                <div class="relative">
                                    <input type="password" name="password"
                                        class="w-full border-2 border-gray-200 rounded-xl focus:ring-4 focus:border-transparent px-4 py-3.5 text-gray-900 placeholder-gray-400 transition-all duration-200 @error('password') border-red-300 focus:ring-red-100 @else focus:ring-blue-100 @enderror ltr:pr-12 rtl:pl-12"
                                        style="focus:border-color: {{ $company->primary_color }};"
                                        placeholder="{{ t($company->translation_group . '.enter_new_password') }}">
                                    <div class="absolute inset-y-0 ltr:right-3 rtl:left-3 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                        </svg>
                                    </div>
                                </div>
                                @error('password')
                                    <div class="flex items-center gap-2 rtl:gap-2 mt-2 text-red-600 text-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="group">
                                <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2 rtl:gap-2">
                                    <svg class="w-4 h-4" style="color: {{ $company->primary_color }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                    {{ t($company->translation_group . '.confirm_new_password') }}
                                </label>
                                <div class="relative">
                                    <input type="password" name="password_confirmation"
                                        class="w-full border-2 border-gray-200 rounded-xl focus:ring-4 focus:border-transparent px-4 py-3.5 text-gray-900 placeholder-gray-400 transition-all duration-200 focus:ring-blue-100 ltr:pr-12 rtl:pl-12"
                                        style="focus:border-color: {{ $company->primary_color }};"
                                        placeholder="{{ t($company->translation_group . '.confirm_new_password_placeholder') }}">
                                    <div class="absolute inset-y-0 ltr:right-3 rtl:left-3 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 pt-8 border-t border-gray-100">
                    <button type="submit" 
                            class="flex-1 group relative overflow-hidden py-4 px-6 text-white rounded-xl font-semibold text-lg transition-all duration-300 hover:shadow-lg hover:scale-105 transform"
                            style="background: linear-gradient(135deg, {{ $company->primary_color }} 0%, {{ $company->secondary_color ?? $company->primary_color }} 100%);">
                        <span class="relative z-10 flex items-center justify-center gap-3 rtl:gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                            </svg>
                            {{ t($company->translation_group . '.update_data') }}
                        </span>
                        <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                    </button>
                    
                    <a href="{{ route('insurance.users.index', $company->company_slug) }}" 
                       class="flex-1 group py-4 px-6 bg-gray-100 text-gray-700 rounded-xl font-semibold text-lg hover:bg-gray-200 transition-all duration-300 hover:shadow-md text-center">
                        <span class="flex items-center justify-center gap-3 rtl:gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            {{ t($company->translation_group . '.cancel') }}
                        </span>
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Custom focus styles for inputs */
.group input:focus {
    border-color: {{ $company->primary_color }} !important;
    box-shadow: 0 0 0 4px {{ $company->primary_color }}20 !important;
}

/* Smooth animations */
.group {
    transition: all 0.2s ease-in-out;
}

.group:hover {
    transform: translateY(-1px);
}

/* Custom scrollbar for better UX */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb {
    background: {{ $company->primary_color }};
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: {{ $company->secondary_color ?? $company->primary_color }};
}

/* RTL/LTR specific styles */
[dir="rtl"] .space-x-2 > * + * {
    margin-left: 0;
    margin-right: 0.5rem;
}

[dir="ltr"] .space-x-2 > * + * {
    margin-left: 0.5rem;
    margin-right: 0;
}
</style>
@endsection
