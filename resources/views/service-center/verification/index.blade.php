@extends('service-center.layouts.app')

@section('title', t('service_center.vehicle_verification'))

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-3xl font-bold text-gray-900">{{ t('service_center.vehicle_verification') }}</h1>
        <p class="text-gray-600 mt-1">{{ t('service_center.verify_vehicle_delivery') }}</p>
    </div>

    <!-- Verification Form -->
    <div class="bg-white rounded-xl shadow-sm border">
        <div class="p-6 border-b">
            <h2 class="text-xl font-bold flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ t('service_center.verify_delivery_code') }}
            </h2>
            <p class="text-gray-600 text-sm mt-1">{{ t('service_center.enter_6_digit_code') }}</p>
        </div>
        
        <div class="p-6">
            <form id="verificationForm" class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">{{ t('service_center.delivery_verification_code') }}</label>
                    <div class="md:flex gap-4">
                        <input type="text" 
                               id="verificationCode" 
                               placeholder="Enter 6-digit code" 
                               maxlength="6" 
                               class="flex-1 mb-4 px-4 py-3 text-lg font-mono border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-center tracking-widest">
                        <button type="submit" 
                                class="px-8 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ t('service_center.verify') }}
                        </button>
                    </div>
                </div>
                
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <h4 class="font-medium text-blue-800 mb-1">{{ t('service_center.verification_instructions') }}</h4>
                            <ul class="text-sm text-blue-700 space-y-1">
                                <li>• {{ t('service_center.get_code_from_driver') }}</li>
                                <li>• {{ t('service_center.code_is_6_digits') }}</li>
                                <li>• {{ t('service_center.verify_vehicle_condition') }}</li>
                                <li>• {{ t('service_center.vehicle_ready_for_inspection') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Customer Delivery Verification -->
<div class="bg-white rounded-xl shadow-sm border">
    <div class="p-6 border-b">
        <h2 class="text-xl font-bold flex items-center gap-2">
            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
            </svg>
            {{ t('service_center.verify_customer_delivery') }}
        </h2>
        <p class="text-gray-600 text-sm mt-1">{{ t('service_center.verify_customer_brought_vehicle') }}</p>
    </div>
    
    <div class="p-6">
        <form id="customerDeliveryForm" class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">{{ t('service_center.customer_delivery_code') }}</label>
                <div class="md:flex gap-4">
                    <input type="text" 
                           id="customerDeliveryCode" 
                           placeholder="Enter 6-digit code" 
                           maxlength="6" 
                           class="flex-1 px-4 mb-4 py-3 text-lg font-mono border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-center tracking-widest">
                    <button type="submit" 
                            class="px-8 py-3 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition-colors">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        {{ t('service_center.verify') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>

document.getElementById('customerDeliveryForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const code = document.getElementById('customerDeliveryCode').value.trim();
    
    if (code.length !== 6) {
        showMessage('{{ t("service_center.code_must_be_6_digits") }}', 'error');
        return;
    }

    const submitButton = this.querySelector('button[type="submit"]');
    submitButton.disabled = true;
    submitButton.innerHTML = '<svg class="w-5 h-5 inline mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>{{ t("service_center.verifying") }}...';

    fetch('{{ route("service-center.verification.verify-delivery") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            delivery_code: code
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showCustomerDeliverySuccess(data.claim_info);
            document.getElementById('customerDeliveryCode').value = '';
            showMessage(data.message, 'success');
        } else {
            showMessage(data.error || '{{ t("service_center.verification_failed") }}', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('{{ t("service_center.error_occurred") }}', 'error');
    })
    .finally(() => {
        submitButton.disabled = false;
        submitButton.innerHTML = '<svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>{{ t("service_center.verify") }}';
    });
});

function showCustomerDeliverySuccess(claimInfo) {
    // You can customize this modal as needed
    alert(`Vehicle received successfully!\nClaim: ${claimInfo.claim_number}\nCustomer: ${claimInfo.customer_name}\nVehicle: ${claimInfo.vehicle_info}\nTime: ${claimInfo.delivery_time}`);
    
    // Reload page to show updated delivery list
    setTimeout(() => {
        location.reload();
    }, 2000);
}

// Auto-format customer delivery code input
document.getElementById('customerDeliveryCode').addEventListener('input', function(e) {
    e.target.value = e.target.value.replace(/\D/g, '');
});
</script>

    <!-- Recent Deliveries -->
    @if($recentDeliveries->count())
    <div class="bg-white rounded-xl shadow-sm border">
        <div class="p-6 border-b">
            <h2 class="text-xl font-bold flex items-center gap-2">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                {{ t('service_center.recent_deliveries') }}
            </h2>
        </div>
        
        <div class="p-6">
            <div class="space-y-4">
                @foreach($recentDeliveries as $delivery)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center text-white font-bold">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-900">{{ $delivery->claim->insuranceUser->full_name }}</h3>
                            <p class="text-gray-600 text-sm">{{ t('service_center.request') }} #{{ $delivery->request_code }}</p>
                            <p class="text-gray-500 text-xs">{{ t('service_center.chassis_number') }}: {{ $delivery->claim->chassis_number }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-900">{{ t('service_center.delivered') }}</p>
                        <p class="text-xs text-gray-500">{{ $delivery->updated_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @else
    <div class="bg-white rounded-xl shadow-sm border">
        <div class="p-12 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">{{ t('service_center.no_recent_deliveries') }}</h3>
            <p class="text-gray-600">{{ t('service_center.no_deliveries_description') }}</p>
        </div>
    </div>
    @endif
</div>

<!-- Success Modal -->
<div id="successModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-md w-full transform transition-all">
        <div class="p-6 text-center">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2" id="successTitle">{{ t('service_center.verification_successful') }}</h3>
            <p class="text-gray-600 mb-6" id="successMessage">{{ t('service_center.vehicle_received_successfully') }}</p>
            
            <div class="bg-gray-50 rounded-lg p-4 mb-6 text-left" id="vehicleDetails">
                <!-- Vehicle details will be populated here -->
            </div>
            
            <button onclick="closeSuccessModal()" 
                    class="w-full px-6 py-3 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition-colors">
                {{ t('service_center.continue') }}
            </button>
        </div>
    </div>
</div>

<!-- Error Messages -->
<div id="messageContainer" class="fixed top-4 right-4 z-40"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('verificationForm');
    const codeInput = document.getElementById('verificationCode');

    // Auto-format input (numbers only)
    codeInput.addEventListener('input', function(e) {
        e.target.value = e.target.value.replace(/\D/g, '');
    });

    // Submit on Enter key
    codeInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            submitVerification();
        }
    });

    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        submitVerification();
    });

    function submitVerification() {
        const code = codeInput.value.trim();
        
        if (code.length !== 6) {
            showMessage('{{ t("service_center.code_must_be_6_digits") }}', 'error');
            return;
        }

        // Disable form
        const submitButton = form.querySelector('button[type="submit"]');
        submitButton.disabled = true;
        submitButton.innerHTML = '<svg class="w-5 h-5 inline mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>{{ t("service_center.verifying") }}...';

        fetch('{{ route("service-center.verification.verify") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                verification_code: code
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccessModal(data.tow_request);
                codeInput.value = '';
                showMessage(data.message, 'success');
            } else {
                showMessage(data.error || '{{ t("service_center.verification_failed") }}', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('{{ t("service_center.error_occurred") }}', 'error');
        })
        .finally(() => {
            // Re-enable form
            submitButton.disabled = false;
            submitButton.innerHTML = '<svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ t("service_center.verify") }}';
        });
    }

    function showSuccessModal(towRequest) {
        const modal = document.getElementById('successModal');
        const vehicleDetails = document.getElementById('vehicleDetails');
        
        vehicleDetails.innerHTML = `
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="font-medium text-gray-600">{{ t("service_center.request_code") }}:</span>
                    <span class="text-gray-900">${towRequest.request_code}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-medium text-gray-600">{{ t("service_center.customer") }}:</span>
                    <span class="text-gray-900">${towRequest.customer_name}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-medium text-gray-600">{{ t("service_center.claim_number") }}:</span>
                    <span class="text-gray-900">${towRequest.claim_number}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-medium text-gray-600">{{ t("service_center.vehicle") }}:</span>
                    <span class="text-gray-900">${towRequest.vehicle_info}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-medium text-gray-600">{{ t("service_center.delivery_time") }}:</span>
                    <span class="text-gray-900">${towRequest.delivery_time}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-medium text-gray-600">{{ t("service_center.status") }}:</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        ${towRequest.new_status}
                    </span>
                </div>
            </div>
        `;
        
        modal.classList.remove('hidden');
    }

    function closeSuccessModal() {
        document.getElementById('successModal').classList.add('hidden');
        // Reload page to show updated delivery list
        setTimeout(() => {
            location.reload();
        }, 500);
    }

    // Make function global
    window.closeSuccessModal = closeSuccessModal;

    function showMessage(message, type) {
        const container = document.getElementById('messageContainer');
        const div = document.createElement('div');
        div.className = `px-4 py-3 rounded-lg shadow-lg text-white font-medium mb-2 transform transition-all duration-300 ${
            type === 'success' ? 'bg-green-600' : 'bg-red-600'
        }`;
        div.textContent = message;
        container.appendChild(div);
        
        // Animation
        setTimeout(() => {
            div.style.transform = 'translateX(0)';
        }, 10);
        
        setTimeout(() => {
            div.style.transform = 'translateX(100%)';
            setTimeout(() => {
                div.remove();
            }, 300);
        }, 5000);
    }
});
</script>
@endsection