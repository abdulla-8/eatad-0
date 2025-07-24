{{-- resources/views/complaints/index.blade.php --}}
@extends($userType === 'insurance_company' ? 'insurance.layouts.app' : ($userType === 'service_center' ? 'service-center.layouts.app' : 'insurance-user.layouts.app'))

@section('title', t($translationGroup . '.complaints_inquiries'))

@section('content')
@php
    // تحديد الـ company slug بشكل موحد
    $companySlug = '';
    if ($userType === 'insurance_company') {
        $companySlug = $user->company_slug ?? ($user->company->company_slug ?? 'default');
    } elseif ($userType === 'insurance_user') {
        $companySlug = optional($user->company)->company_slug ?? 'default';
    }
@endphp

<div class="space-y-6">
    <!-- Header with Stats -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ t($translationGroup . '.complaints_inquiries') }}</h1>
            <p class="text-gray-600 mt-1">{{ t($translationGroup . '.manage_complaints_inquiries') }}</p>
            
            <!-- Show company name for insurance users -->
            @if($userType === 'insurance_user' && $user->company)
                <p class="text-sm text-blue-600 mt-2">{{ $user->company->legal_name ?? $user->company->commercial_name }}</p>
            @endif
        </div>
        
        <button onclick="openComplaintModal()"  style="background-color: {{ $profileData['colors']['primary'] ?? '#001100' }};"
                class="inline-flex items-center gap-2 px-6 py-3  text-white rounded-lg font-medium hover:bg-black transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            {{ t($translationGroup . '.add_new_complaint') }}
        </button>
    </div>

    <!-- Quick Stats -->
    <div class="md:grid grid-cols-2 lg:grid-cols-6 gap-3">
        <div class="bg-white rounded-lg border px-4 py-3 text-center shadow-sm mb-4">
            <div class="text-2xl font-bold text-blue-600">{{ $stats['total'] }}</div>
            <div class="text-xs text-gray-600">{{ t($translationGroup . '.total') }}</div>
        </div>
        <div class="bg-white rounded-lg border px-4 py-3 text-center shadow-sm mb-4">
            <div class="text-2xl font-bold text-red-600">{{ $stats['unread'] }}</div>
            <div class="text-xs text-gray-600">{{ t($translationGroup . '.unread') }}</div>
        </div>
        <div class="bg-white rounded-lg border px-4 py-3 text-center shadow-sm mb-4">
            <div class="text-2xl font-bold text-green-600">{{ $stats['read'] }}</div>
            <div class="text-xs text-gray-600">{{ t($translationGroup . '.read') }}</div>
        </div>
        <div class="bg-white rounded-lg border px-4 py-3 text-center shadow-sm mb-4">
            <div class="text-2xl font-bold text-blue-600">{{ $stats['inquiry'] }}</div>
            <div class="text-xs text-gray-600">{{ t($translationGroup . '.inquiries') }}</div>
        </div>
        <div class="bg-white rounded-lg border px-4 py-3 text-center shadow-sm mb-4">
            <div class="text-2xl font-bold text-yellow-600">{{ $stats['complaint'] }}</div>
            <div class="text-xs text-gray-600">{{ t($translationGroup . '.complaints') }}</div>
        </div>
        <div class="bg-white rounded-lg border px-4 py-3 text-center shadow-sm mb-4">
            <div class="text-2xl font-bold text-gray-600">{{ $stats['other'] }}</div>
            <div class="text-xs text-gray-600">{{ t($translationGroup . '.other') }}</div>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="bg-white rounded-xl shadow-sm border">
        <div class="p-6">
            <form method="GET" class="flex flex-col lg:flex-row gap-4">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ t($translationGroup . '.search') }}</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 {{ app()->getLocale() === 'ar' ? 'right-0 pr-3' : 'left-0 pl-3' }} flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="{{ t($translationGroup . '.search_placeholder') }}"
                               class="w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ app()->getLocale() === 'ar' ? 'pr-10' : 'pl-10' }} py-2.5">
                    </div>
                </div>
                
                <div class="lg:w-48">
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ t($translationGroup . '.request_type') }}</label>
                    <select name="type" class="w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent px-4 py-2.5">
                        <option value="">{{ t($translationGroup . '.all_types') }}</option>
                        <option value="inquiry" {{ request('type') === 'inquiry' ? 'selected' : '' }}>{{ t($translationGroup . '.inquiry') }}</option>
                        <option value="complaint" {{ request('type') === 'complaint' ? 'selected' : '' }}>{{ t($translationGroup . '.complaint') }}</option>
                        <option value="other" {{ request('type') === 'other' ? 'selected' : '' }}>{{ t($translationGroup . '.other') }}</option>
                    </select>
                </div>
                
                <div class="lg:w-48">
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ t($translationGroup . '.status') }}</label>
                    <select name="status" class="w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent px-4 py-2.5">
                        <option value="">{{ t($translationGroup . '.all_status') }}</option>
                        <option value="unread" {{ request('status') === 'unread' ? 'selected' : '' }}>{{ t($translationGroup . '.unread') }}</option>
                        <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>{{ t($translationGroup . '.read') }}</option>
                    </select>
                </div>
                
                <div class="flex gap-2 lg:items-end">
                    <button  style="background-color: {{ $profileData['colors']['primary'] ?? '#000' }};" type="submit" class="px-6 py-2.5  text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">
                        <svg class="w-4 h-4 inline {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        {{ t($translationGroup . '.filter') }}
                    </button>
                    
                    @if(request()->hasAny(['status', 'type', 'search']))
                        @if($userType === 'insurance_company')
                            <a href="{{ route('insurance.complaints.index', ['companyRoute' => $companySlug]) }}" 
                               class="px-6 py-2.5 bg-gray-500 text-white rounded-lg font-medium hover:bg-gray-600 transition-colors">
                                {{ t($translationGroup . '.clear') }}
                            </a>
                        @elseif($userType === 'insurance_user')
                            <a href="{{ route('insurance.user.complaints.index', ['companySlug' => $companySlug]) }}" 
                               class="px-6 py-2.5 bg-gray-500 text-white rounded-lg font-medium hover:bg-gray-600 transition-colors">
                                {{ t($translationGroup . '.clear') }}
                            </a>
                        @else
                            <a href="{{ route('service-center.complaints.index') }}" 
                               class="px-6 py-2.5 bg-gray-500 text-white rounded-lg font-medium hover:bg-gray-600 transition-colors">
                                {{ t($translationGroup . '.clear') }}
                            </a>
                        @endif
                    @endif
                </div>
            </form>
        </div>
    </div>

  @php
    $isRtl = app()->getLocale() === 'ar';
@endphp

<!-- Complaints List -->
@if($complaints->count())
    <div class="space-y-4">
        @foreach($complaints as $complaint)
            <div class="bg-white rounded-xl shadow-sm border hover:shadow-md transition-all duration-300 {{ !$complaint->is_read ? 'ring-2 ring-red-100' : '' }}">
                <div class="p-4 md:p-6">
                    <!-- الصف الأول: أيقونة الشكوى + العنوان + نوع الشكوى وحالتها -->
                    <div class="flex items-start justify-between mb-4">
                        <!-- الأيقونة والعنوان -->
                        <div class="flex items-center gap-3 flex-1 min-w-0 ">
                            <!-- أيقونة الشكوى -->
                            <div class="relative flex-shrink-0">
                                <svg class="w-10 h-10 md:w-12 md:h-12 drop-shadow-lg hover:scale-105 transition-transform" viewBox="0 0 24 24">
                                    <defs>
                                        <linearGradient id="warningGradient{{ $complaint->id }}" x1="0%" y1="0%" x2="100%" y2="100%">
                                            <stop offset="0%" style="stop-color:#FF6B6B;stop-opacity:1" />
                                            <stop offset="50%" style="stop-color:#FF8E53;stop-opacity:1" />
                                            <stop offset="100%" style="stop-color:#FF6B35;stop-opacity:1" />
                                        </linearGradient>
                                        <filter id="glow{{ $complaint->id }}">
                                            <feGaussianBlur stdDeviation="2" result="coloredBlur"/>
                                            <feMerge> 
                                                <feMergeNode in="coloredBlur"/>
                                                <feMergeNode in="SourceGraphic"/>
                                            </feMerge>
                                        </filter>
                                    </defs>
                                    
                                    <!-- الخلفية مع التأثير -->
                                    <path d="M12 2L3 20h18L12 2z" fill="url(#warningGradient{{ $complaint->id }})" opacity="0.9" filter="url(#glow{{ $complaint->id }})"/>
                                    
                                    <!-- الحدود -->
                                    <path stroke="#FFFFFF" stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round"
                                          d="M12 2L3 20h18L12 2z"/>
                                    
                                    <!-- علامة التعجب -->
                                    <line x1="12" y1="9" x2="12" y2="13" stroke="#FFFFFF" stroke-width="2.5" stroke-linecap="round"/>
                                    <circle cx="12" cy="16" r="1.2" fill="#FFFFFF"/>
                                </svg>
                                
                                <!-- مؤشر عدم القراءة -->
                                @if(!$complaint->is_read)
                                    <div class="absolute -top-1 {{ $isRtl ? '-left-1' : '-right-1' }}">
                                        <div class="w-3 h-3 md:w-4 md:h-4 bg-red-500 rounded-full animate-pulse border-2 border-white"></div>
                                    </div>
                                @endif
                            </div>

                            <!-- عنوان الشكوى -->
                            <div class="flex-1 min-w-0">
                                <h3 class="font-bold text-lg md:text-xl {{ !$complaint->is_read ? 'text-red-900' : 'text-gray-900' }} truncate {{ $isRtl ? 'text-right' : 'text-left' }}">
                                    {{ $complaint->subject }}
                                </h3>
                                
                                <!-- معلومات المستخدم للتأمين -->
                                @if($userType === 'insurance_user')
                                    <div class="mt-2 p-2 bg-blue-50 rounded-lg border border-blue-200">
                                        <div class="flex flex-wrap items-center gap-2 text-xs {{ $isRtl ? 'flex-row-reverse' : '' }}">
                                            <span class="text-blue-700 font-medium">{{ t($translationGroup . '.complainant') }}:</span>
                                            <span class="text-blue-800">{{ $user->full_name }}</span>
                                            <span class="text-blue-400">•</span>
                                            <span class="text-blue-600">{{ $user->policy_number }}</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- النوع وحالة القراءة - جنباً إلى جنب -->
                        <div class="flex items-center gap-2 {{ $isRtl ? 'mr-3 flex-row-reverse' : 'ml-3' }}">
                            <span class="px-2 md:px-3 py-1 md:py-1.5 rounded-full text-xs md:text-sm font-medium border {{ $complaint->type_badge['class'] ?? 'bg-gray-100 text-gray-800' }} whitespace-nowrap">
                                {{ t($translationGroup . '.' . $complaint->type) }}
                            </span>
                            <span class="px-2 md:px-3 py-1 md:py-1.5 rounded-full text-xs md:text-sm font-medium border {{ $complaint->is_read ? 'bg-green-100 text-green-800 border-green-300' : 'bg-red-100 text-red-800 border-red-300' }} whitespace-nowrap">
                                {{ $complaint->is_read ? t($translationGroup . '.read') : t($translationGroup . '.unread') }}
                            </span>
                            
                            @if($userType === 'insurance_user')
                                <span class="px-2 md:px-3 py-1 md:py-1.5 rounded-full text-xs md:text-sm font-medium border {{ $complaint->complainant_type_badge['class'] ?? 'bg-indigo-100 text-indigo-800' }} whitespace-nowrap">
                                    {{ $complaint->complainant_type_badge['text'] ?? t($translationGroup . '.insurance_user') }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- الصف الثاني: التاريخ -->
                    <div class="flex items-center gap-2 mb-4 {{ $isRtl ? 'flex-row' : '' }}">
                      
                        
                            <div class="w-7 h-7 md:w-8 md:h-8 rounded-lg bg-gradient-to-br from-blue-50 to-blue-100 flex items-center justify-center border border-blue-200 shadow-sm">
                                <svg class="w-4 h-4 md:w-5 md:h-5" viewBox="0 0 24 24">
                                    <defs>
                                        <linearGradient id="dateGradient{{ $complaint->id }}" x1="0%" y1="0%" x2="100%" y2="100%">
                                            <stop offset="0%" style="stop-color:#3B82F6;stop-opacity:1" />
                                            <stop offset="100%" style="stop-color:#1D4ED8;stop-opacity:1" />
                                        </linearGradient>
                                    </defs>
                                    <rect x="3" y="6" width="18" height="15" rx="2" fill="url(#dateGradient{{ $complaint->id }})" opacity="0.8"/>
                                    <path d="M8 2v4M16 2v4" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round"/>
                                    <path d="M3 10h18" stroke="#FFFFFF" stroke-width="2"/>
                                    <circle cx="9" cy="14" r="1" fill="#FFFFFF"/>
                                    <circle cx="15" cy="14" r="1" fill="#FFFFFF"/>
                                    <circle cx="9" cy="18" r="1" fill="#FFFFFF"/>
                                    <circle cx="15" cy="18" r="1" fill="#FFFFFF"/>
                                </svg>
                            </div>
                          <span class="text-sm md:text-base text-gray-600 font-medium">
                                {{ $complaint->created_at->format('d/m/Y H:i') }}
                            </span>
                    </div>

                    <!-- الصف الثالث: الوصف -->
                    <div class="mb-4">
                        <div class="p-3 md:p-4 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100 transition-colors">
                            <p class="text-gray-700 text-sm md:text-base leading-relaxed {{ $isRtl ? 'text-right' : 'text-left' }}">
                                {{ Str::limit($complaint->description, 200) }}
                            </p>
                        </div>
                    </div>

                    <!-- الصف الرابع: المرفقات (إن وجدت) -->
                    @if($complaint->attachment_path)
                        <div class="flex items-center gap-2 mb-4 {{ $isRtl ? 'flex-row' : '' }}">
                        
                                <div class="w-7 h-7 md:w-8 md:h-8 rounded-lg bg-gradient-to-br from-emerald-50 to-emerald-100 flex items-center justify-center border border-emerald-200 shadow-sm">
                                    <svg class="w-4 h-4 md:w-5 md:h-5" viewBox="0 0 24 24">
                                        <defs>
                                            <linearGradient id="attachGradient{{ $complaint->id }}" x1="0%" y1="0%" x2="100%" y2="100%">
                                                <stop offset="0%" style="stop-color:#10B981;stop-opacity:1" />
                                                <stop offset="100%" style="stop-color:#047857;stop-opacity:1" />
                                            </linearGradient>
                                        </defs>
                                        <path d="M21.44 11.05l-9.19 9.19a6 6 0 01-8.49-8.49l9.19-9.19a4 4 0 015.66 5.66l-9.2 9.19a2 2 0 01-2.83-2.83l8.49-8.48" 
                                              stroke="url(#attachGradient{{ $complaint->id }})" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <span class="text-sm md:text-base text-emerald-600 font-medium flex items-center gap-1">
                                    {{ t($translationGroup . '.has_attachment') }}
                                    <svg class="w-3 h-3 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </span>
                         
                        </div>
                    @endif

                    <!-- الصف الأخير: الإجراءات -->
                    <div class="flex flex-wrap gap-2 pt-4 border-t border-gray-200  justify-end">
                        <!-- Edit Button -->
                        @if($userType === 'insurance_company')
                            <a href="{{ route('insurance.complaints.edit', ['companyRoute' => $companySlug, 'id' => $complaint->id]) }}" 
                               class="inline-flex items-center gap-2 px-3 md:px-4 py-2 bg-yellow-50 text-yellow-700 rounded-lg font-medium hover:bg-yellow-100 hover:shadow-md transition-all duration-200 border border-yellow-200 text-sm md:text-base {{ $isRtl ? 'flex-row-reverse' : '' }}">
                                @if($isRtl)
                                    <span class="hidden sm:inline">{{ t($translationGroup . '.edit') }}</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                @else
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    <span class="hidden sm:inline">{{ t($translationGroup . '.edit') }}</span>
                                @endif
                            </a>
                        @elseif($userType === 'insurance_user')
                            <a href="{{ route('insurance.user.complaints.edit', ['companySlug' => $companySlug, 'id' => $complaint->id]) }}" 
                               class="inline-flex items-center gap-2 px-3 md:px-4 py-2 bg-yellow-50 text-yellow-700 rounded-lg font-medium hover:bg-yellow-100 hover:shadow-md transition-all duration-200 border border-yellow-200 text-sm md:text-base {{ $isRtl ? 'flex-row-reverse' : '' }}">
                                @if($isRtl)
                                    <span class="hidden sm:inline">{{ t($translationGroup . '.edit') }}</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                @else
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    <span class="hidden sm:inline">{{ t($translationGroup . '.edit') }}</span>
                                @endif
                            </a>
                        @else
                            <a href="{{ route('service-center.complaints.edit', $complaint->id) }}" 
                               class="inline-flex items-center gap-2 px-3 md:px-4 py-2 bg-yellow-50 text-yellow-700 rounded-lg font-medium hover:bg-yellow-100 hover:shadow-md transition-all duration-200 border border-yellow-200 text-sm md:text-base {{ $isRtl ? 'flex-row-reverse' : '' }}">
                                @if($isRtl)
                                    <span class="hidden sm:inline">{{ t($translationGroup . '.edit') }}</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                @else
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    <span class="hidden sm:inline">{{ t($translationGroup . '.edit') }}</span>
                                @endif
                            </a>
                        @endif
                        
                        <!-- View Button -->
                        @if($userType === 'insurance_company')
                            <a href="{{ route('insurance.complaints.show', ['companyRoute' => $companySlug, 'id' => $complaint->id]) }}" 
                               class="inline-flex items-center gap-2 px-3 md:px-4 py-2 bg-blue-50 text-blue-700 rounded-lg font-medium hover:bg-blue-100 hover:shadow-md transition-all duration-200 border border-blue-200 text-sm md:text-base {{ $isRtl ? 'flex-row-reverse' : '' }}">
                                @if($isRtl)
                                    <svg class="w-4 h-4 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                    <span>{{ t($translationGroup . '.read_more') }}</span>
                                @else
                                    <span>{{ t($translationGroup . '.read_more') }}</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                @endif
                            </a>
                        @elseif($userType === 'insurance_user')
                            <a href="{{ route('insurance.user.complaints.show', ['companySlug' => $companySlug, 'id' => $complaint->id]) }}" 
                               class="inline-flex items-center gap-2 px-3 md:px-4 py-2 bg-blue-50 text-blue-700 rounded-lg font-medium hover:bg-blue-100 hover:shadow-md transition-all duration-200 border border-blue-200 text-sm md:text-base {{ $isRtl ? 'flex-row-reverse' : '' }}">
                                @if($isRtl)
                                    <svg class="w-4 h-4 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                    <span>{{ t($translationGroup . '.read_more') }}</span>
                                @else
                                    <span>{{ t($translationGroup . '.read_more') }}</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                @endif
                            </a>
                        @else
                            <a href="{{ route('service-center.complaints.show', $complaint->id) }}" 
                               class="inline-flex items-center gap-2 px-3 md:px-4 py-2 bg-blue-50 text-blue-700 rounded-lg font-medium hover:bg-blue-100 hover:shadow-md transition-all duration-200 border border-blue-200 text-sm md:text-base {{ $isRtl ? 'flex-row-reverse' : '' }}">
                                @if($isRtl)
                                    <svg class="w-4 h-4 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                    <span>{{ t($translationGroup . '.read_more') }}</span>
                                @else
                                    <span>{{ t($translationGroup . '.read_more') }}</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                @endif
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="flex justify-center mt-6">
        {{ $complaints->withQueryString()->links() }}
    </div>
@else
    <!-- Empty State -->
    <div class="bg-white rounded-xl shadow-sm border">
        <div class="p-8 md:p-12 text-center">
            <div class="w-12 h-12 md:w-16 md:h-16 mx-auto mb-4 rounded-full flex items-center justify-center bg-blue-50">
                <svg class="w-6 h-6 md:w-8 md:h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">{{ t($translationGroup . '.no_complaints_found') }}</h3>
            <p class="text-gray-600">{{ t($translationGroup . '.no_complaints_description') }}</p>
        </div>
    </div>
@endif

</div>

<!-- Enhanced Add Complaint Modal -->
<div id="complaintModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-3xl w-full max-h-[90vh] overflow-y-auto shadow-2xl">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900">{{ t($translationGroup . '.add_new_complaint') }}</h3>
            </div>
            <button onclick="closeModal('complaintModal')" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        @if($userType === 'insurance_company')
            <form id="complaintForm" method="POST" action="{{ route('insurance.complaints.store', ['companyRoute' => $companySlug]) }}" enctype="multipart/form-data" class="p-6">
        @elseif($userType === 'insurance_user')
            <form id="complaintForm" method="POST" action="{{ route('insurance.user.complaints.store', ['companySlug' => $companySlug]) }}" enctype="multipart/form-data" class="p-6">
        @else
            <form id="complaintForm" method="POST" action="{{ route('service-center.complaints.store') }}" enctype="multipart/form-data" class="p-6">
        @endif
            @csrf
            
            <!-- Show user info for insurance users -->
            @if($userType === 'insurance_user')
                <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <h4 class="font-medium text-blue-900 mb-2">{{ t($translationGroup . '.complainant_info') }}</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-blue-700 font-medium">{{ t($translationGroup . '.name') }}:</span>
                            <span class="text-blue-800">{{ $user->full_name }}</span>
                        </div>
                        <div>
                            <span class="text-blue-700 font-medium">{{ t($translationGroup . '.policy_number') }}:</span>
                            <span class="text-blue-800">{{ $user->policy_number }}</span>
                        </div>
                        <div>
                            <span class="text-blue-700 font-medium">{{ t($translationGroup . '.phone') }}:</span>
                            <span class="text-blue-800">{{ $user->phone }}</span>
                        </div>
                        <div>
                            <span class="text-blue-700 font-medium">{{ t($translationGroup . '.company') }}:</span>
                            <span class="text-blue-800">{{ optional($user->company)->legal_name ?? 'غير محدد' }}</span>
                        </div>
                    </div>
                </div>
            @endif
            
            <div class="space-y-6">
                <!-- Type Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">{{ t($translationGroup . '.type') }} *</label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <label class="relative cursor-pointer">
                            <input type="radio" name="type" value="inquiry" class="sr-only" required>
                            <div class="p-4 border-2 border-gray-300 rounded-lg hover:border-blue-300 transition-colors type-option">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">{{ t($translationGroup . '.inquiry') }}</div>
                                        <div class="text-sm text-gray-500">{{ t($translationGroup . '.inquiry_desc') }}</div>
                                    </div>
                                </div>
                            </div>
                        </label>
                        
                        <label class="relative cursor-pointer">
                            <input type="radio" name="type" value="complaint" class="sr-only" required>
                            <div class="p-4 border-2 border-gray-300 rounded-lg hover:border-blue-300 transition-colors type-option">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">{{ t($translationGroup . '.complaint') }}</div>
                                        <div class="text-sm text-gray-500">{{ t($translationGroup . '.complaint_desc') }}</div>
                                    </div>
                                </div>
                            </div>
                        </label>
                        
                        <label class="relative cursor-pointer">
                            <input type="radio" name="type" value="other" class="sr-only" required>
                            <div class="p-4 border-2 border-gray-300 rounded-lg hover:border-blue-300 transition-colors type-option">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">{{ t($translationGroup . '.other') }}</div>
                                        <div class="text-sm text-gray-500">{{ t($translationGroup . '.other_desc') }}</div>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>
                
                <!-- Subject -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ t($translationGroup . '.subject') }} *</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 {{ app()->getLocale() === 'ar' ? 'right-0 pr-3' : 'left-0 pl-3' }} flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                            </svg>
                        </div>
                        <input type="text" name="subject" required 
                               class="w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ app()->getLocale() === 'ar' ? 'pr-10' : 'pl-10' }} py-2.5"
                               placeholder="{{ t($translationGroup . '.subject_placeholder') }}">
                    </div>
                </div>
                
                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ t($translationGroup . '.description') }} *</label>
                    <textarea name="description" rows="4" required 
                              class="w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent px-4 py-2.5 resize-none"
                              placeholder="{{ t($translationGroup . '.description_placeholder') }}"></textarea>
                </div>
                
                <!-- Enhanced File Upload -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ t($translationGroup . '.attachment') }}</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition-colors">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                    <span>{{ t($translationGroup . '.upload_file') }}</span>
                                    <input id="file-upload" name="attachment" type="file" class="sr-only" accept=".jpeg,.png,.jpg,.pdf,.doc,.docx" onchange="handleFilePreview(this)">
                                </label>
                                <p class="pl-1">{{ t($translationGroup . '.drag_drop') }}</p>
                            </div>
                            <p class="text-xs text-gray-500">{{ t($translationGroup . '.attachment_note') }}</p>
                        </div>
                    </div>
                    
                    <!-- File Preview -->
                    <div id="filePreview" class="hidden mt-3">
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="text-sm font-medium text-gray-900" id="fileName"></p>
                                <p class="text-sm text-gray-500" id="fileSize"></p>
                            </div>
                            <button type="button" onclick="removeFile()" class="ml-3 text-red-400 hover:text-red-600">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Modal Actions -->
            <div class="flex gap-4 pt-6 border-t border-gray-200 mt-6">
                <button  style="background-color: {{ $profileData['colors']['primary'] ?? '#000' }};" type="submit" class="flex-1 inline-flex justify-center items-center gap-2 py-3  text-white rounded-lg font-medium hover:bg-black transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                    {{ t($translationGroup . '.submit_complaint') }}
                </button>
                <button type="button" onclick="closeModal('complaintModal')" 
                        class="flex-1 py-3 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition-colors">
                    {{ t($translationGroup . '.cancel') }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openComplaintModal() {
    document.getElementById('complaintModal').classList.remove('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
    // Reset form
    document.getElementById('complaintForm').reset();
    document.getElementById('filePreview').classList.add('hidden');
    // Reset radio buttons styling
    document.querySelectorAll('.type-option').forEach(option => {
        option.classList.remove('border-blue-500', 'bg-blue-50');
        option.classList.add('border-gray-300');
    });
}

// Handle radio button styling
document.querySelectorAll('input[name="type"]').forEach(radio => {
    radio.addEventListener('change', function() {
        document.querySelectorAll('.type-option').forEach(option => {
            option.classList.remove('border-blue-500', 'bg-blue-50');
            option.classList.add('border-gray-300');
        });
        
        if (this.checked) {
            const option = this.nextElementSibling;
            option.classList.remove('border-gray-300');
            option.classList.add('border-blue-500', 'bg-blue-50');
        }
    });
});

// Handle file preview
function handleFilePreview(input) {
    const file = input.files[0];
    const preview = document.getElementById('filePreview');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');
    
    if (file) {
        fileName.textContent = file.name;
        fileSize.textContent = formatFileSize(file.size);
        preview.classList.remove('hidden');
    } else {
        preview.classList.add('hidden');
    }
}

function removeFile() {
    document.getElementById('file-upload').value = '';
    document.getElementById('filePreview').classList.add('hidden');
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// Close modal on outside click
document.getElementById('complaintModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal('complaintModal');
});

// Handle drag and drop
const dropArea = document.querySelector('.border-dashed');
['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    dropArea.addEventListener(eventName, preventDefaults, false);
});

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

['dragenter', 'dragover'].forEach(eventName => {
    dropArea.addEventListener(eventName, highlight, false);
});

['dragleave', 'drop'].forEach(eventName => {
    dropArea.addEventListener(eventName, unhighlight, false);
});

function highlight(e) {
    dropArea.classList.add('border-blue-400', 'bg-blue-50');
}

function unhighlight(e) {
    dropArea.classList.remove('border-blue-400', 'bg-blue-50');
}

dropArea.addEventListener('drop', handleDrop, false);

function handleDrop(e) {
    const dt = e.dataTransfer;
    const files = dt.files;
    
    if (files.length > 0) {
        document.getElementById('file-upload').files = files;
        handleFilePreview(document.getElementById('file-upload'));
    }
}
</script>

@endsection
