<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ t('auth.register') }} - {{ t('dealer.dashboard') }}</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="{{ $fontLink }}" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'cairo': ['Cairo', 'sans-serif'],
                        'inter': ['Inter', 'sans-serif'],
                    },
                    colors: {
                        gold: { 400: '#FFDD57', 500: '#FFDD57', 600: '#e6c64d' },
                        dark: { 900: '#191919', 800: '#2d2d2d', 700: '#3a3a3a' }
                    }
                }
            }
        }
    </script>
</head>
<body class="min-h-screen bg-gradient-to-br from-dark-900 via-dark-800 to-dark-900 {{ $isRtl ? 'font-cairo' : 'font-inter' }}">
    
    <!-- Language Switcher -->
    <div class="fixed top-8 {{ $isRtl ? 'left-8' : 'right-8' }} z-50">
        <x-language-switcher class="bg-dark-800/80 backdrop-blur-md border border-gold-500/30 text-white hover:bg-dark-700/80 hover:border-gold-500/50 transition-all duration-300 shadow-lg" />
    </div>

    <!-- Main Content -->
    <div class="min-h-screen flex items-center justify-center p-6">
        <div class="w-full max-w-2xl">
            
            <!-- Logo & Brand -->
            <div class="text-center mb-8">
                <div class="relative mx-auto w-20 h-20 mb-4">
                    <div class="bg-gradient-to-br from-dark-800 to-dark-900 rounded-xl p-3 border border-gold-500/30 shadow-xl">
                        <svg class="w-full h-full text-gold-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                </div>
                <h1 class="text-xl font-bold bg-gradient-to-r from-gold-400 to-gold-600 bg-clip-text text-transparent mb-1">
                    {{ t('auth.create_account') }}
                </h1>
                <p class="text-gray-400 text-sm">{{ t('dealer.dashboard') }}</p>
            </div>

            <!-- Register Card -->
            <div class="bg-dark-800/80 backdrop-blur-xl rounded-2xl p-6 border border-gold-500/20 shadow-2xl">
                
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-500/20 border border-red-500/30 rounded-lg">
                        @foreach ($errors->all() as $error)
                            <p class="text-red-300 text-sm">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
                
                <form method="POST" action="{{ route('dealer.register') }}" class="space-y-4">
                    @csrf
                    
                    <!-- Basic Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Legal Name -->
                        <div class="md:col-span-2">
                            <label for="legal_name" class="block text-white text-sm font-medium mb-1">
                                {{ t('auth.legal_name') }} <span class="text-red-400">*</span>
                            </label>
                            <input type="text" 
                                   id="legal_name" 
                                   name="legal_name" 
                                   value="{{ old('legal_name') }}"
                                   class="w-full px-3 py-2 bg-dark-900/50 border border-gold-500/20 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gold-500/50 focus:border-gold-500/50 transition-all text-sm"
                                   placeholder="{{ t('auth.legal_name_placeholder', 'Company Legal Name') }}"
                                   required>
                        </div>
                        
                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-white text-sm font-medium mb-1">
                                {{ t('auth.phone') }} <span class="text-red-400">*</span>
                            </label>
                            <input type="text" 
                                   id="phone" 
                                   name="phone" 
                                   value="{{ old('phone') }}"
                                   class="w-full px-3 py-2 bg-dark-900/50 border border-gold-500/20 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gold-500/50 focus:border-gold-500/50 transition-all text-sm"
                                   placeholder="01234567890"
                                   required>
                        </div>
                        
                        <!-- Commercial Register -->
                        <div>
                            <label for="commercial_register" class="block text-white text-sm font-medium mb-1">
                                {{ t('auth.commercial_register') }} <span class="text-red-400">*</span>
                            </label>
                            <input type="text" 
                                   id="commercial_register" 
                                   name="commercial_register" 
                                   value="{{ old('commercial_register') }}"
                                   class="w-full px-3 py-2 bg-dark-900/50 border border-gold-500/20 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gold-500/50 focus:border-gold-500/50 transition-all text-sm"
                                   placeholder="CR123456789"
                                   required>
                        </div>
                        
                        <!-- Tax Number -->
                        <div>
                            <label for="tax_number" class="block text-white text-sm font-medium mb-1">
                                {{ t('auth.tax_number', 'Tax Number') }} <span class="text-gray-400 text-xs">({{ t('auth.optional', 'Optional') }})</span>
                            </label>
                            <input type="text" 
                                   id="tax_number" 
                                   name="tax_number" 
                                   value="{{ old('tax_number') }}"
                                   class="w-full px-3 py-2 bg-dark-900/50 border border-gold-500/20 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gold-500/50 focus:border-gold-500/50 transition-all text-sm"
                                   placeholder="TX123456789">
                        </div>
                        
                        <!-- Specialization -->
                        <div>
                            <label for="specialization_id" class="block text-white text-sm font-medium mb-1">
                                {{ t('auth.specialization', 'Specialization') }} <span class="text-gray-400 text-xs">({{ t('auth.optional', 'Optional') }})</span>
                            </label>
                            <select name="specialization_id" 
                                    id="specialization_id"
                                    class="w-full px-3 py-2 bg-dark-900/50 border border-gold-500/20 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-gold-500/50 focus:border-gold-500/50 transition-all text-sm">
                                <option value="">{{ t('auth.select_brand', 'Select Brand') }}</option>
                                @foreach($specializations as $specialization)
                                    <option value="{{ $specialization->id }}" 
                                            {{ old('specialization_id') == $specialization->id ? 'selected' : '' }}>
                                        {{ $specialization->display_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <!-- Shop Address -->
                    <div>
                        <label for="shop_address" class="block text-white text-sm font-medium mb-1">
                            {{ t('auth.shop_address', 'Shop Address') }} <span class="text-gray-400 text-xs">({{ t('auth.optional', 'Optional') }})</span>
                        </label>
                        <textarea id="shop_address" 
                                  name="shop_address" 
                                  rows="2"
                                  class="w-full px-3 py-2 bg-dark-900/50 border border-gold-500/20 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gold-500/50 focus:border-gold-500/50 transition-all text-sm"
                                  placeholder="{{ t('auth.shop_address_placeholder', 'Full shop address') }}">{{ old('shop_address') }}</textarea>
                    </div>
                    
                    <!-- Dealer Type -->
                    <div>
                        <label class="block text-white text-sm font-medium mb-2">{{ t('auth.business_type', 'Business Type') }}</label>
                        <div class="flex items-center space-x-4 {{ $isRtl ? 'space-x-reverse' : '' }}">
                            <label class="inline-flex items-center">
                                <input type="radio" 
                                       name="is_scrapyard_owner" 
                                       value="0" 
                                       {{ old('is_scrapyard_owner', '0') == '0' ? 'checked' : '' }}
                                       class="form-radio text-gold-500 focus:ring-gold-500">
                                <span class="{{ $isRtl ? 'mr-2' : 'ml-2' }} text-sm text-gray-300">{{ t('auth.parts_dealer', 'Parts Dealer') }}</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" 
                                       name="is_scrapyard_owner" 
                                       value="1" 
                                       {{ old('is_scrapyard_owner') == '1' ? 'checked' : '' }}
                                       class="form-radio text-gold-500 focus:ring-gold-500">
                                <span class="{{ $isRtl ? 'mr-2' : 'ml-2' }} text-sm text-gray-300">{{ t('auth.scrapyard_owner', 'Scrapyard Owner') }}</span>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Password Fields -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-white text-sm font-medium mb-1">
                                {{ t('auth.password') }} <span class="text-red-400">*</span>
                            </label>
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   class="w-full px-3 py-2 bg-dark-900/50 border border-gold-500/20 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gold-500/50 focus:border-gold-500/50 transition-all text-sm"
                                   placeholder="••••••••"
                                   required>
                        </div>
                        
                        <div>
                            <label for="password_confirmation" class="block text-white text-sm font-medium mb-1">
                                {{ t('auth.confirm_password') }} <span class="text-red-400">*</span>
                            </label>
                            <input type="password" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   class="w-full px-3 py-2 bg-dark-900/50 border border-gold-500/20 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gold-500/50 focus:border-gold-500/50 transition-all text-sm"
                                   placeholder="••••••••"
                                   required>
                        </div>
                    </div>
                    
                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-gold-500 to-gold-400 hover:from-gold-400 hover:to-gold-500 text-dark-900 font-bold py-3 px-6 rounded-lg transition-all duration-300 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-gold-500/50">
                        <span class="flex items-center justify-center">
                            <svg class="w-5 h-5 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                            {{ t('auth.create_account') }}
                        </span>
                    </button>
                    
                    <!-- Login Link -->
                    <div class="text-center">
                        <p class="text-gray-400 text-sm">
                            {{ t('auth.already_have_account') }}
                            <a href="{{ route('dealer.login') }}" class="text-gold-400 hover:text-gold-300 font-medium">
                                {{ t('auth.login') }}
                            </a>
                        </p>
                    </div>
                </form>
            </div>

            <!-- Footer -->
            <div class="text-center text-gray-500 text-sm mt-6">
                <p>&copy; {{ date('Y') }} {{ t('site.name', 'Etad') }}. {{ t('site.all_rights_reserved', 'All rights reserved') }}</p>
            </div>
        </div>
    </div>
</body>
</html>