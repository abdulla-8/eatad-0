{{-- resources/views/insurance/users/show.blade.php --}}
@extends('insurance.layouts.app')

@section('title', t($company->translation_group . '.user_details') . ' - ' . $user->full_name)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('insurance.users.index', $company->company_slug) }}" 
           class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <div class="flex-1">
            <h1 class="text-3xl font-bold text-gray-900">{{ t($company->translation_group . '.user_details') }}</h1>
            <p class="text-gray-600 mt-1">{{ t($company->translation_group . '.user_info_and_stats') }} {{ $user->full_name }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('insurance.users.edit', [$company->company_slug, $user->id]) }}" 
               class="inline-flex items-center gap-2 px-4 py-2 text-white rounded-lg font-medium hover:opacity-90 transition-opacity"
               style="background: {{ $company->primary_color }};">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                {{ t($company->translation_group . '.edit_data') }}
            </a>
            <button onclick="toggleUserStatus({{ $user->id }})" 
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium transition-colors {{ $user->is_active ? 'bg-red-500 hover:bg-red-600 text-white' : 'bg-green-500 hover:bg-green-600 text-white' }}">
                @if($user->is_active)
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                    </svg>
                    {{ t($company->translation_group . '.deactivate') }}
                @else
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ t($company->translation_group . '.activate_account') }}
                @endif
            </button>
        </div>
    </div>

    <!-- User Info Card -->
    <div class="bg-white rounded-xl shadow-sm border">
        <div class="p-6">
            <div class="flex items-start gap-6">
                <div class="w-20 h-20 rounded-xl flex items-center justify-center text-white font-bold text-2xl"
                     style="background: {{ $company->primary_color }};">
                    {{ substr($user->full_name, 0, 1) }}
                </div>
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <h2 class="text-2xl font-bold text-gray-900">{{ $user->full_name }}</h2>
                        @if($user->is_active)
                            <span class="px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                {{ t($company->translation_group . '.active') }}
                            </span>
                        @else
                            <span class="px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                {{ t($company->translation_group . '.inactive') }}
                            </span>
                        @endif
                    </div>
                    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div>
                            <p class="text-sm text-gray-600">{{ t($company->translation_group . '.phone_number') }}</p>
                            <p class="font-medium text-gray-900">{{ $user->phone }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">{{ t($company->translation_group . '.national_id') }}</p>
                            <p class="font-medium text-gray-900">{{ $user->national_id }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">{{ t($company->translation_group . '.policy_number') }}</p>
                            <p class="font-medium text-gray-900">{{ $user->policy_number }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">{{ t($company->translation_group . '.registration_date') }}</p>
                            <p class="font-medium text-gray-900">{{ $user->created_at->format('Y-m-d') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Claims Statistics -->
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-4">
        <div class="bg-white rounded-xl shadow-sm border p-4">
            <div class="text-center">
                <div class="text-2xl font-bold text-blue-600">{{ $claimsStats['total'] }}</div>
                <div class="text-sm text-gray-600">{{ t($company->translation_group . '.total_claims') }}</div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border p-4">
            <div class="text-center">
                <div class="text-2xl font-bold text-yellow-600">{{ $claimsStats['pending'] }}</div>
                <div class="text-sm text-gray-600">{{ t($company->translation_group . '.pending') }}</div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border p-4">
            <div class="text-center">
                <div class="text-2xl font-bold text-green-600">{{ $claimsStats['approved'] }}</div>
                <div class="text-sm text-gray-600">{{ t($company->translation_group . '.approved') }}</div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border p-4">
            <div class="text-center">
                <div class="text-2xl font-bold text-red-600">{{ $claimsStats['rejected'] }}</div>
                <div class="text-sm text-gray-600">{{ t($company->translation_group . '.rejected') }}</div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border p-4">
            <div class="text-center">
                <div class="text-2xl font-bold text-purple-600">{{ $claimsStats['in_progress'] }}</div>
                <div class="text-sm text-gray-600">{{ t($company->translation_group . '.in_progress') }}</div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border p-4">
            <div class="text-center">
                <div class="text-2xl font-bold text-gray-600">{{ $claimsStats['completed'] }}</div>
                <div class="text-sm text-gray-600">{{ t($company->translation_group . '.completed') }}</div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border p-4">
            <div class="text-center">
                <div class="text-2xl font-bold text-indigo-600">{{ $claimsStats['this_month'] }}</div>
                <div class="text-sm text-gray-600">{{ t($company->translation_group . '.this_month') }}</div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border p-4">
            <div class="text-center">
                <div class="text-2xl font-bold text-teal-600">{{ $claimsStats['this_year'] }}</div>
                <div class="text-sm text-gray-600">{{ t($company->translation_group . '.this_year') }}</div>
            </div>
        </div>
    </div>

    <!-- Charts and Recent Claims -->
    <div class="grid lg:grid-cols-2 gap-6">
        <!-- Monthly Claims Chart -->
        <div class="bg-white rounded-xl shadow-sm border">
            <div class="p-6 border-b">
                <h3 class="text-lg font-bold text-gray-900">{{ t($company->translation_group . '.monthly_claims') }}</h3>
                <p class="text-sm text-gray-600">{{ t($company->translation_group . '.last_12_months') }}</p>
            </div>
            <div class="p-6">
                <canvas id="monthlyChart" width="400" height="200"></canvas>
            </div>
        </div>

        <!-- Recent Claims -->
        <div class="bg-white rounded-xl shadow-sm border">
            <div class="p-6 border-b">
                <h3 class="text-lg font-bold text-gray-900">{{ t($company->translation_group . '.recent_claims') }}</h3>
                <p class="text-sm text-gray-600">{{ t($company->translation_group . '.last_5_claims') }}</p>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($recentClaims as $claim)
                    <div class="p-4 hover:bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="font-medium text-gray-900">{{ $claim->claim_number }}</div>
                                <div class="text-sm text-gray-600">{{ $claim->policy_number }}</div>
                            </div>
                            <div class="text-right">
                                <span class="px-2 py-1 rounded-full text-xs font-medium {{ $claim->status_badge['class'] }}">
                                    {{ t($company->translation_group . '.' . $claim->status) }}
                                </span>
                                <div class="text-xs text-gray-500 mt-1">{{ $claim->created_at->format('M d, Y') }}</div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-500">
                        {{ t($company->translation_group . '.no_claims') }}
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Activity Timeline -->
    <div class="bg-white rounded-xl shadow-sm border">
        <div class="p-6 border-b">
            <h3 class="text-lg font-bold text-gray-900">{{ t($company->translation_group . '.recent_activity') }}</h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <div class="flex items-center gap-4">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="font-medium text-gray-900">{{ t($company->translation_group . '.account_created') }}</p>
                        <p class="text-sm text-gray-600">{{ $user->created_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
                
                @if($claimsStats['total'] > 0)
                    <div class="flex items-center gap-4">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">{{ t($company->translation_group . '.first_claim') }}</p>
                            <p class="text-sm text-gray-600">{{ $recentClaims->last()?->created_at->format('M d, Y H:i') ?? t($company->translation_group . '.not_specified') }}</p>
                        </div>
                    </div>
                @endif
                
                @if($recentClaims->first())
                    <div class="flex items-center gap-4">
                        <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">{{ t($company->translation_group . '.last_claim') }}</p>
                            <p class="text-sm text-gray-600">{{ $recentClaims->first()->created_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Monthly Claims Chart
const ctx = document.getElementById('monthlyChart').getContext('2d');
const monthlyChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: @json(array_column($monthlyStats, 'month')),
        datasets: [{
            label: '{{ t($company->translation_group . '.claims_count') }}',
            data: @json(array_column($monthlyStats, 'count')),
            borderColor: '{{ $company->primary_color }}',
            backgroundColor: '{{ $company->primary_color }}20',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});

// Toggle User Status Function
function toggleUserStatus(userId) {
    if (!confirm('{{ t($company->translation_group . '.confirm_status_change') }}')) {
        return;
    }

    fetch(`{{ route('insurance.users.toggle-status', [$company->company_slug, '__USER_ID__']) }}`.replace('__USER_ID__', userId), {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        alert('{{ t($company->translation_group . '.status_change_error') }}');
    });
}
</script>

@endsection
