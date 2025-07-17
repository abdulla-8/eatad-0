{{-- resources/views/insurance/service-centers/edit.blade.php --}}
@extends('insurance.layouts.app')

@section('title', 'تعديل مركز الصيانة')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-yellow-600 to-orange-600 px-8 py-6">
                <div class="flex items-center text-white">
                    <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center mr-4">
                        <i class="fas fa-edit text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold">تعديل مركز الصيانة</h1>
                        <p class="text-yellow-100 mt-1">تعديل معلومات {{ $serviceCenter->legal_name }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <form method="POST" action="{{ route('insurance.service-centers.update', ['companyRoute' => auth()->user()->company_slug, 'serviceCenter' => $serviceCenter->id]) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <!-- Form Header -->
                <div class="px-8 py-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-building mr-3 text-yellow-600"></i>
                        المعلومات الأساسية
                    </h2>
                </div>

                <div class="px-8 py-6">
                    <!-- Basic Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <!-- Legal Name -->
                        <div class="space-y-2">
                            <label class="flex items-center text-sm font-semibold text-gray-700">
                                <i class="fas fa-building text-yellow-600 mr-2"></i>
                                اسم المركز القانوني
                                <span class="text-red-500 mr-1">*</span>
                            </label>
                            <input type="text" name="legal_name" value="{{ old('legal_name', $serviceCenter->legal_name) }}" required
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 transition-all duration-200 text-gray-900"
                                   placeholder="أدخل اسم المركز القانوني">
                            @error('legal_name')
                                <p class="text-red-500 text-xs mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div class="space-y-2">
                            <label class="flex items-center text-sm font-semibold text-gray-700">
                                <i class="fas fa-phone text-yellow-600 mr-2"></i>
                                رقم الهاتف
                                <span class="text-red-500 mr-1">*</span>
                            </label>
                            <input type="tel" name="phone" value="{{ old('phone', $serviceCenter->phone) }}" required
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 transition-all duration-200 text-gray-900"
                                   placeholder="966501234567">
                            @error('phone')
                                <p class="text-red-500 text-xs mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="space-y-2">
                            <label class="flex items-center text-sm font-semibold text-gray-700">
                                <i class="fas fa-lock text-yellow-600 mr-2"></i>
                                كلمة المرور الجديدة
                                <span class="text-gray-500 text-xs">(اتركها فارغة إذا لم تريد تغييرها)</span>
                            </label>
                            <input type="password" name="password"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 transition-all duration-200 text-gray-900"
                                   placeholder="أدخل كلمة المرور الجديدة">
                            @error('password')
                                <p class="text-red-500 text-xs mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Commercial Register -->
                        <div class="space-y-2">
                            <label class="flex items-center text-sm font-semibold text-gray-700">
                                <i class="fas fa-file-contract text-yellow-600 mr-2"></i>
                                السجل التجاري
                                <span class="text-red-500 mr-1">*</span>
                            </label>
                            <input type="text" name="commercial_register" value="{{ old('commercial_register', $serviceCenter->commercial_register) }}" required
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 transition-all duration-200 text-gray-900"
                                   placeholder="أدخل رقم السجل التجاري">
                            @error('commercial_register')
                                <p class="text-red-500 text-xs mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Tax Number -->
                        <div class="space-y-2">
                            <label class="flex items-center text-sm font-semibold text-gray-700">
                                <i class="fas fa-calculator text-yellow-600 mr-2"></i>
                                الرقم الضريبي
                            </label>
                            <input type="text" name="tax_number" value="{{ old('tax_number', $serviceCenter->tax_number) }}"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 transition-all duration-200 text-gray-900"
                                   placeholder="أدخل الرقم الضريبي">
                            @error('tax_number')
                                <p class="text-red-500 text-xs mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Location Information -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-map-marker-alt text-yellow-600 mr-2"></i>
                            معلومات الموقع
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Industrial Area -->
                            <div class="space-y-2">
                                <label class="flex items-center text-sm font-semibold text-gray-700">
                                    <i class="fas fa-industry text-yellow-600 mr-2"></i>
                                    المنطقة الصناعية
                                </label>
                                <select name="industrial_area_id" 
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 transition-all duration-200 text-gray-900">
                                    <option value="">اختر المنطقة الصناعية</option>
                                    @foreach($industrialAreas as $area)
                                        <option value="{{ $area->id }}" {{ old('industrial_area_id', $serviceCenter->industrial_area_id) == $area->id ? 'selected' : '' }}>
                                            {{ $area->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('industrial_area_id')
                                    <p class="text-red-500 text-xs mt-1 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Service Specialization -->
                            <div class="space-y-2">
                                <label class="flex items-center text-sm font-semibold text-gray-700">
                                    <i class="fas fa-star text-yellow-600 mr-2"></i>
                                    التخصص
                                </label>
                                <select name="service_specialization_id" 
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 transition-all duration-200 text-gray-900">
                                    <option value="">اختر التخصص</option>
                                    @foreach($serviceSpecializations as $specialization)
                                        <option value="{{ $specialization->id }}" {{ old('service_specialization_id', $serviceCenter->service_specialization_id) == $specialization->id ? 'selected' : '' }}>
                                            {{ $specialization->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('service_specialization_id')
                                    <p class="text-red-500 text-xs mt-1 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Center Address -->
                            <div class="space-y-2 md:col-span-2">
                                <label class="flex items-center text-sm font-semibold text-gray-700">
                                    <i class="fas fa-map-marker-alt text-yellow-600 mr-2"></i>
                                    عنوان المركز
                                </label>
                                <textarea name="center_address" rows="3"
                                          class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 transition-all duration-200 text-gray-900"
                                          placeholder="أدخل عنوان المركز التفصيلي">{{ old('center_address', $serviceCenter->center_address) }}</textarea>
                                @error('center_address')
                                    <p class="text-red-500 text-xs mt-1 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Technicians Information -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-users text-yellow-600 mr-2"></i>
                            معلومات الفنيين
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
                            <!-- Body Work Technicians -->
                            <div class="space-y-2">
                                <label class="flex items-center text-sm font-semibold text-gray-700">
                                    <i class="fas fa-hammer text-yellow-600 mr-2"></i>
                                    فنيو هيكل
                                </label>
                                <input type="number" name="body_work_technicians" value="{{ old('body_work_technicians', $serviceCenter->body_work_technicians) }}" min="0"
                                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 transition-all duration-200 text-gray-900">
                                @error('body_work_technicians')
                                    <p class="text-red-500 text-xs mt-1 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Mechanical Technicians -->
                            <div class="space-y-2">
                                <label class="flex items-center text-sm font-semibold text-gray-700">
                                    <i class="fas fa-cog text-yellow-600 mr-2"></i>
                                    فنيو ميكانيكا
                                </label>
                                <input type="number" name="mechanical_technicians" value="{{ old('mechanical_technicians', $serviceCenter->mechanical_technicians) }}" min="0"
                                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 transition-all duration-200 text-gray-900">
                                @error('mechanical_technicians')
                                    <p class="text-red-500 text-xs mt-1 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Painting Technicians -->
                            <div class="space-y-2">
                                <label class="flex items-center text-sm font-semibold text-gray-700">
                                    <i class="fas fa-paint-brush text-yellow-600 mr-2"></i>
                                    فنيو دهان
                                </label>
                                <input type="number" name="painting_technicians" value="{{ old('painting_technicians', $serviceCenter->painting_technicians) }}" min="0"
                                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 transition-all duration-200 text-gray-900">
                                @error('painting_technicians')
                                    <p class="text-red-500 text-xs mt-1 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Electrical Technicians -->
                            <div class="space-y-2">
                                <label class="flex items-center text-sm font-semibold text-gray-700">
                                    <i class="fas fa-bolt text-yellow-600 mr-2"></i>
                                    فنيو كهرباء
                                </label>
                                <input type="number" name="electrical_technicians" value="{{ old('electrical_technicians', $serviceCenter->electrical_technicians) }}" min="0"
                                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 transition-all duration-200 text-gray-900">
                                @error('electrical_technicians')
                                    <p class="text-red-500 text-xs mt-1 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Other Technicians -->
                            <div class="space-y-2">
                                <label class="flex items-center text-sm font-semibold text-gray-700">
                                    <i class="fas fa-wrench text-yellow-600 mr-2"></i>
                                    فنيو آخرين
                                </label>
                                <input type="number" name="other_technicians" value="{{ old('other_technicians', $serviceCenter->other_technicians) }}" min="0"
                                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 transition-all duration-200 text-gray-900">
                                @error('other_technicians')
                                    <p class="text-red-500 text-xs mt-1 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Additional Phones -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-phone-alt text-yellow-600 mr-2"></i>
                            أرقام هواتف إضافية
                        </h3>
                        <div id="additional-phones">
                            @if($serviceCenter->additionalPhones->where('is_primary', false)->count() > 0)
                                @foreach($serviceCenter->additionalPhones->where('is_primary', false) as $index => $phone)
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                        <div class="space-y-2">
                                            <label class="flex items-center text-sm font-semibold text-gray-700">
                                                <i class="fas fa-phone text-yellow-600 mr-2"></i>
                                                رقم الهاتف
                                            </label>
                                            <input type="tel" name="additional_phones[]" value="{{ $phone->phone }}"
                                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 transition-all duration-200 text-gray-900">
                                        </div>
                                        <div class="space-y-2">
                                            <label class="flex items-center text-sm font-semibold text-gray-700">
                                                <i class="fas fa-tag text-yellow-600 mr-2"></i>
                                                تسمية الهاتف
                                            </label>
                                            <input type="text" name="phone_labels[]" value="{{ $phone->label }}"
                                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 transition-all duration-200 text-gray-900">
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <button type="button" onclick="addPhoneField()" 
                                class="mt-2 px-4 py-2 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 transition-colors duration-200">
                            <i class="fas fa-plus mr-2"></i>
                            إضافة رقم هاتف
                        </button>
                    </div>

                    <!-- Additional Information -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-info-circle text-yellow-600 mr-2"></i>
                            معلومات إضافية
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Center Area -->
                            <div class="space-y-2">
                                <label class="flex items-center text-sm font-semibold text-gray-700">
                                    <i class="fas fa-expand-arrows-alt text-yellow-600 mr-2"></i>
                                    مساحة المركز (متر مربع)
                                </label>
                                <input type="number" name="center_area_sqm" value="{{ old('center_area_sqm', $serviceCenter->center_area_sqm) }}" min="0" step="0.01"
                                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 transition-all duration-200 text-gray-900"
                                       placeholder="مساحة المركز">
                                @error('center_area_sqm')
                                    <p class="text-red-500 text-xs mt-1 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Center Logo -->
                            <div class="space-y-2">
                                <label class="flex items-center text-sm font-semibold text-gray-700">
                                    <i class="fas fa-image text-yellow-600 mr-2"></i>
                                    شعار المركز
                                </label>
                                <input type="file" name="center_logo" accept="image/*"
                                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 transition-all duration-200 text-gray-900">
                                @if($serviceCenter->center_logo)
                                    <p class="text-xs text-gray-500 mt-1">الشعار الحالي: {{ basename($serviceCenter->center_logo) }}</p>
                                @endif
                                @error('center_logo')
                                    <p class="text-red-500 text-xs mt-1 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Status Options -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-toggle-on text-yellow-600 mr-2"></i>
                            الحالة
                        </h3>
                        <div class="flex items-center space-x-4 rtl:space-x-reverse">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $serviceCenter->is_active) ? 'checked' : '' }}
                                       class="w-5 h-5 text-yellow-600 border-2 border-gray-300 rounded focus:ring-yellow-500 focus:ring-2">
                                <span class="mr-2 text-sm font-medium text-gray-700">تفعيل المركز</span>
                            </label>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-end space-x-4 rtl:space-x-reverse pt-6 border-t border-gray-200">
                        <a href="{{ route('insurance.service-centers.index', ['companyRoute' => auth()->user()->company_slug]) }}" 
                           class="px-6 py-3 bg-gray-300 text-gray-700 rounded-xl font-semibold hover:bg-gray-400 transition-colors duration-200">
                            <i class="fas fa-times mr-2"></i>
                            إلغاء
                        </a>
                        <button type="submit" 
                                class="px-6 py-3 bg-yellow-600 text-white rounded-xl font-semibold hover:bg-yellow-700 transition-colors duration-200">
                            <i class="fas fa-save mr-2"></i>
                            حفظ التغييرات
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function addPhoneField() {
    const container = document.getElementById('additional-phones');
    const phoneField = document.createElement('div');
    phoneField.classList.add('grid', 'grid-cols-1', 'md:grid-cols-2', 'gap-4', 'mb-4');
    phoneField.innerHTML = `
        <div class="space-y-2">
            <label class="flex items-center text-sm font-semibold text-gray-700">
                <i class="fas fa-phone text-yellow-600 mr-2"></i>
                رقم الهاتف
            </label>
            <input type="tel" name="additional_phones[]" 
                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 transition-all duration-200 text-gray-900">
        </div>
        <div class="space-y-2">
            <label class="flex items-center text-sm font-semibold text-gray-700">
                <i class="fas fa-tag text-yellow-600 mr-2"></i>
                تسمية الهاتف
            </label>
            <input type="text" name="phone_labels[]" 
                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 transition-all duration-200 text-gray-900">
        </div>
    `;
    container.appendChild(phoneField);
}
</script>
@endsection
