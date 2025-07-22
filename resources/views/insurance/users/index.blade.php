@extends('insurance.layouts.app')

@section('title', t($company->translation_group . '.users_management'))

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="md:flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ t($company->translation_group . '.users_management') }}</h1>
            <p class="text-gray-600 mt-1">{{ t($company->translation_group . '.manage_company_users') }} {{ $company->legal_name }}</p>
        </div>
        <a href="{{ route('insurance.users.create', $company->company_slug) }}" 
           class="inline-flex items-center gap-2 px-6 py-3 text-white rounded-lg font-medium hover:opacity-90 transition-opacity"
           style="background: {{ $company->primary_color }};">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            {{ t($company->translation_group . '.add_new_user') }}
        </a>
    </div>

    <!-- Stats -->
    <div class="md:grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg flex items-center justify-center"
                     style="background: {{ $company->primary_color }}20;">
                    <svg class="w-6 h-6" style="color: {{ $company->primary_color }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                    <p class="text-sm text-gray-600">{{ t($company->translation_group . '.total_users') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['active'] }}</p>
                    <p class="text-sm text-gray-600">{{ t($company->translation_group . '.active') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['inactive'] }}</p>
                    <p class="text-sm text-gray-600">{{ t($company->translation_group . '.inactive') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 12v-2m-6 2h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['this_month'] }}</p>
                    <p class="text-sm text-gray-600">{{ t($company->translation_group . '.this_month') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border">
        <div class="p-6">
            <form method="GET" class="flex flex-col lg:flex-row gap-4">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ t($company->translation_group . '.search') }}</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="{{ t($company->translation_group . '.search_placeholder') }}"
                           class="w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent px-4 py-2.5">
                </div>
                <div class="flex gap-2 lg:items-end">
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">
                        {{ t($company->translation_group . '.filter') }}
                    </button>
                    @if(request()->has('search'))
                        <a href="{{ route('insurance.users.index', $company->company_slug) }}" 
                           class="px-6 py-2.5 bg-gray-500 text-white rounded-lg font-medium hover:bg-gray-600 transition-colors">
                            {{ t($company->translation_group . '.clear') }}
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Users List -->
    @if($users->count())
        <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-right text-sm font-medium text-gray-900">{{ t($company->translation_group . '.user') }}</th>
                            <th class="px-6 py-4 text-right text-sm font-medium text-gray-900">{{ t($company->translation_group . '.phone') }}</th>
                            <th class="px-6 py-4 text-right text-sm font-medium text-gray-900">{{ t($company->translation_group . '.national_id') }}</th>
                            <th class="px-6 py-4 text-right text-sm font-medium text-gray-900">{{ t($company->translation_group . '.policy_number') }}</th>
                          
                            <th class="px-6 py-4 text-right text-sm font-medium text-gray-900">{{ t($company->translation_group . '.registration_date') }}</th>
                            <th class="px-6 py-4 text-right text-sm font-medium text-gray-900">{{ t($company->translation_group . '.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold"
                                         style="background: {{ $company->primary_color }};">
                                        {{ substr($user->full_name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $user->full_name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $user->phone }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $user->national_id }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $user->policy_number }}</td>
                
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $user->created_at->format('Y-m-d') }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('insurance.users.show', [$company->company_slug, $user->id]) }}" 
                                       class="p-2 text-gray-600 hover:bg-gray-50 rounded-lg transition-colors"
                                       title="{{ t($company->translation_group . '.view_details') }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>

                                    <a href="{{ route('insurance.users.edit', [$company->company_slug, $user->id]) }}" 
                                       class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                       title="{{ t($company->translation_group . '.edit') }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    
                                    <button onclick="toggleUserStatus({{ $user->id }})" 
                                            class="p-2 {{ $user->is_active ? 'text-red-600 hover:bg-red-50' : 'text-green-600 hover:bg-green-50' }} rounded-lg transition-colors"
                                            title="{{ $user->is_active ? t($company->translation_group . '.deactivate') : t($company->translation_group . '.activate') }}">
                                        @if($user->is_active)
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
  <path d="M3 6h18" />
  <path d="M8 6v12a2 2 0 002 2h4a2 2 0 002-2V6" />
  <path d="M10 11v6" />
  <path d="M14 11v6" />
  <path d="M5 6l1-3h12l1 3" />
</svg>

                                        @else
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        @endif
                                    </button>

                                    <button onclick="openResetPasswordModal({{ $user->id }})" 
                                            class="p-2 text-yellow-600 hover:bg-yellow-50 rounded-lg transition-colors"
                                            title="{{ t($company->translation_group . '.reset_password') }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="flex justify-center">
            {{ $users->withQueryString()->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-xl shadow-sm border">
            <div class="p-12 text-center">
                <div class="w-20 h-20 mx-auto mb-6 rounded-full flex items-center justify-center"
                     style="background: {{ $company->primary_color }}20;">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: {{ $company->primary_color }};">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ t($company->translation_group . '.no_users_found') }}</h3>
                <p class="text-gray-600 mb-6">{{ t($company->translation_group . '.no_users_description') }}</p>
                <a href="{{ route('insurance.users.create', $company->company_slug) }}" 
                   class="inline-flex items-center gap-2 px-6 py-3 text-white rounded-lg font-medium hover:opacity-90 transition-opacity"
                   style="background: {{ $company->primary_color }};">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    {{ t($company->translation_group . '.add_first_user') }}
                </a>
            </div>
        </div>
    @endif
</div>

<!-- Reset Password Modal -->
<div id="resetPasswordModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-md w-full">
        <div class="p-6 border-b">
            <h3 class="text-xl font-bold">{{ t($company->translation_group . '.reset_password') }}</h3>
        </div>
        <form id="resetPasswordForm" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ t($company->translation_group . '.new_password') }}</label>
                <input type="password" name="new_password" required
                    class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent px-4 py-2.5">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ t($company->translation_group . '.confirm_password') }}</label>
                <input type="password" name="new_password_confirmation" required
                    class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent px-4 py-2.5">
            </div>
            <div class="flex gap-4">
                <button type="submit" class="flex-1 py-3 bg-blue-500 text-white rounded-lg font-medium hover:bg-blue-600 transition-colors">
                    {{ t($company->translation_group . '.update_password') }}
                </button>
                <button type="button" onclick="closeModal('resetPasswordModal')"
                    class="flex-1 py-3 bg-gray-500 text-white rounded-lg font-medium hover:bg-gray-600 transition-colors">
                    {{ t($company->translation_group . '.cancel') }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function showAlert(message, color) {
    let alertDiv = document.createElement('div');
    alertDiv.className = `fixed top-6 left-1/2 transform -translate-x-1/2 z-50 px-6 py-3 rounded shadow-lg text-white font-bold bg-${color}-500`;
    alertDiv.textContent = message;
    document.body.appendChild(alertDiv);
    setTimeout(() => { alertDiv.remove(); }, 3000);
}

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
            showAlert(data.message, 'green');
            setTimeout(() => window.location.reload(), 1000);
        } else {
            showAlert(data.message, 'red');
        }
    })
    .catch(error => {
        showAlert('{{ t($company->translation_group . '.status_change_error') }}', 'red');
    });
}

function openResetPasswordModal(userId) {
    document.getElementById('resetPasswordForm').setAttribute('data-user-id', userId);
    document.getElementById('resetPasswordModal').classList.remove('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

// Reset Password Form
document.getElementById('resetPasswordForm').addEventListener('submit', function(e) {
    e.preventDefault();
    let form = this;
    let userId = form.getAttribute('data-user-id');
    let formData = new FormData(form);
    
    fetch(`{{ route('insurance.users.reset-password', [$company->company_slug, '__USER_ID__']) }}`.replace('__USER_ID__', userId), {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert(data.message, 'green');
            closeModal('resetPasswordModal');
            form.reset();
        } else {
            showAlert(data.message, 'red');
        }
    })
    .catch(error => {
        showAlert('{{ t($company->translation_group . '.password_reset_error') }}', 'red');
    });
});

// Close modal when clicking outside
document.getElementById('resetPasswordModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal('resetPasswordModal');
});
</script>

@endsection
