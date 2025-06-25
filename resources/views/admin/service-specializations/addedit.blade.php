@extends('admin.layouts.app')

@section('title', isset($serviceSpecialization) ? 'تعديل تخصص الصيانة' : 'إضافة تخصص صيانة جديد')

@section('content')

<!-- Page Header -->
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
    <div>
        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-2">
            {{ isset($serviceSpecialization) ? 'تعديل تخصص الصيانة' : 'إضافة تخصص صيانة جديد' }}
        </h1>
        <nav class="flex text-sm">
            <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-gold-600">{{ t('admin.dashboard') }}</a>
            <span class="mx-2 text-gray-400">></span>
            <a href="{{ route('admin.service-specializations.index') }}" class="text-gray-500 hover:text-gold-600">تخصصات الصيانة</a>
            <span class="mx-2 text-gray-400">></span>
            <span class="text-gold-600 font-medium">
                {{ isset($serviceSpecialization) ? 'تعديل' : 'إضافة جديد' }}
            </span>
        </nav>
    </div>
    <a href="{{ route('admin.service-specializations.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition-colors mt-4 sm:mt-0">
        <svg class="w-4 h-4 inline {{ $isRtl ? 'ml-1' : 'mr-1' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        العودة للقائمة
    </a>
</div>

<!-- Form Container -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="bg-gold-500 text-dark-900 px-6 py-4">
        <h2 class="text-lg font-bold flex items-center">
            <svg class="w-5 h-5 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ isset($serviceSpecialization) ? 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z' : 'M12 4v16m8-8H4' }}"></path>
            </svg>
            تفاصيل تخصص الصيانة
        </h2>
    </div>
    
    <form method="POST" 
          action="{{ isset($serviceSpecialization) ? route('admin.service-specializations.update', $serviceSpecialization) : route('admin.service-specializations.store') }}" 
          class="p-6"
          id="serviceSpecializationForm">
        @csrf
        @if(isset($serviceSpecialization))
            @method('PUT')
        @endif
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Service Name (English) -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    اسم التخصص (بالإنجليزية) <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       value="{{ old('name', $serviceSpecialization->name ?? '') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gold-500 focus:border-gold-500 @error('name') border-red-500 @enderror"
                       placeholder="مثال: Engine Repair"
                       required>
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Service Name (Arabic) -->
            <div>
                <label for="name_ar" class="block text-sm font-medium text-gray-700 mb-2">
                    اسم التخصص (بالعربية) <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="name_ar" 
                       name="name_ar" 
                       value="{{ old('name_ar', $serviceSpecialization->name_ar ?? '') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gold-500 focus:border-gold-500 @error('name_ar') border-red-500 @enderror"
                       placeholder="مثال: إصلاح المحركات"
                       required>
                @error('name_ar')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Sort Order -->
            <div>
                <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">
                    ترتيب العرض
                </label>
                <input type="number" 
                       id="sort_order" 
                       name="sort_order" 
                       value="{{ old('sort_order', $serviceSpecialization->sort_order ?? 0) }}"
                       min="0"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gold-500 focus:border-gold-500 @error('sort_order') border-red-500 @enderror"
                       placeholder="0 للترتيب التلقائي">
                <p class="text-xs text-gray-500 mt-1">اتركه فارغاً أو ضع 0 للترتيب التلقائي</p>
                @error('sort_order')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Status -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    الحالة
                </label>
                <div class="flex items-center space-x-3 {{ $isRtl ? 'space-x-reverse' : '' }}">
                    <label class="inline-flex items-center">
                        <input type="radio" 
                               name="is_active" 
                               value="1" 
                               {{ old('is_active', $serviceSpecialization->is_active ?? 1) == 1 ? 'checked' : '' }}
                               class="form-radio text-gold-500 focus:ring-gold-500">
                        <span class="{{ $isRtl ? 'mr-2' : 'ml-2' }} text-sm text-gray-700">نشط</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" 
                               name="is_active" 
                               value="0" 
                               {{ old('is_active', $serviceSpecialization->is_active ?? 1) == 0 ? 'checked' : '' }}
                               class="form-radio text-gray-500 focus:ring-gray-500">
                        <span class="{{ $isRtl ? 'mr-2' : 'ml-2' }} text-sm text-gray-700">غير نشط</span>
                    </label>
                </div>
            </div>
        </div>
        
        <!-- Form Actions -->
        <div class="flex items-center justify-between mt-8 pt-6 border-t border-gray-200">
            <div class="flex items-center space-x-4 {{ $isRtl ? 'space-x-reverse' : '' }}">
                <button type="button" 
                        onclick="resetForm()" 
                        class="text-gray-500 hover:text-gray-700 text-sm font-medium">
                    إعادة تعيين النموذج
                </button>
            </div>
            
            <div class="flex items-center space-x-3 {{ $isRtl ? 'space-x-reverse' : '' }}">
                <a href="{{ route('admin.service-specializations.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                    إلغاء
                </a>
                <button type="submit" 
                        class="bg-gold-500 hover:bg-gold-600 text-dark-900 px-6 py-2 rounded-lg font-medium transition-colors flex items-center"
                        id="submitBtn">
                    <svg class="w-4 h-4 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span id="submitText">{{ isset($serviceSpecialization) ? 'تحديث' : 'حفظ' }}</span>
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Information Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
    <div class="bg-blue-50 rounded-xl p-6 border border-blue-200">
        <div class="flex items-center mb-3">
            <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center {{ $isRtl ? 'ml-3' : 'mr-3' }}">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h3 class="font-bold text-gray-900">إرشادات النموذج</h3>
        </div>
        <ul class="text-gray-700 text-sm space-y-2">
            <li class="flex items-start">
                <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mt-2 {{ $isRtl ? 'ml-2' : 'mr-2' }} flex-shrink-0"></span>
                أدخل اسم التخصص باللغتين العربية والإنجليزية
            </li>
            <li class="flex items-start">
                <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mt-2 {{ $isRtl ? 'ml-2' : 'mr-2' }} flex-shrink-0"></span>
                ترتيب العرض يحدد موضع التخصص في القوائم
            </li>
            <li class="flex items-start">
                <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mt-2 {{ $isRtl ? 'ml-2' : 'mr-2' }} flex-shrink-0"></span>
                يمكن إلغاء تفعيل التخصص دون حذفه
            </li>
        </ul>
    </div>
    
    <div class="bg-green-50 rounded-xl p-6 border border-green-200">
        <div class="flex items-center mb-3">
            <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center {{ $isRtl ? 'ml-3' : 'mr-3' }}">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h3 class="font-bold text-gray-900">أمثلة التخصصات</h3>
        </div>
        <ul class="text-gray-700 text-sm space-y-2">
            <li class="flex items-start">
                <span class="w-1.5 h-1.5 rounded-full bg-green-500 mt-2 {{ $isRtl ? 'ml-2' : 'mr-2' }} flex-shrink-0"></span>
                إصلاح المحركات
            </li>
            <li class="flex items-start">
                <span class="w-1.5 h-1.5 rounded-full bg-green-500 mt-2 {{ $isRtl ? 'ml-2' : 'mr-2' }} flex-shrink-0"></span>
                صيانة الكهرباء
            </li>
            <li class="flex items-start">
                <span class="w-1.5 h-1.5 rounded-full bg-green-500 mt-2 {{ $isRtl ? 'ml-2' : 'mr-2' }} flex-shrink-0"></span>
                إصلاح الفرامل
            </li>
        </ul>
    </div>
    
    <div class="bg-gold-50 rounded-xl p-6 border border-gold-200">
        <div class="flex items-center mb-3">
            <div class="w-10 h-10 bg-gold-500 rounded-lg flex items-center justify-center {{ $isRtl ? 'ml-3' : 'mr-3' }}">
                <svg class="w-5 h-5 text-dark-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                </svg>
            </div>
            <h3 class="font-bold text-gray-900">نصائح</h3>
        </div>
        <ul class="text-gray-700 text-sm space-y-2">
            <li class="flex items-start">
                <span class="w-1.5 h-1.5 rounded-full bg-gold-500 mt-2 {{ $isRtl ? 'ml-2' : 'mr-2' }} flex-shrink-0"></span>
                استخدم أسماء واضحة ومفهومة
            </li>
            <li class="flex items-start">
                <span class="w-1.5 h-1.5 rounded-full bg-gold-500 mt-2 {{ $isRtl ? 'ml-2' : 'mr-2' }} flex-shrink-0"></span>
                رتب التخصصات حسب الأهمية
            </li>
            <li class="flex items-start">
                <span class="w-1.5 h-1.5 rounded-full bg-gold-500 mt-2 {{ $isRtl ? 'ml-2' : 'mr-2' }} flex-shrink-0"></span>
                تأكد من صحة الترجمة
            </li>
        </ul>
    </div>
</div>

<script>
// Reset form
function resetForm() {
    if (confirm('هل أنت متأكد من إعادة تعيين النموذج؟ سيتم فقدان جميع البيانات المدخلة.')) {
        document.getElementById('serviceSpecializationForm').reset();
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Form submission handling
    const form = document.getElementById('serviceSpecializationForm');
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    
    form.addEventListener('submit', function(e) {
        // Disable submit button to prevent double submission
        submitBtn.disabled = true;
        submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
        submitText.textContent = 'جاري الحفظ...';
        
        // Add loading spinner
        const spinner = document.createElement('div');
        spinner.className = 'animate-spin rounded-full h-4 w-4 border-b-2 border-dark-900 {{ $isRtl ? "ml-2" : "mr-2" }}';
        submitBtn.insertBefore(spinner, submitText);
        
        // Re-enable button after 10 seconds as fallback
        setTimeout(function() {
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            submitText.textContent = '{{ isset($serviceSpecialization) ? "تحديث" : "حفظ" }}';
            if (spinner.parentNode) {
                spinner.parentNode.removeChild(spinner);
            }
        }, 10000);
    });
    
    // Live validation
    const nameInput = document.getElementById('name');
    const nameArInput = document.getElementById('name_ar');
    
    function validateInput(input, minLength = 2) {
        const value = input.value.trim();
        const isValid = value.length >= minLength;
        
        if (value.length > 0) {
            if (isValid) {
                input.classList.remove('border-red-500');
                input.classList.add('border-green-500');
            } else {
                input.classList.remove('border-green-500');
                input.classList.add('border-red-500');
            }
        } else {
            input.classList.remove('border-red-500', 'border-green-500');
        }
        
        return isValid;
    }
    
    nameInput.addEventListener('input', function() {
        validateInput(this);
    });
    
    nameArInput.addEventListener('input', function() {
        validateInput(this);
    });
    
    // Character counter for inputs
    function addCharacterCounter(input, maxLength) {
        const counter = document.createElement('div');
        counter.className = 'text-xs text-gray-500 mt-1 text-{{ $isRtl ? "left" : "right" }}';
        input.parentNode.appendChild(counter);
        
        function updateCounter() {
            const remaining = maxLength - input.value.length;
            counter.textContent = `${input.value.length}/${maxLength}`;
            
            if (remaining < 20) {
                counter.classList.add('text-orange-500');
            } else if (remaining < 10) {
                counter.classList.remove('text-orange-500');
                counter.classList.add('text-red-500');
            } else {
                counter.classList.remove('text-orange-500', 'text-red-500');
                counter.classList.add('text-gray-500');
            }
        }
        
        input.addEventListener('input', updateCounter);
        updateCounter();
    }
    
    addCharacterCounter(nameInput, 255);
    addCharacterCounter(nameArInput, 255);
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + S to save
        if ((e.ctrlKey || e.metaKey) && e.key === 's') {
            e.preventDefault();
            form.submit();
        }
        
        // Escape to cancel
        if (e.key === 'Escape') {
            if (confirm('هل تريد إلغاء العملية والعودة للقائمة؟')) {
                window.location.href = '{{ route("admin.service-specializations.index") }}';
            }
        }
    });
});
</script>

@endsection
