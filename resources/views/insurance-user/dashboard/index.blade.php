@extends('insurance-user.layouts.app')
@section('title', t($company->translation_group . '.dashboard', 'Dashboard'))

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
@endpush

@section('content')
<div class="container mx-auto px-4 py-6 rtl:text-right ltr:text-left" dir="rtl">
    <!-- Welcome Section -->
    <div class="rounded-2xl p-6 md:p-8 text-white text-center mb-6 md:mb-8 shadow-xl" 
         style="background: linear-gradient(135deg, {{ $company->primary_color ?? '#3b82f6' }}, {{ $company->secondary_color ?? '#8b5cf6' }});">
        <h2 class="text-2xl md:text-3xl font-light mb-3">{{ t($company->translation_group . '.welcome_back', 'أهلاً وسهلاً') }}، {{ $stats['user_info']['full_name'] }}</h2>
        <p class="text-white/80 text-base md:text-lg">{{ t($company->translation_group . '.dashboard_subtitle', 'إدارة مطالباتك ومعلوماتك بكل سهولة') }}</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 md:gap-6 mb-6 md:mb-8">
        <!-- Total Claims Card -->
        <div class="rounded-xl md:rounded-2xl p-4 md:p-6 text-white shadow-lg transform transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl relative overflow-hidden"
             style="background: linear-gradient(135deg, {{ $company->primary_color ?? '#3b82f6' }}, {{ $company->primary_color ?? '#3b82f6' }}dd);">
            <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent pointer-events-none"></div>
            <div class="relative z-10 flex items-center justify-between">
                <div class="flex-1">
                    <div class="text-2xl md:text-3xl font-bold mb-2">{{ $stats['claims_stats']['total_claims'] }}</div>
                    <div class="text-xs md:text-sm opacity-90">{{ t($company->translation_group . '.total_claims', 'إجمالي المطالبات') }}</div>
                </div>
                <div class="text-3xl md:text-4xl opacity-80 ml-3 md:ml-4">
                    <!-- File Alt SVG Icon -->
                    <svg class="w-8 h-8 md:w-12 md:h-12" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                        <path d="M8,12V14H16V12H8M8,16V18H13V16H8Z"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <!-- Pending Claims Card -->
        <div class="rounded-xl md:rounded-2xl p-4 md:p-6 text-white shadow-lg transform transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl relative overflow-hidden"
             style="background: linear-gradient(135deg, {{ $company->warning_color ?? '#f59e0b' }}, {{ $company->warning_color ?? '#f59e0b' }}dd);">
            <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent pointer-events-none"></div>
            <div class="relative z-10 flex items-center justify-between">
                <div class="flex-1">
                    <div class="text-2xl md:text-3xl font-bold mb-2">{{ $stats['claims_stats']['pending_claims'] }}</div>
                    <div class="text-xs md:text-sm opacity-90">{{ t($company->translation_group . '.pending_claims', 'مطالبات معلقة') }}</div>
                </div>
                <div class="text-3xl md:text-4xl opacity-80 ml-3 md:ml-4">
                    <!-- Clock SVG Icon -->
                    <svg class="w-8 h-8 md:w-12 md:h-12" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M16.2,16.2L11,13V7H12.5V12.2L17,14.7L16.2,16.2Z"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <!-- Approved Claims Card -->
        <div class="rounded-xl md:rounded-2xl p-4 md:p-6 text-white shadow-lg transform transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl relative overflow-hidden"
             style="background: linear-gradient(135deg, {{ $company->success_color ?? '#10b981' }}, {{ $company->success_color ?? '#10b981' }}dd);">
            <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent pointer-events-none"></div>
            <div class="relative z-10 flex items-center justify-between">
                <div class="flex-1">
                    <div class="text-2xl md:text-3xl font-bold mb-2">{{ $stats['claims_stats']['approved_claims'] }}</div>
                    <div class="text-xs md:text-sm opacity-90">{{ t($company->translation_group . '.approved_claims', 'مطالبات مقبولة') }}</div>
                </div>
                <div class="text-3xl md:text-4xl opacity-80 ml-3 md:ml-4">
                    <!-- Check Circle SVG Icon -->
                    <svg class="w-8 h-8 md:w-12 md:h-12" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12,2A10,10 0 0,1 22,12A10,10 0 0,1 12,22A10,10 0 0,1 2,12A10,10 0 0,1 12,2M11,16.5L18,9.5L16.59,8.09L11,13.67L7.91,10.59L6.5,12L11,16.5Z"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <!-- Rejected Claims Card -->
        <div class="rounded-xl md:rounded-2xl p-4 md:p-6 text-white shadow-lg transform transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl relative overflow-hidden"
             style="background: linear-gradient(135deg, {{ $company->danger_color ?? '#ef4444' }}, {{ $company->danger_color ?? '#ef4444' }}dd);">
            <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent pointer-events-none"></div>
            <div class="relative z-10 flex items-center justify-between">
                <div class="flex-1">
                    <div class="text-2xl md:text-3xl font-bold mb-2">{{ $stats['claims_stats']['rejected_claims'] }}</div>
                    <div class="text-xs md:text-sm opacity-90">{{ t($company->translation_group . '.rejected_claims', 'مطالبات مرفوضة') }}</div>
                </div>
                <div class="text-3xl md:text-4xl opacity-80 ml-3 md:ml-4">
                    <!-- Close Circle SVG Icon -->
                    <svg class="w-8 h-8 md:w-12 md:h-12" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12,2C17.53,2 22,6.47 22,12C22,17.53 17.53,22 12,22C6.47,22 2,17.53 2,12C2,6.47 6.47,2 12,2M15.59,7L12,10.59L8.41,7L7,8.41L10.59,12L7,15.59L8.41,17L12,13.41L15.59,17L17,15.59L13.41,12L17,8.41L15.59,7Z"/>
                    </svg>
                </div>
            </div>
        </div>

 <!-- Total Complaints Card -->
<div class="rounded-xl md:rounded-2xl p-4 md:p-6 text-white shadow-lg transform transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl relative overflow-hidden"
     style="background: linear-gradient(135deg, {{ $company->secondary_color ?? '#8b5cf6' }}, {{ $company->secondary_color ?? '#8b5cf6' }}dd);">
    <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent pointer-events-none"></div>
    <div class="relative z-10 flex items-center justify-between">
        <div class="flex-1">
            <div class="text-2xl md:text-3xl font-bold mb-2">{{ $stats['complaints_stats']['total_complaints'] }}</div>
            <div class="text-xs md:text-sm opacity-90">{{ t($company->translation_group . '.total_complaints', 'إجمالي الشكاوى') }}</div>
        </div>
        <div class="text-3xl md:text-4xl opacity-80 ml-3 md:ml-4">
            <!-- Complaint SVG Icon -->
            <svg class="w-8 h-8 md:w-12 md:h-12" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M12,6A1,1 0 0,1 13,7A1,1 0 0,1 12,8A1,1 0 0,1 11,7A1,1 0 0,1 12,6M12,10C12.55,10 13,10.45 13,11V17C13,17.55 12.55,18 12,18C11.45,18 11,17.55 11,17V11C11,10.45 11.45,10 12,10Z"/>
            </svg>
        </div>
    </div>
</div>

    </div>

    <!-- Charts Section (keeping existing charts) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6 mb-6 md:mb-8">
        <!-- Claims Over Time Chart -->
        <div class="lg:col-span-2 bg-white rounded-xl md:rounded-2xl p-4 md:p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-shadow duration-300">
            <h3 class="text-base md:text-lg font-semibold text-gray-800 text-center mb-4 md:mb-6">{{ t($company->translation_group . '.claims_over_time', 'المطالبات على مدار الأشهر') }}</h3>
            <div class="h-64 md:h-80">
                <canvas id="claimsChart" class="w-full h-full"></canvas>
            </div>
        </div>
        
        <!-- Claims by Status Chart -->
        <div class="bg-white rounded-xl md:rounded-2xl p-4 md:p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-shadow duration-300">
            <h3 class="text-base md:text-lg font-semibold text-gray-800 text-center mb-4 md:mb-6">{{ t($company->translation_group . '.claims_by_status', 'المطالبات حسب الحالة') }}</h3>
            <div class="h-64 md:h-80">
                <canvas id="statusChart" class="w-full h-full"></canvas>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 md:gap-8">
        <!-- User Information (keeping existing) -->
        <div class="bg-white rounded-xl md:rounded-2xl p-4 md:p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <h5 class="flex items-center text-lg md:text-xl font-semibold text-gray-800 mb-4 md:mb-6">
                <!-- User Circle SVG Icon -->
                <svg class="w-6 h-6 ml-2 md:ml-3" fill="{{ $company->primary_color ?? '#3b82f6' }}" viewBox="0 0 24 24">
                    <path d="M12,2A10,10 0 0,1 22,12A10,10 0 0,1 12,22A10,10 0 0,1 2,12A10,10 0 0,1 12,2M7.07,18.28C7.5,17.38 10.12,16.5 12,16.5C13.88,16.5 16.5,17.38 16.93,18.28C15.57,19.36 13.86,20 12,20C10.14,20 8.43,19.36 7.07,18.28M18.36,16.83C16.93,15.09 13.46,14.5 12,14.5C10.54,14.5 7.07,15.09 5.64,16.83C4.62,15.5 4,13.82 4,12C4,7.59 7.59,4 12,4C16.41,4 20,7.59 20,12C20,13.82 19.38,15.5 18.36,16.83M12,6C10.06,6 8.5,7.56 8.5,9.5C8.5,11.44 10.06,13 12,13C13.94,13 15.5,11.44 15.5,9.5C15.5,7.56 13.94,6 12,6M12,11A1.5,1.5 0 0,1 10.5,9.5A1.5,1.5 0 0,1 12,8A1.5,1.5 0 0,1 13.5,9.5A1.5,1.5 0 0,1 12,11Z"/>
                </svg>
                {{ t($company->translation_group . '.personal_info', 'المعلومات الشخصية') }}
            </h5>
            
            <!-- User info content (keeping existing) -->
            <div class="space-y-3 md:space-y-4">
                <div class="flex items-center py-2 md:py-3 border-b border-gray-100 last:border-b-0">
                    <div class="flex-1">
                        <div class="text-xs md:text-sm text-gray-500 mb-1">{{ t($company->translation_group . '.member_since', 'عضو منذ') }}</div>
                        <div class="text-sm md:text-base font-semibold text-gray-800">{{ $stats['user_info']['member_since'] }}</div>
                    </div>
                    <div class="bg-gray-50 rounded-full w-8 h-8 md:w-10 md:h-10 flex items-center justify-center text-gray-600 mr-3 md:mr-4">
                        <svg class="w-4 h-4 md:w-5 md:h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19,3H18V1H16V3H8V1H6V3H5A2,2 0 0,0 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5A2,2 0 0,0 19,3M19,19H5V8H19V19Z"/>
                        </svg>
                    </div>
                </div>
                
                <div class="flex items-center py-2 md:py-3 border-b border-gray-100 last:border-b-0">
                    <div class="flex-1">
                        <div class="text-xs md:text-sm text-gray-500 mb-1">{{ t($company->translation_group . '.policy_number', 'رقم البوليصة') }}</div>
                        <div class="text-sm md:text-base font-semibold text-gray-800">{{ $stats['user_info']['policy_number'] }}</div>
                    </div>
                    <div class="bg-gray-50 rounded-full w-8 h-8 md:w-10 md:h-10 flex items-center justify-center text-gray-600 mr-3 md:mr-4">
                        <svg class="w-4 h-4 md:w-5 md:h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M4,4A2,2 0 0,0 2,6V18A2,2 0 0,0 4,20H20A2,2 0 0,0 22,18V6A2,2 0 0,0 20,4H4M4,6H20V18H4V6M6,8V10H8V8H6M10,8V10H18V8H10M6,12V14H8V12H6M10,12V14H18V12H10M6,16V18H8V16H6M10,16V18H18V16H10Z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
<!-- Quick Actions (Updated with Complaints) -->
<div class="bg-white rounded-xl md:rounded-2xl p-4 md:p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
    <h5 class="flex items-center text-lg md:text-xl font-semibold text-gray-800 mb-4 md:mb-6">
        <!-- Bolt SVG Icon -->
        <svg class="w-6 h-6 ml-2 md:ml-3" fill="{{ $company->warning_color ?? '#f59e0b' }}" viewBox="0 0 24 24">
            <path d="M11,21L12.5,18.5L9.5,16.5L13,3L8,3L3.5,12L6.5,12L5,15.5L8,17.5L11,21M13,21L14.5,18.5L11.5,16.5L15,3L20,3L24.5,12L21.5,12L23,15.5L20,17.5L13,21Z"/>
        </svg>
        {{ t($company->translation_group . '.quick_actions', 'الإجراءات السريعة') }}
    </h5>
    
    <div class="space-y-3">
        <a href="{{ route('insurance.user.claims.index', $company->company_slug) }}" 
           class="flex items-center p-3 md:p-4 text-white rounded-lg md:rounded-xl transition-all duration-300 hover:translate-x-1 group"
           style="background: linear-gradient(135deg, {{ $company->success_color ?? '#10b981' }}, {{ $company->success_color ?? '#10b981' }}dd); 
                  {{ request()->routeIs('insurance.user.claims.*') ? 'box-shadow: 0 0 0 3px ' . ($company->success_color ?? '#10b981') . '33;' : '' }}">
            <div class="flex-1">
                <div class="font-bold text-base md:text-lg">{{ t($company->translation_group . '.view_claims', 'عرض المطالبات') }}</div>
                <div class="text-xs md:text-sm text-white/80">{{ t($company->translation_group . '.manage_your_claims', 'إدارة مطالباتك') }}</div>
            </div>
            <div class="text-xl md:text-2xl mr-3 md:mr-4 group-hover:scale-110 transition-transform duration-300">
                <svg class="w-6 h-6 md:w-8 md:h-8" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                    <path d="M8,12V14H16V12H8M8,16V18H13V16H8Z"/>
                </svg>
            </div>
        </a>
        
        <!-- New Complaints Link -->
        <a href="{{ route('insurance.user.complaints.index', $company->company_slug) }}" 
           class="flex items-center p-3 md:p-4 text-white rounded-lg md:rounded-xl transition-all duration-300 hover:translate-x-1 group"
           style="background: linear-gradient(135deg, {{ $company->success_color ?? '#10b981' }}, {{ $company->success_color ?? '#10b981' }}dd); 
                  {{ request()->routeIs('insurance.user.complaints.*') ? 'box-shadow: 0 0 0 3px ' . ($company->success_color ?? '#10b981') . '33;' : '' }}">
            <div class="flex-1">
                <div class="font-bold text-base md:text-lg">{{ t($company->translation_group . '.view_complaints', 'عرض الشكاوى') }}</div>
                <div class="text-xs md:text-sm text-white/80">{{ t($company->translation_group . '.manage_your_complaints', 'إدارة شكاواك') }}</div>
            </div>
            <div class="text-xl md:text-2xl mr-3 md:mr-4 group-hover:scale-110 transition-transform duration-300">
                <svg class="w-6 h-6 md:w-8 md:h-8" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12,2L13.09,8.26L22,9L13.09,15.74L12,22L10.91,15.74L2,9L10.91,8.26L12,2Z"/>
                </svg>
            </div>
        </a>
        
        <a href="{{ route('insurance.user.claims.create', $company->company_slug) }}" 
           class="flex items-center p-3 md:p-4 text-white rounded-lg md:rounded-xl transition-all duration-300 hover:translate-x-1 group"
           style="background: linear-gradient(135deg, {{ $company->success_color ?? '#10b981' }}, {{ $company->success_color ?? '#10b981' }}dd);">
            <div class="flex-1">
                <div class="font-bold text-sm md:text-base">{{ t($company->translation_group . '.new_claim', 'مطالبة جديدة') }}</div>
                <div class="text-xs md:text-sm text-white/80">{{ t($company->translation_group . '.submit_new_claim', 'تقديم مطالبة جديدة') }}</div>
            </div>
            <div class="text-lg md:text-xl mr-3 md:mr-4 group-hover:scale-110 transition-transform duration-300">
                <svg class="w-5 h-5 md:w-6 md:h-6" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17,13H13V17H11V13H7V11H11V7H13V11H17M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2Z"/>
                </svg>
            </div>
        </a>
        
        <a href="{{ route('insurance.user.profile.edit', $company->company_slug) }}" 
           class="flex items-center p-3 md:p-4 text-white rounded-lg md:rounded-xl transition-all duration-300 hover:translate-x-1 group"
           style="background: linear-gradient(135deg, {{ $company->success_color ?? '#10b981' }}, {{ $company->success_color ?? '#10b981' }}dd);">
            <div class="flex-1">
                <div class="font-bold text-sm md:text-base">{{ t($company->translation_group . '.edit_profile', 'تحديث الملف الشخصي') }}</div>
                <div class="text-xs md:text-sm text-white/80">{{ t($company->translation_group . '.update_your_info', 'تحديث معلوماتك') }}</div>
            </div>
            <div class="text-lg md:text-xl mr-3 md:mr-4 group-hover:scale-110 transition-transform duration-300">
                <svg class="w-5 h-5 md:w-6 md:h-6" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M21.7,13.35L20.7,14.35L18.65,12.3L19.65,11.3C19.86,11.09 20.21,11.09 20.42,11.3L21.7,12.58C21.91,12.79 21.91,13.14 21.7,13.35M12,18.94L18.06,12.88L20.11,14.93L14.06,21H12V18.94M12,14C7.58,14 4,15.79 4,18V20H10V18.11L14,14.11C13.34,14.03 12.67,14 12,14M12,4A4,4 0 0,0 8,8A4,4 0 0,0 12,12A4,4 0 0,0 16,8A4,4 0 0,0 12,4Z"/>
                </svg>
            </div>
        </a>
    </div>
</div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // استخدام الألوان من قاعدة البيانات
    const primaryColor = '{{ $company->primary_color ?? '#3b82f6' }}';
    const secondaryColor = '{{ $company->secondary_color ?? '#8b5cf6' }}';
    const successColor = '{{ $company->success_color ?? '#10b981' }}';
    const warningColor = '{{ $company->warning_color ?? '#f59e0b' }}';
    const dangerColor = '{{ $company->danger_color ?? '#ef4444' }}';

    // Claims over time chart (keeping existing chart logic)
    const claimsCtx = document.getElementById('claimsChart').getContext('2d');
    const claimsChart = new Chart(claimsCtx, {
        type: 'line',
        data: {
            labels: [
                @foreach($stats['claims_stats']['by_month'] as $month)
                    '{{ $month['month'] }}',
                @endforeach
            ],
            datasets: [{
                label: '{{ t($company->translation_group . '.number_of_claims', 'عدد المطالبات') }}',
                data: [
                    @foreach($stats['claims_stats']['by_month'] as $month)
                        {{ $month['count'] }},
                    @endforeach
                ],
                borderColor: primaryColor,
                backgroundColor: primaryColor + '20',
                tension: 0.4,
                fill: true,
                borderWidth: 3,
                pointBackgroundColor: primaryColor,
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0,
                        color: '#6B7280'
                    },
                    grid: {
                        color: '#F3F4F6'
                    }
                },
                x: {
                    ticks: {
                        color: '#6B7280'
                    },
                    grid: {
                        color: '#F3F4F6'
                    }
                }
            }
        }
    });

    // Claims by status chart (keeping existing chart logic)
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    const statusChart = new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: [
                '{{ t($company->translation_group . '.pending', 'معلق') }}',
                '{{ t($company->translation_group . '.approved', 'مقبول') }}',
                '{{ t($company->translation_group . '.rejected', 'مرفوض') }}',
                '{{ t($company->translation_group . '.under_review', 'قيد المراجعة') }}'
            ],
            datasets: [{
                data: [
                    {{ $stats['claims_stats']['pending_claims'] }},
                    {{ $stats['claims_stats']['approved_claims'] }},
                    {{ $stats['claims_stats']['rejected_claims'] }},
                    {{ $stats['claims_stats']['under_review'] }}
                ],
                backgroundColor: [
                    warningColor,
                    successColor,
                    dangerColor,
                    primaryColor
                ],
                borderWidth: 0,
                hoverOffset: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        color: '#6B7280',
                        font: {
                            size: 12
                        }
                    }
                }
            }
        }
    });
});
</script>
@endsection
