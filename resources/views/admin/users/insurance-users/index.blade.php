@extends('admin.layouts.app')

@section('title', t('admin.insurance_users_management') . ' - ' . $insuranceCompany->legal_name)

@section('content')

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
    <div>
        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-2">{{ t('admin.insurance_users_management') }}</h1>
        <nav class="flex text-sm">
            <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-gold-600">{{ t('admin.dashboard') }}</a>
            <span class="mx-2 text-gray-400">></span>
            <a href="{{ route('admin.users.insurance-companies.index') }}" class="text-gray-500 hover:text-gold-600">{{ t('admin.insurance_companies') }}</a>
            <span class="mx-2 text-gray-400">></span>
            <span class="text-gold-600 font-medium">{{ t('admin.users') }}</span>
        </nav>
        <div class="mt-2 p-3 bg-blue-50 rounded-lg border border-blue-200">
            <div class="flex items-center">
                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center {{ $isRtl ? 'ml-3' : 'mr-3' }}">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-blue-900">{{ $insuranceCompany->legal_name }}</p>
                    <p class="text-sm text-blue-700">{{ $insuranceCompany->formatted_phone }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="flex items-center space-x-3 {{ $isRtl ? 'space-x-reverse' : '' }} mt-4 sm:mt-0">
        <div class="bg-gray-50 px-3 py-2 rounded-lg border">
            <span class="text-sm text-gray-600">{{ t('admin.total_users') }}: </span>
            <span class="font-bold text-gold-600">{{ $users->total() }}</span>
        </div>
        <a href="{{ route('admin.users.insurance-companies.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
            <svg class="w-4 h-4 inline {{ $isRtl ? 'ml-1' : 'mr-1' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            {{ t('admin.back_to_companies') }}
        </a>
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">{{ t('admin.total_users') }}</p>
                <p class="text-3xl font-bold text-gray-900">{{ $users->total() }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                </svg>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">{{ t('admin.active_users') }}</p>
                <p class="text-3xl font-bold text-green-600">{{ $users->where('is_active', true)->count() }}</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">{{ t('admin.inactive_users') }}</p>
                <p class="text-3xl font-bold text-red-600">{{ $users->where('is_active', false)->count() }}</p>
            </div>
            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">{{ t('admin.registration_rate') }}</p>
                <p class="text-lg font-bold text-purple-600">{{ $users->count() > 0 ? number_format(($users->where('is_active', true)->count() / $users->count()) * 100, 1) : 0 }}%</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="bg-gold-500 text-dark-900 px-6 py-4">
        <h2 class="text-lg font-bold flex items-center">
            <svg class="w-5 h-5 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
            </svg>
            {{ t('admin.insurance_users') }}
        </h2>
    </div>
    
    @if($users->count() > 0)
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-{{ $isRtl ? 'right' : 'left' }} text-xs font-semibold text-gray-600 uppercase">{{ t('admin.user_info') }}</th>
                        <th class="px-6 py-3 text-{{ $isRtl ? 'right' : 'left' }} text-xs font-semibold text-gray-600 uppercase">{{ t('admin.contact_info') }}</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">{{ t('admin.documents') }}</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">{{ t('admin.status') }}</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">{{ t('admin.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div>
                                    <div class="font-semibold text-gray-900">{{ $user->full_name }}</div>
                                    <div class="text-sm text-gray-500">{{ t('admin.member_since') }}: {{ $user->created_at->format('Y/m/d') }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div>
                                    <div class="font-medium text-gray-900">{{ $user->formatted_phone }}</div>
                                    <div class="text-sm text-gray-500">{{ t('admin.national_id') }}: {{ $user->formatted_national_id }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="space-y-1">
                                    <div class="text-sm">
                                        <span class="text-gray-500">{{ t('admin.policy_number') }}:</span>
                                        <span class="font-medium font-mono text-xs bg-gray-100 px-2 py-1 rounded">{{ $user->policy_number }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $user->status_badge['class'] }}">
                                    <div class="w-2 h-2 rounded-full {{ $user->is_active ? 'bg-green-400' : 'bg-red-400' }} {{ $isRtl ? 'ml-1' : 'mr-1' }}"></div>
                                    {{ $user->status_badge['text'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center space-x-2 {{ $isRtl ? 'space-x-reverse' : '' }}">
                                    <form method="POST" action="{{ route('admin.users.insurance-companies.insurance-users.toggle', [$insuranceCompany, $user]) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="p-2 rounded-lg {{ $user->is_active ? 'bg-orange-100 text-orange-600 hover:bg-orange-200' : 'bg-green-100 text-green-600 hover:bg-green-200' }} transition-colors" title="{{ $user->is_active ? t('admin.deactivate') : t('admin.activate') }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                @if($user->is_active)
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                @else
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h8m-10 5a9 9 0 1118 0 9 9 0 01-18 0z"></path>
                                                @endif
                                            </svg>
                                        </button>
                                    </form>

                                    <button onclick="openResetPasswordModal('{{ $user->id }}', '{{ $user->full_name }}')" class="p-2 rounded-lg bg-blue-100 text-blue-600 hover:bg-blue-200 transition-colors" title="{{ t('admin.reset_password') }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-6 6c-3 0-6-1-9-3s-9-3-9-6 6-6 9-6c3 0 6 1 9 3m-2 2a2 2 0 012-2m-2 2a2 2 0 01-2 2m2-2v10a2 2 0 01-2 2H9a2 2 0 01-2-2V9a2 2 0 012-2 2 2 0 012-2"></path>
                                        </svg>
                                    </button>
                                    
                                    <form method="POST" action="{{ route('admin.users.insurance-companies.insurance-users.destroy', [$insuranceCompany, $user]) }}" class="inline" onsubmit="return confirm('{{ t('admin.confirm_delete_user') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-lg bg-red-100 text-red-600 hover:bg-red-200 transition-colors" title="{{ t('admin.delete') }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="md:hidden space-y-4 p-4">
            @foreach($users as $user)
                <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1">
                            <h3 class="font-bold text-gray-900">{{ $user->full_name }}</h3>
                            <p class="text-sm text-gray-600">{{ $user->formatted_phone }}</p>
                            <p class="text-xs text-gray-500">{{ t('admin.national_id') }}: {{ $user->formatted_national_id }}</p>
                            <div class="flex items-center space-x-2 {{ $isRtl ? 'space-x-reverse' : '' }} mt-2">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $user->status_badge['class'] }}">
                                    {{ $user->status_badge['text'] }}
                                </span>
                                <span class="px-2 py-1 rounded text-xs bg-gray-100 font-mono">{{ $user->policy_number }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-center space-x-3 {{ $isRtl ? 'space-x-reverse' : '' }} pt-3 border-t border-gray-200">
                        <form method="POST" action="{{ route('admin.users.insurance-companies.insurance-users.toggle', [$insuranceCompany, $user]) }}" class="inline">
                            @csrf
                            <button type="submit" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium {{ $user->is_active ? 'bg-orange-100 text-orange-700 hover:bg-orange-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }} transition-colors">
                                <svg class="w-4 h-4 {{ $isRtl ? 'ml-1' : 'mr-1' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if($user->is_active)
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h8m-10 5a9 9 0 1118 0 9 9 0 01-18 0z"></path>
                                    @endif
                                </svg>
                                {{ $user->is_active ? t('admin.deactivate') : t('admin.activate') }}
                            </button>
                        </form>
                        
                        <button onclick="openResetPasswordModal('{{ $user->id }}', '{{ $user->full_name }}')" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium bg-blue-100 text-blue-700 hover:bg-blue-200 transition-colors">
                            <svg class="w-4 h-4 {{ $isRtl ? 'ml-1' : 'mr-1' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-6 6c-3 0-6-1-9-3s-9-3-9-6 6-6 9-6c3 0 6 1 9 3m-2 2a2 2 0 012-2m-2 2a2 2 0 01-2 2m2-2v10a2 2 0 01-2 2H9a2 2 0 01-2-2V9a2 2 0 012-2 2 2 0 012-2"></path>
                            </svg>
                            {{ t('admin.reset_password') }}
                        </button>
                        
                        <form method="POST" action="{{ route('admin.users.insurance-companies.insurance-users.destroy', [$insuranceCompany, $user]) }}" class="inline" onsubmit="return confirm('{{ t('admin.confirm_delete_user') }}')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium bg-red-100 text-red-700 hover:bg-red-200 transition-colors">
                                <svg class="w-4 h-4 {{ $isRtl ? 'ml-1' : 'mr-1' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                {{ t('admin.delete') }}
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $users->links() }}
            </div>
        @endif
    @else
        <div class="p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
            </svg>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ t('admin.no_users_found') }}</h3>
            <p class="text-gray-600">{{ t('admin.no_users_found_desc') }}</p>
        </div>
    @endif
</div>

<div id="resetPasswordModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-md w-full p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-900">{{ t('admin.reset_password') }}</h3>
            <button onclick="closeResetPasswordModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <form id="resetPasswordForm" method="POST">
            @csrf
            <div class="mb-4">
                <p class="text-sm text-gray-600 mb-4">{{ t('admin.reset_password_for') }}: <span id="userNameDisplay" class="font-semibold"></span></p>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ t('admin.new_password') }}</label>
                    <input type="password" name="new_password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500 focus:border-transparent" required minlength="6">
                </div>
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ t('admin.confirm_new_password') }}</label>
                    <input type="password" name="new_password_confirmation" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500 focus:border-transparent" required minlength="6">
                </div>
            </div>
            
            <div class="flex justify-end space-x-3 {{ $isRtl ? 'space-x-reverse' : '' }}">
                <button type="button" onclick="closeResetPasswordModal()" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                    {{ t('admin.cancel') }}
                </button>
                <button type="submit" class="px-4 py-2 bg-gold-500 text-dark-900 rounded-lg hover:bg-gold-600 transition-colors">
                    {{ t('admin.reset_password') }}
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openResetPasswordModal(userId, userName) {
    document.getElementById('userNameDisplay').textContent = userName;
    document.getElementById('resetPasswordForm').action = '{{ route("admin.users.insurance-companies.insurance-users.reset-password", [$insuranceCompany, ":user"]) }}'.replace(':user', userId);
    document.getElementById('resetPasswordModal').classList.remove('hidden');
}

function closeResetPasswordModal() {
    document.getElementById('resetPasswordModal').classList.add('hidden');
    document.getElementById('resetPasswordForm').reset();
}

document.getElementById('resetPasswordModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeResetPasswordModal();
    }
});
</script>
@endpush