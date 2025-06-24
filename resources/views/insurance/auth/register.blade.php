<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ t($company->translation_group . '.register', 'Register') }} - {{ t($company->translation_group . '.company_name', $company->legal_name) }}</title>
    
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
                        primary: '{{ $company->primary_color }}',
                        secondary: '{{ $company->secondary_color }}',
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
        <x-language-switcher class="bg-dark-800/80 backdrop-blur-md border border-primary/30 text-white hover:bg-dark-700/80 hover:border-primary/50 transition-all duration-300 shadow-lg" />
    </div>

    <!-- Main Content -->
    <div class="min-h-screen flex items-center justify-center p-6">
        <div class="w-full max-w-2xl">
            
            <!-- Logo & Brand -->
            <div class="text-center mb-8">
                <div class="relative mx-auto w-20 h-20 mb-4">
                    @if($company->company_logo)
                        <div class="bg-white rounded-xl p-3 border border-primary/30 shadow-xl">
                            <img src="{{ $company->logo_url }}" alt="{{ $company->legal_name }}" class="w-full h-full object-contain">
                        </div>
                    @else
                        <div class="bg-gradient-to-br from-dark-800 to-dark-900 rounded-xl p-3 border border-primary/30 shadow-xl">
                            <svg class="w-full h-full text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                    @endif
                </div>
                <h1 class="text-xl font-bold text-white mb-1">
                    {{ t($company->translation_group . '.register', 'Create Account') }}
                </h1>
                <p class="text-gray-400 text-sm">{{ t($company->translation_group . '.company_name', $company->legal_name) }}</p>
            </div>

            <!-- Register Card -->
            <div class="bg-dark-800/80 backdrop-blur-xl rounded-2xl p-6 border border-primary/20 shadow-2xl">
                
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-500/20 border border-red-500/30 rounded-lg">
                        @foreach ($errors->all() as $error)
                            <p class="text-red-300 text-sm">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
                
                <form method="POST" action="{{ route('insurance.register', ['companyRoute' => $company->company_slug]) }}" class="space-y-4">
                    @csrf
                    
                    <!-- Basic Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Legal Name -->
                        <div class="md:col-span-2">
                            <label for="legal_name" class="block text-white text-sm font-medium mb-1">
                                Legal Name <span class="text-red-400">*</span>
                            </label>
                            <input type="text" 
                                   id="legal_name" 
                                   name="legal_name" 
                                   value="{{ old('legal_name') }}"
                                   class="w-full px-3 py-2 bg-dark-900/50 border border-primary/20 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary/50 transition-all text-sm"
                                   placeholder="Insurance Company Legal Name"
                                   required>
                        </div>
                        
                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-white text-sm font-medium mb-1">
                                {{ t($company->translation_group . '.phone', 'Phone Number') }} <span class="text-red-400">*</span>
                            </label>
                            <input type="text" 
                                   id="phone" 
                                   name="phone" 
                                   value="{{ old('phone') }}"
                                   class="w-full px-3 py-2 bg-dark-900/50 border border-primary/20 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary/50 transition-all text-sm"
                                   placeholder="01234567890"
                                   required>
                        </div>
                        
                        <!-- Commercial Register -->
                        <div>
                            <label for="commercial_register" class="block text-white text-sm font-medium mb-1">
                                Commercial Register <span class="text-red-400">*</span>
                            </label>
                            <input type="text" 
                                   id="commercial_register" 
                                   name="commercial_register" 
                                   value="{{ old('commercial_register') }}"
                                   class="w-full px-3 py-2 bg-dark-900/50 border border-primary/20 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary/50 transition-all text-sm"
                                   placeholder="CR123456789"
                                   required>
                        </div>
                        
                        <!-- Tax Number -->
                        <div>
                            <label for="tax_number" class="block text-white text-sm font-medium mb-1">
                                Tax Number <span class="text-gray-400 text-xs">(Optional)</span>
                            </label>
                            <input type="text" 
                                   id="tax_number" 
                                   name="tax_number" 
                                   value="{{ old('tax_number') }}"
                                   class="w-full px-3 py-2 bg-dark-900/50 border border-primary/20 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary/50 transition-all text-sm"
                                   placeholder="TX123456789">
                        </div>
                        
                        <!-- Employee Count -->
                        <div>
                            <label for="employee_count" class="block text-white text-sm font-medium mb-1">
                                Employee Count <span class="text-gray-400 text-xs">(Optional)</span>
                            </label>
                            <input type="number" 
                                   id="employee_count" 
                                   name="employee_count" 
                                   value="{{ old('employee_count') }}"
                                   min="1"
                                   class="w-full px-3 py-2 bg-dark-900/50 border border-primary/20 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary/50 transition-all text-sm"
                                   placeholder="50">
                        </div>
                    </div>
                    
                    <!-- Office Address -->
                    <div>
                        <label for="office_address" class="block text-white text-sm font-medium mb-1">
                            Office Address <span class="text-gray-400 text-xs">(Optional)</span>
                        </label>
                        <textarea id="office_address" 
                                  name="office_address" 
                                  rows="2"
                                  class="w-full px-3 py-2 bg-dark-900/50 border border-primary/20 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary/50 transition-all text-sm"
                                  placeholder="Full office address">{{ old('office_address') }}</textarea>
                    </div>
                    
                    <!-- Password Fields -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-white text-sm font-medium mb-1">
                                {{ t($company->translation_group . '.password', 'Password') }} <span class="text-red-400">*</span>
                            </label>
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   class="w-full px-3 py-2 bg-dark-900/50 border border-primary/20 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary/50 transition-all text-sm"
                                   placeholder="••••••••"
                                   required>
                        </div>
                        
                        <div>
                            <label for="password_confirmation" class="block text-white text-sm font-medium mb-1">
                                Confirm Password <span class="text-red-400">*</span>
                            </label>
                            <input type="password" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   class="w-full px-3 py-2 bg-dark-900/50 border border-primary/20 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary/50 transition-all text-sm"
                                   placeholder="••••••••"
                                   required>
                        </div>
                    </div>
                    
                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full text-white font-bold py-3 px-6 rounded-lg transition-all duration-300 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-primary/50 hover:opacity-90"
                            style="background: linear-gradient(to right, {{ $company->primary_color }}, {{ $company->secondary_color }});">
                        <span class="flex items-center justify-center">
                            <svg class="w-5 h-5 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                            {{ t($company->translation_group . '.register', 'Create Account') }}
                        </span>
                    </button>
                    
                    <!-- Login Link -->
                    <div class="text-center">
                        <p class="text-gray-400 text-sm">
                            Already have an account?
                            <a href="{{ route('insurance.login', ['companyRoute' => $company->company_slug]) }}" class="font-medium hover:underline" style="color: {{ $company->primary_color }};">
                                {{ t($company->translation_group . '.login', 'Login') }}
                            </a>
                        </p>
                    </div>
                </form>
            </div>

            <!-- Footer -->
            <div class="text-center text-gray-500 text-sm mt-6">
                <p>&copy; {{ date('Y') }} {{ t($company->translation_group . '.company_name', $company->legal_name) }}. All rights reserved</p>
            </div>
        </div>
    </div>

    <script>
        // Phone input formatting
        document.getElementById('phone').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 11) {
                value = value.substring(0, 11);
            }
            e.target.value = value;
        });

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const phone = document.getElementById('phone').value;
            const password = document.getElementById('password').value;
            const passwordConfirmation = document.getElementById('password_confirmation').value;
            const legalName = document.getElementById('legal_name').value;
            const commercialRegister = document.getElementById('commercial_register').value;

            if (!phone || !password || !legalName || !commercialRegister) {
                e.preventDefault();
                alert('Please fill in all required fields');
                return;
            }

            if (!/^01[0-9]{9}$/.test(phone)) {
                e.preventDefault();
                alert('Please enter a valid Egyptian phone number');
                return;
            }

            if (password !== passwordConfirmation) {
                e.preventDefault();
                alert('Passwords do not match');
                return;
            }

            if (password.length < 6) {
                e.preventDefault();
                alert('Password must be at least 6 characters');
                return;
            }
        });
    </script>
</body>
</html>