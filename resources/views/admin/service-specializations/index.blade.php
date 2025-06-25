@extends('admin.layouts.app')

@section('title', 'إدارة تخصصات الصيانة')

@section('content')

<!-- Page Header -->
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
    <div>
        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-2">إدارة تخصصات الصيانة</h1>
        <nav class="flex text-sm">
            <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-gold-600">{{ t('admin.dashboard') }}</a>
            <span class="mx-2 text-gray-400">></span>
            <span class="text-gold-600 font-medium">تخصصات الصيانة</span>
        </nav>
    </div>
    <div class="flex items-center space-x-3 {{ $isRtl ? 'space-x-reverse' : '' }} mt-4 sm:mt-0">
        <div class="bg-gray-50 px-3 py-2 rounded-lg border">
            <span class="text-sm text-gray-600">{{ t('admin.total') }}: </span>
            <span class="font-bold text-gold-600">{{ $serviceSpecializations->count() }}</span>
        </div>
        <a href="{{ route('admin.service-specializations.create') }}" class="bg-gold-500 hover:bg-gold-600 text-dark-900 px-4 py-2 rounded-lg font-medium transition-colors">
            <svg class="w-4 h-4 inline {{ $isRtl ? 'ml-1' : 'mr-1' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            إضافة تخصص جديد
        </a>
    </div>
</div>

<!-- Service Specializations Container -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="bg-gold-500 text-dark-900 px-6 py-4">
        <h2 class="text-lg font-bold flex items-center">
            <svg class="w-5 h-5 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            تخصصات الصيانة
        </h2>
    </div>
    
    @if($serviceSpecializations->count() > 0)
        <!-- Desktop Table -->
        <div class="hidden md:block">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-{{ $isRtl ? 'right' : 'left' }} text-xs font-semibold text-gray-600 uppercase">اسم التخصص</th>
                        <th class="px-6 py-3 text-{{ $isRtl ? 'right' : 'left' }} text-xs font-semibold text-gray-600 uppercase">الاسم بالعربية</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">ترتيب العرض</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">الحالة</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200" id="sortable-service-specializations">
                    @foreach($serviceSpecializations as $serviceSpecialization)
                        <tr class="hover:bg-gray-50 sortable-item" data-id="{{ $serviceSpecialization->id }}">
                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-900">{{ $serviceSpecialization->name }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-900">{{ $serviceSpecialization->name_ar }}</div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="bg-gray-100 text-gray-800 text-sm font-mono px-2 py-1 rounded">{{ $serviceSpecialization->sort_order }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $serviceSpecialization->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    <div class="w-2 h-2 rounded-full {{ $serviceSpecialization->is_active ? 'bg-green-400' : 'bg-gray-400' }} {{ $isRtl ? 'ml-1' : 'mr-1' }}"></div>
                                    {{ $serviceSpecialization->is_active ? 'نشط' : 'غير نشط' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center space-x-2 {{ $isRtl ? 'space-x-reverse' : '' }}">
                                    <a href="{{ route('admin.service-specializations.edit', $serviceSpecialization) }}" class="p-2 rounded-lg bg-blue-100 text-blue-600 hover:bg-blue-200 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    
                                    <form method="POST" action="{{ route('admin.service-specializations.toggle', $serviceSpecialization) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="p-2 rounded-lg {{ $serviceSpecialization->is_active ? 'bg-orange-100 text-orange-600 hover:bg-orange-200' : 'bg-green-100 text-green-600 hover:bg-green-200' }} transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $serviceSpecialization->is_active ? 'M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z' : 'M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h8m-10 5a9 9 0 1118 0 9 9 0 01-18 0z' }}"></path>
                                            </svg>
                                        </button>
                                    </form>
                                    
                                    <form method="POST" action="{{ route('admin.service-specializations.destroy', $serviceSpecialization) }}" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا التخصص؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-lg bg-red-100 text-red-600 hover:bg-red-200 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                    
                                    <div class="handle cursor-move p-2 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                                        </svg>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards -->
        <div class="md:hidden space-y-4 p-4">
            @foreach($serviceSpecializations as $serviceSpecialization)
                <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex-1">
                            <h3 class="font-bold text-gray-900">{{ $serviceSpecialization->name }}</h3>
                            <p class="text-gray-600">{{ $serviceSpecialization->name_ar }}</p>
                            <div class="flex items-center space-x-2 {{ $isRtl ? 'space-x-reverse' : '' }} text-xs mt-1">
                                <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded-full">ترتيب: {{ $serviceSpecialization->sort_order }}</span>
                                <span class="inline-flex items-center px-2 py-1 rounded-full {{ $serviceSpecialization->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    <div class="w-2 h-2 rounded-full {{ $serviceSpecialization->is_active ? 'bg-green-400' : 'bg-gray-400' }} {{ $isRtl ? 'ml-1' : 'mr-1' }}"></div>
                                    {{ $serviceSpecialization->is_active ? 'نشط' : 'غير نشط' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-center space-x-3 {{ $isRtl ? 'space-x-reverse' : '' }} pt-3 border-t border-gray-200">
                        <a href="{{ route('admin.service-specializations.edit', $serviceSpecialization) }}" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium bg-blue-100 text-blue-700 hover:bg-blue-200 transition-colors">
                            <svg class="w-4 h-4 {{ $isRtl ? 'ml-1' : 'mr-1' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            تعديل
                        </a>
                        
                        <form method="POST" action="{{ route('admin.service-specializations.toggle', $serviceSpecialization) }}" class="inline">
                            @csrf
                            <button type="submit" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium {{ $serviceSpecialization->is_active ? 'bg-orange-100 text-orange-700 hover:bg-orange-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }} transition-colors">
                                <svg class="w-4 h-4 {{ $isRtl ? 'ml-1' : 'mr-1' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $serviceSpecialization->is_active ? 'M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z' : 'M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h8m-10 5a9 9 0 1118 0 9 9 0 01-18 0z' }}"></path>
                                </svg>
                                {{ $serviceSpecialization->is_active ? 'إلغاء تفعيل' : 'تفعيل' }}
                            </button>
                        </form>
                        
                        <form method="POST" action="{{ route('admin.service-specializations.destroy', $serviceSpecialization) }}" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا التخصص؟')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium bg-red-100 text-red-700 hover:bg-red-200 transition-colors">
                                <svg class="w-4 h-4 {{ $isRtl ? 'ml-1' : 'mr-1' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                حذف
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">لا توجد تخصصات صيانة</h3>
            <p class="text-gray-600">لم يتم إضافة أي تخصصات صيانة بعد. ابدأ بإضافة التخصص الأول.</p>
        </div>
    @endif
</div>

<!-- Info Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
    <div class="bg-blue-50 rounded-xl p-6 border border-blue-200">
        <div class="flex items-center mb-3">
            <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center {{ $isRtl ? 'ml-3' : 'mr-3' }}">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h3 class="font-bold text-gray-900">معلومات التخصصات</h3>
        </div>
        <p class="text-gray-700 text-sm">تخصصات الصيانة تساعد في تصنيف مراكز الخدمة حسب نوع الخدمات التي تقدمها.</p>
    </div>
    
    <div class="bg-gold-50 rounded-xl p-6 border border-gold-200">
        <div class="flex items-center mb-3">
            <div class="w-10 h-10 bg-gold-500 rounded-lg flex items-center justify-center {{ $isRtl ? 'ml-3' : 'mr-3' }}">
                <svg class="w-5 h-5 text-dark-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2m0 0V1a1 1 0 011-1h2a1 1 0 011 1v3M7 4H5a1 1 0 00-1 1v16a1 1 0 001 1h14a1 1 0 001-1V5a1 1 0 00-1-1h-2M7 4h10M9 9h6m-6 4h6m-6 4h6"></path>
                </svg>
            </div>
            <h3 class="font-bold text-gray-900">ترتيب العرض</h3>
        </div>
        <p class="text-gray-700 text-sm">يمكنك ترتيب التخصصات بسحبها وإفلاتها أو تحديد رقم الترتيب يدوياً.</p>
    </div>
    
    <div class="bg-green-50 rounded-xl p-6 border border-green-200">
        <div class="flex items-center mb-3">
            <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center {{ $isRtl ? 'ml-3' : 'mr-3' }}">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h3 class="font-bold text-gray-900">إدارة الحالة</h3>
        </div>
        <p class="text-gray-700 text-sm">يمكنك تفعيل أو إلغاء تفعيل التخصصات دون حذفها نهائياً من النظام.</p>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
// Sortable functionality for reordering service specializations
document.addEventListener('DOMContentLoaded', function() {
    const sortableElement = document.getElementById('sortable-service-specializations');
    if (sortableElement) {
        new Sortable(sortableElement, {
            handle: '.handle',
            animation: 150,
            onEnd: function(evt) {
                const items = [];
                document.querySelectorAll('.sortable-item').forEach(function(item) {
                    items.push(item.dataset.id);
                });
                
                // Send AJAX request to update order
                fetch('{{ route("admin.service-specializations.updateOrder") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ items: items })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        const successMessage = document.createElement('div');
                        successMessage.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg z-50';
                        successMessage.textContent = 'تم تحديث الترتيب بنجاح';
                        document.body.appendChild(successMessage);
                        
                        setTimeout(() => {
                            successMessage.remove();
                        }, 3000);
                    }
                });
            }
        });
    }
});
</script>
@endpush

@endsection
