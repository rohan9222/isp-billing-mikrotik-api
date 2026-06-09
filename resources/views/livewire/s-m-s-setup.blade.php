<div>
    <x-slot name="header">
        {{ __('SMS Gateway & Templates Setup') }}
    </x-slot>

    {{-- Top Section: Balance & Profile Dashboard --}}
    <div class="row g-4 mb-4">
        {{-- Gateway Balance Card --}}
        <div class="col-md-6">
            <div class="card border-0 rounded-4 position-relative overflow-hidden gateway-dashboard-card" 
                 style="background: linear-gradient(145deg, #072e33, #0f4c5c); color: white; box-shadow: 0 10px 25px -10px rgba(15, 76, 92, 0.3); border: 1px solid rgba(255, 255, 255, 0.08) !important;">
                
                {{-- Decorative background glow --}}
                <div class="position-absolute" style="width: 180px; height: 180px; background: radial-gradient(circle, rgba(45, 212, 191, 0.2) 0%, rgba(45, 212, 191, 0) 70%); top: -50px; right: -50px; pointer-events: none; z-index: 1;"></div>
                
                <div class="card-body p-3.5 position-relative d-flex align-items-center justify-content-between" style="z-index: 2; min-height: 110px;">
                    <div class="d-flex flex-column justify-content-center">
                        <span class="text-white text-opacity-70 text-uppercase fw-semibold mb-1" style="font-size: 0.68rem; letter-spacing: 1px;">SMS Balance</span>
                        @if(empty($balance) || !is_array($balance) || ($balance['status'] ?? '') === 'error' || empty($balance['data']))
                            <div class="d-flex flex-column gap-0.5">
                                <h4 class="fw-bold mb-0 text-white-50" style="font-size: 1.25rem;">Connection Error</h4>
                                <span class="text-danger small" style="font-size: 0.7rem;">Endpoint unreachable</span>
                            </div>
                        @else
                            <div class="d-flex align-items-baseline gap-1.5">
                                <h2 class="fw-bold mb-0 text-white" style="letter-spacing: -0.5px; font-family: 'Outfit', 'Inter', sans-serif; font-size: 1.85rem;">
                                    {{ is_numeric($balance['data']['remaining_balance'] ?? null) ? number_format((float) $balance['data']['remaining_balance']) : ($balance['data']['remaining_balance'] ?? 'N/A') }}
                                </h2>
                                <span class="fs-10 text-teal-300 text-opacity-90 fw-medium">credits</span>
                            </div>
                            @if(isset($balance['data']['expired_on']))
                                <span class="text-white-50 mt-1 d-flex align-items-center gap-1" style="font-size: 0.72rem;">
                                    <i class="bi bi-calendar-event text-teal-400" style="font-size: 0.8rem;"></i>
                                    Expiry: <strong class="text-white">{{ \Carbon\Carbon::parse($balance['data']['expired_on'])->format('d M, Y') }}</strong>
                                </span>
                            @endif
                        @endif
                    </div>
                    
                    <div class="d-flex flex-column align-items-end justify-content-between gap-2">
                        <div class="rounded-3 p-2 bg-white bg-opacity-10 text-white border border-white border-opacity-10 shadow-sm">
                            <i class="bi bi-wallet2 fs-6 text-teal-300" style="color: #2dd4bf;"></i>
                        </div>
                        <span class="badge bg-emerald-500 bg-opacity-25 rounded-pill px-2.5 py-1 fs-11 text-uppercase fw-bold border border-emerald-500 border-opacity-20 d-flex align-items-center gap-1.5" style="letter-spacing: 0.5px; color: #34d399 !important; background-color: rgba(16, 185, 129, 0.15) !important;">
                            <span class="d-inline-block rounded-circle pulse-dot" style="width: 5px; height: 5px; background-color: #10b981;"></span>
                            Active
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Gateway Profile Card --}}
        <div class="col-md-6">
            <div class="card border-0 rounded-4 position-relative overflow-hidden gateway-dashboard-card" 
                 style="background: linear-gradient(145deg, #120e36, #2d2685); color: white; box-shadow: 0 10px 25px -10px rgba(45, 38, 133, 0.3); border: 1px solid rgba(255, 255, 255, 0.08) !important;">
                
                {{-- Decorative background glow --}}
                <div class="position-absolute" style="width: 180px; height: 180px; background: radial-gradient(circle, rgba(129, 140, 248, 0.2) 0%, rgba(129, 140, 248, 0) 70%); top: -50px; right: -50px; pointer-events: none; z-index: 1;"></div>
                
                <div class="card-body p-3.5 position-relative d-flex align-items-center justify-content-between" style="z-index: 2; min-height: 110px;">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-white bg-opacity-15 rounded-circle p-0.5 border border-white border-opacity-20 shadow-inner">
                            <div class="bg-indigo-600 rounded-circle d-flex align-items-center justify-content-center fw-bold text-white shadow-sm" style="width: 42px; height: 42px; font-size: 1.1rem;">
                                {{ strtoupper(substr($profile['data']['first_name'] ?? 'N', 0, 1)) }}
                            </div>
                        </div>
                        <div class="d-flex flex-column justify-content-center">
                            <span class="text-white text-opacity-70 text-uppercase fw-semibold mb-0.5" style="font-size: 0.68rem; letter-spacing: 1px;">Gateway Profile</span>
                            @if(empty($profile) || !is_array($profile) || ($profile['status'] ?? '') === 'error' || empty($profile['data']))
                                <h4 class="fw-bold mb-0 text-white-50" style="font-size: 1.25rem;">Profile Error</h4>
                            @else
                                <h4 class="fw-bold mb-0 text-white" style="letter-spacing: -0.3px; font-family: 'Outfit', 'Inter', sans-serif; font-size: 1.25rem;">
                                    {{ ($profile['data']['first_name'] ?? '') . ' ' . ($profile['data']['last_name'] ?? '') }}
                                </h4>
                                <span class="text-indigo-200 small d-flex align-items-center gap-1" style="color: #c7d2fe; font-size: 0.72rem;">
                                    <i class="bi bi-envelope-open" style="font-size: 0.75rem;"></i>
                                    {{ $profile['data']['email'] ?? 'N/A' }}
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="d-flex flex-column align-items-end justify-content-between gap-2">
                        <div class="rounded-3 p-2 bg-white bg-opacity-10 text-white border border-white border-opacity-10 shadow-sm">
                            <i class="bi bi-shield-check fs-6 text-indigo-300" style="color: #a5b4fc;"></i>
                        </div>
                        <span class="badge bg-indigo-500 bg-opacity-25 rounded-pill px-2.5 py-1 fs-11 text-uppercase fw-bold border border-indigo-500 border-opacity-20" style="letter-spacing: 0.5px; color: #c7d2fe !important; background-color: rgba(99, 102, 241, 0.15) !important;">
                            Verified
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Templates Section --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-chat-left-quote-fill text-primary me-2"></i>SMS Notification Templates</h5>
                <p class="text-muted small mb-0">Manage automatic alerts and customize message placeholders.</p>
            </div>
            {{-- Search templates --}}
            <div class="position-relative">
                <span class="position-absolute top-50 start-0 translate-middle-y ps-3 text-muted">
                    <i class="bi bi-search"></i>
                </span>
                <input 
                    type="text" 
                    wire:model.live="search" 
                    class="form-control form-control-sm rounded-3 ps-5" 
                    placeholder="Search templates..." 
                    style="width: 250px; min-height: 38px; border: 1px solid #e2e8f0;"
                >
            </div>
        </div>
        <div class="card-body bg-light bg-opacity-30 p-4">
            @php
                $templateMeta = [
                    'all_customers' => ['icon' => 'bi-megaphone', 'color' => '#06b6d4', 'bg' => '#ecfeff', 'text' => '#0891b2', 'desc' => 'Sent to all active customers'],
                    'area_wise_customer_due_list' => ['icon' => 'bi-card-list', 'color' => '#f59e0b', 'bg' => '#fef3c7', 'text' => '#d97706', 'desc' => 'Due list by area coverage'],
                    'area_wise_customer_list' => ['icon' => 'bi-geo-alt', 'color' => '#14b8a6', 'bg' => '#ccfbf1', 'text' => '#0d9488', 'desc' => 'General message to area list'],
                    'auto_temporary_disable_alert' => ['icon' => 'bi-x-circle', 'color' => '#ef4444', 'bg' => '#fee2e2', 'text' => '#dc2626', 'desc' => 'Alert for automated disable'],
                    'bill_generate' => ['icon' => 'bi-receipt', 'color' => '#10b981', 'bg' => '#d1fae5', 'text' => '#059669', 'desc' => 'Sent during bill generation'],
                    'payment_collection' => ['icon' => 'bi-cash-coin', 'color' => '#10b981', 'bg' => '#d1fae5', 'text' => '#059669', 'desc' => 'Payment confirmation alert'],
                    'collection_(MFS)_to_owner' => ['icon' => 'bi-shield-check', 'color' => '#6366f1', 'bg' => '#e0e7ff', 'text' => '#4f46e5', 'desc' => 'MFS payment summary to admin'],
                    'collection_delete' => ['icon' => 'bi-trash', 'color' => '#ef4444', 'bg' => '#fee2e2', 'text' => '#dc2626', 'desc' => 'Payment cancellation alert'],
                    'collection_edit' => ['icon' => 'bi-pencil-square', 'color' => '#f59e0b', 'bg' => '#fef3c7', 'text' => '#d97706', 'desc' => 'Payment update notification'],
                    'collection_to_owner' => ['icon' => 'bi-person-check', 'color' => '#6366f1', 'bg' => '#e0e7ff', 'text' => '#4f46e5', 'desc' => 'Collection summary to admin'],
                    'complain_employee' => ['icon' => 'bi-briefcase', 'color' => '#8b5cf6', 'bg' => '#f3e8ff', 'text' => '#7c3aed', 'desc' => 'Complain assignment alert'],
                    'complain_list' => ['icon' => 'bi-chat-dots', 'color' => '#8b5cf6', 'bg' => '#f3e8ff', 'text' => '#7c3aed', 'desc' => 'Resolved complaint alert'],
                    'complain_to_customer' => ['icon' => 'bi-headset', 'color' => '#8b5cf6', 'bg' => '#f3e8ff', 'text' => '#7c3aed', 'desc' => 'Complain registration receipt'],
                    'create_customer' => ['icon' => 'bi-person-plus', 'color' => '#3b82f6', 'bg' => '#dbeafe', 'text' => '#2563eb', 'desc' => 'New customer account welcome'],
                    'create_customer_to_owner' => ['icon' => 'bi-info-circle', 'color' => '#3b82f6', 'bg' => '#dbeafe', 'text' => '#2563eb', 'desc' => 'New customer notice to admin'],
                    'free_customer_list' => ['icon' => 'bi-gift', 'color' => '#ec4899', 'bg' => '#fce7f3', 'text' => '#db2777', 'desc' => 'Complimentary service details'],
                    'inactive_customer_list' => ['icon' => 'bi-moon-stars', 'color' => '#6b7280', 'bg' => '#f3f4f6', 'text' => '#4b5563', 'desc' => 'Alert for inactive accounts'],
                    'failed_to_disable_at_mikrotik' => ['icon' => 'bi-exclamation-octagon', 'color' => '#ef4444', 'bg' => '#fee2e2', 'text' => '#dc2626', 'desc' => 'Failed disable mikrotik notice'],
                    'temporary_disable_customer_list' => ['icon' => 'bi-pause-circle', 'color' => '#f59e0b', 'bg' => '#fef3c7', 'text' => '#d97706', 'desc' => 'Temporary disable notice'],
                    'reminder' => ['icon' => 'bi-alarm', 'color' => '#f59e0b', 'bg' => '#fef3c7', 'text' => '#d97706', 'desc' => 'Upcoming bill payment warning']
                ];
            @endphp
            <div class="row g-3">
                @forelse($smsTemps as $smsTemp)
                    @php
                        $meta = $templateMeta[$smsTemp->template_name] ?? ['icon' => 'bi-chat-left-dots', 'color' => '#6b7280', 'bg' => '#f3f4f6', 'text' => '#4b5563', 'desc' => 'Custom notification alert'];
                    @endphp
                    <div class="col-md-6 col-lg-4">
                        <div class="card border-0 shadow-sm rounded-4 h-100 transition-all template-card" 
                             style="border: 1px solid #f1f5f9; transition: all 0.25s ease-in-out;">
                            
                            <div class="card-body p-4 d-flex flex-column justify-content-between">
                                <div>
                                    {{-- Card Header Info --}}
                                    <div class="d-flex align-items-start justify-content-between mb-3">
                                        <div class="d-flex align-items-center gap-2.5">
                                            <div class="rounded-3 p-2.5 d-flex align-items-center justify-content-center" 
                                                 style="background-color: {{ $meta['bg'] }}; color: {{ $meta['color'] }}; width: 40px; height: 40px; border: 1px solid rgba(0,0,0,0.02);">
                                                <i class="bi {{ $meta['icon'] }} fs-5"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0.5 fw-bold text-dark text-capitalize" style="font-size: 0.95rem; letter-spacing: -0.2px;">
                                                    {{ str_replace('_', ' ', $smsTemp->template_name) }}
                                                </h6>
                                                <small class="text-muted" style="font-size: 0.72rem;">{{ $meta['desc'] }}</small>
                                            </div>
                                        </div>
                                        
                                        {{-- Status Switch --}}
                                        <div class="form-check form-switch p-0 m-0">
                                            @can('sms-setup')
                                                <input 
                                                    class="form-check-input ms-0 cursor-pointer" 
                                                    type="checkbox" 
                                                    role="switch" 
                                                    wire:click="setSmsActive({{ $smsTemp->id }})" 
                                                    id="sms-{{ $smsTemp->id }}" 
                                                    {{ $smsTemp->is_active ? 'checked' : '' }}
                                                    style="width: 2.1em; height: 1.05em;"
                                                >
                                            @else
                                                <input 
                                                    class="form-check-input ms-0" 
                                                    type="checkbox" 
                                                    role="switch" 
                                                    id="sms-{{ $smsTemp->id }}" 
                                                    {{ $smsTemp->is_active ? 'checked' : '' }} 
                                                    disabled
                                                    style="width: 2.1em; height: 1.05em;"
                                                >
                                            @endcan
                                        </div>
                                    </div>

                                    {{-- Template Body Preview with Highlighted Placeholders --}}
                                    <div class="template-preview-box rounded-3 p-3 text-muted mb-4 position-relative overflow-hidden" 
                                         style="background-color: #f8fafc; border: 1px solid #f1f5f9; min-height: 100px; font-size: 0.82rem; line-height: 1.5; font-family: 'Courier New', Courier, monospace; border-left: 3px solid {{ $meta['color'] }};">
                                        
                                        {!! preg_replace('/(\{[A-Z0-9_()]+\})/', '<span class="px-1.5 py-0.5 rounded-pill fw-semibold text-xs border" style="background-color: white; border-color: #e2e8f0; color: #475569; font-family: sans-serif; font-size: 0.68rem; letter-spacing: 0.2px;">$1</span>', e(Str::limit($smsTemp->template, 140))) !!}
                                    </div>
                                </div>

                                {{-- Card Footer Details & Action Button --}}
                                <div class="d-flex align-items-center justify-content-between pt-2 border-top border-light">
                                    <span class="badge rounded-pill px-2.5 py-1 fw-bold fs-10 text-uppercase tracking-wider border {{ $smsTemp->is_active ? 'bg-success-subtle text-success border-success border-opacity-10' : 'bg-secondary-subtle text-secondary border-secondary border-opacity-10' }}">
                                        {{ $smsTemp->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                    
                                    <button 
                                        type="button" 
                                        class="btn btn-sm btn-outline-primary rounded-pill px-3.5 py-1.5 fw-semibold d-flex align-items-center gap-1.5 shadow-sm text-xs template-edit-btn" 
                                        style="transition: all 0.2s;"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#smsModal-{{ $smsTemp->id }}"
                                    >
                                        <i class="bi bi-pencil-square"></i>Configure
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Modal for Editing SMS Template --}}
                        <div class="modal fade" id="smsModal-{{ $smsTemp->id }}" tabindex="-1" aria-labelledby="smsModalLabel-{{ $smsTemp->id }}" aria-hidden="true" wire:ignore.self>
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content border-0 shadow rounded-4">
                                    <form wire:submit.prevent="updateSms({{ $smsTemp->id }})">
                                        <div class="modal-header border-0 pb-0">
                                            <div>
                                                <h5 class="modal-title fw-bold text-dark text-capitalize" id="smsModalLabel-{{ $smsTemp->id }}">
                                                    Configure Template: {{ str_replace('_', ' ', $smsTemp->template_name) }}
                                                </h5>
                                                <p class="text-muted small mb-0">Modify automatic message content and formatting parameters.</p>
                                            </div>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            {{-- SMS Template Message Area --}}
                                            @can('sms-setup')
                                                @php
                                                    $placeholderMap = [
                                                        'all_customers' => ['{CUSTOMER_NAME}', '{MONTH}', '{BILL_AMOUNT}', '{CUSTOMER_ID}', '{LAST_DAY_OF_PAY_BILL}', '{COMPANY_NAME}', '{COMPANY_MOBILE}'],
                                                        'area_wise_customer_due_list' => ['{AMOUNT}', '{CUSTOMER_ID}', '{LAST_DAY_DUE_DATE}', '{AUTO_TEMPORARY_DAY}', '{COMPANY_NAME}'],
                                                        'area_wise_customer_list' => ['{CUSTOMER_NAME}', '{COMPANY_NAME}', '{COMPANY_MOBILE}'],
                                                        'auto_temporary_disable_alert' => ['{CUSTOMER_NAME}', '{CUSTOMER_ID}', '{DUE_AMOUNT}', '{COMPANY_NAME}', '{COMPANY_MOBILE}'],
                                                        'bill_generate' => ['{MONTH}', '{BILL_AMOUNT}', '{CUSTOMER_ID}', '{LAST_DAY_OF_PAY_BILL}', '{COMPANY_NAME}', '{COMPANY_MOBILE}'],
                                                        'payment_collection' => ['{CUSTOMER_NAME}', '{AMOUNT}', '{IP_OR_USER_NAME_OR_ID}', '{BALANCE}', '{COMPANY_NAME}'],
                                                        'collection_(MFS)_to_owner' => ['{AMOUNT}', '{CUSTOMER_NAME}', '{CUSTOMER_ID}', '{PAYMENT_SYSTEM}'],
                                                        'collection_delete' => ['{CUSTOMER_NAME}', '{AMOUNT}', '{TOTAL_COLLECTION}', '{COMPANY_NAME}', '{COMPANY_MOBILE}'],
                                                        'collection_edit' => ['{CUSTOMER_NAME}', '{PREVIOUS_COLLECTION_AMOUNT}', '{CURRENT_COLLECTION_AMOUNT}', '{COMPANY_NAME}'],
                                                        'collection_to_owner' => ['{AMOUNT}', '{CUSTOMER_NAME}', '{CUSTOMER_ID}'],
                                                        'complain_employee' => ['{CUSTOMER_NAME}', '{CUSTOMER_ID}', '{IP}', '{PPPOE_USERNAME}', '{CUSTOMER_MOBILE}', '{COMPLAINS}', '{COMMENT}', '{CUSTOMER_ADDRESS}'],
                                                        'complain_list' => ['{CUSTOMER_NAME}', '{COMPANY_MOBILE}', '{COMPANY_NAME}'],
                                                        'complain_to_customer' => ['{CUSTOMER_NAME}', '{COMPLAINS}', '{COMMENT}', '{EMPLOYEE_NAME}', '{EMPLOYEE_MOBILE}', '{COMPANY_NAME}', '{COMPANY_MOBILE}'],
                                                        'create_customer' => ['{CUSTOMER_NAME}', '{CUSTOMER_ID}', '{IP}', '{PPPOE_USERNAME}', '{COMPANY_NAME}'],
                                                        'create_customer_to_owner' => ['{CUSTOMER_NAME}', '{CUSTOMER_ID}', '{IP}', '{PPPOE_USERNAME}'],
                                                        'free_customer_list' => ['{CUSTOMER_NAME}', '{COMPANY_NAME}', '{COMPANY_MOBILE}'],
                                                        'inactive_customer_list' => ['{CUSTOMER_NAME}', '{DUE_AMOUNT}', '{COMPANY_NAME}', '{COMPANY_MOBILE}'],
                                                        'failed_to_disable_at_mikrotik' => ['{CUSTOMER_NAME}', '{ID}', '{IP}', '{PPPOE_USERNAME}', '{COMPANY_NAME}', '{COMPANY_MOBILE}'],
                                                        'temporary_disable_customer_list' => ['{CUSTOMER_NAME}', '{DUE_AMOUNT}', '{COMPANY_NAME}', '{COMPANY_MOBILE}'],
                                                        'reminder' => ['{CUSTOMER_NAME}', '{AUTO_TEMPORARY_DAY}', '{ID}', '{DUE_AMOUNT}', '{COMPANY_NAME}', '{COMPANY_MOBILE}']
                                                    ];

                                                    $templateName = $smsTemp->template_name;
                                                    $defaultPlaceholders = $placeholderMap[$templateName] ?? ['{CUSTOMER_NAME}', '{CUSTOMER_ID}', '{BILL_AMOUNT}', '{LAST_DAY_OF_PAY_BILL}', '{DUE_AMOUNT}', '{COMPANY_NAME}', '{COMPANY_MOBILE}'];

                                                    preg_match_all('/\{[A-Z0-9_()]+\}/', $smsTemp->template, $matches);
                                                    $extractedPlaceholders = $matches[0] ?? [];

                                                    $placeholders = array_unique(array_merge($defaultPlaceholders, $extractedPlaceholders));
                                                @endphp

                                                <div class="mb-4">
                                                    <label for="smsTemp-{{ $smsTemp->id }}" class="form-label fw-bold text-dark"><i class="bi bi-chat-quote me-1"></i>Message Template Body</label>
                                                    
                                                    {{-- Clickable placeholder buttons/badges --}}
                                                    <div class="mb-2">
                                                        <span class="text-muted d-block mb-1.5" style="font-size: 0.8rem; font-weight: 500;">
                                                            <i class="bi bi-patch-plus me-1 text-primary"></i>Click to insert placeholder:
                                                        </span>
                                                        <div class="d-flex flex-wrap gap-1">
                                                            @foreach($placeholders as $placeholder)
                                                                <button 
                                                                    type="button" 
                                                                    class="btn btn-sm btn-outline-secondary rounded-pill px-2.5 py-1 text-xs placeholder-btn" 
                                                                    onclick="insertPlaceholder('smsTemp-{{ $smsTemp->id }}', '{{ $placeholder }}')"
                                                                >
                                                                    {{ $placeholder }}
                                                                </button>
                                                            @endforeach
                                                        </div>
                                                    </div>

                                                    <textarea 
                                                        class="form-control rounded-3" 
                                                        wire:model.defer="smsTempList.{{ $smsTemp->id }}" 
                                                        id="smsTemp-{{ $smsTemp->id }}" 
                                                        rows="5"
                                                        style="border: 1px solid #cbd5e1; font-family: 'Courier New', Courier, monospace;"
                                                        placeholder="Write template here..."
                                                    ></textarea>
                                                    <div class="form-text text-muted small mt-1">
                                                        <i class="bi bi-info-circle me-1"></i>
                                                        Ensure you include the required parameters (e.g. <code>{CUSTOMER_NAME}</code>, <code>{DUE_AMOUNT}</code>) to render dynamically.
                                                    </div>
                                                </div>
                                            @else
                                                <div class="mb-4">
                                                    <label class="form-label fw-bold text-dark"><i class="bi bi-chat-quote me-1"></i>Message Template Body</label>
                                                    <div class="p-3 bg-light rounded-3 text-muted" style="font-family: 'Courier New', Courier, monospace;">
                                                        {{ $smsTemp->template }}
                                                    </div>
                                                </div>
                                            @endcan

                                            {{-- Live Previews / Examples Grid --}}
                                            <div class="row g-3">
                                                {{-- English Example --}}
                                                <div class="col-md-6">
                                                    <div class="card border-0 bg-light bg-opacity-50 rounded-3 h-100" style="border: 1px solid #f1f5f9 !important;">
                                                        <div class="card-body p-3">
                                                            <h6 class="fw-bold text-dark mb-2"><i class="bi bi-translate text-primary me-1"></i>English Example Preview</h6>
                                                            <div class="small text-muted p-2.5 bg-white rounded-3 border" style="min-height: 70px;">
                                                                {!! $smsTemp->template_ex_en !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Bangla Example --}}
                                                <div class="col-md-6">
                                                    <div class="card border-0 bg-light bg-opacity-50 rounded-3 h-100" style="border: 1px solid #f1f5f9 !important;">
                                                        <div class="card-body p-3">
                                                            <h6 class="fw-bold text-dark mb-2"><i class="bi bi-translate text-success me-1"></i>Bangla Example Preview</h6>
                                                            <div class="small text-muted p-2.5 bg-white rounded-3 border" style="min-height: 70px;">
                                                                {!! $smsTemp->template_ex_bn !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0 pt-0">
                                            <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Cancel</button>
                                            @can('sms-setup')
                                                <button type="submit" class="btn btn-primary rounded-3 px-4" data-bs-dismiss="modal">
                                                    <i class="bi bi-save me-1"></i>Save Configuration
                                                </button>
                                            @endcan
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 py-5 text-center text-muted">
                        <i class="bi bi-chat-square-x fs-1 mb-2 text-muted"></i>
                        <h6>No SMS templates found matching your search.</h6>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<style>
    .gateway-dashboard-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .gateway-dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.3) !important;
    }
    .pulse-dot {
        animation: pulse-animation 2s infinite;
    }
    @keyframes pulse-animation {
        0% {
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
        }
        70% {
            box-shadow: 0 0 0 8px rgba(16, 185, 129, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
        }
    }
    .placeholder-btn {
        font-size: 0.75rem !important;
        font-weight: 500;
        border: 1px solid #cbd5e1 !important;
        color: #475569 !important;
        background-color: #f8fafc !important;
        transition: all 0.15s ease-in-out;
    }
    .placeholder-btn:hover {
        background-color: #e2e8f0 !important;
        border-color: #94a3b8 !important;
        color: #0f172a !important;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .template-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px -5px rgba(0, 0, 0, 0.08) !important;
        border-color: #cbd5e1 !important;
    }
    .template-edit-btn:hover {
        background-color: var(--bs-primary) !important;
        color: white !important;
    }
</style>

<script>
    function insertPlaceholder(textareaId, placeholder) {
        const el = document.getElementById(textareaId);
        if (!el) return;

        const startPos = el.selectionStart;
        const endPos = el.selectionEnd;
        const oldVal = el.value;

        // Insert the placeholder at caret position
        const newVal = oldVal.substring(0, startPos) + placeholder + oldVal.substring(endPos, oldVal.length);
        el.value = newVal;

        // Reset cursor position to right after the inserted text
        const newCursorPos = startPos + placeholder.length;
        el.focus();
        el.setSelectionRange(newCursorPos, newCursorPos);

        // Dispatch input event to notify Livewire of the change
        el.dispatchEvent(new Event('input', { bubbles: true }));
    }
</script>

