<div class="px-md-4 zoom-in" x-data @focus-paid-amount.window="document.getElementById('paid_amount').focus()">
    <x-slot name="header">
        {{ __('Collection') }}
    </x-slot>
    <div class="row g-2 d-flex justify-content-center">
        <div class="col-lg-4 col-md-5 col-sm-12">
            <x-mikrotik.section-form>
                <x-slot name="title">{{ __('Collection') }}</x-slot>
                <x-slot name="aside">
                    <input
                            type="search"
                            name="customer_list"
                            class="form-control w-100"
                            placeholder="FCNET-XXX, customer name, mobile, name"
                            wire:model.live="customer_list"
                            autocomplete="off"
                            tabindex="1"
                            wire:keydown.arrow-down="incrementHighlight"
                            wire:keydown.arrow-up="decrementHighlight"
                            wire:keydown.enter="selectHighlightedCustomer"
                            id="customer_list"
                            autofocus
                        >
                    @if (!empty($customers))
                        <ul class="scrollbar-overlay overflow-auto list-group position-absolute" style="max-height:25rem; z-index: 1000;">
                            @foreach ($customers as $index => $customer)
                                <li
                                    wire:click="selectCustomer('{{ encrypt($customer->customer_unique_id) }}')"
                                    class="list-group-item {{ $index === $highlightedIndex ? 'active' : '' }}"
                                    style="cursor: pointer;"
                                    wire:key="customer-{{ $customer->id }}"
                                >
                                    {{ $customer->customer_unique_id }}, {{ $customer->customer_name }},
                                    @foreach ($customer->customerAddress as $address)
                                        {{ $address->input_type_test }},
                                        {{ $address->input_type_dropdown }},
                                        {{ $address->input_type_textarea }}
                                    @endforeach
                                    , {{ $customer->mobile }}, {{ $customer->username }}
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </x-slot>
            </x-mikrotik.section-form>
        </div>

        <div class="col-lg-6 col-md-7 col-sm-12">
            @if (!empty($info_data))
                <x-mikrotik.section-form :class="'row'" x-init="$dispatch('focus-paid-amount')">
                    <x-slot name="title">{{ __('Customer Info') }}</x-slot>
                    <x-slot name="aside">
                        <div class="col-md-12">
                            <table class="table table-sm table-striped">
                                <tr>
                                    <td>ID</td>
                                    <td>{{ $info_data->customer_unique_id }}</td>
                                </tr>
                                <tr>
                                    <td>Name</td>
                                    <td>{{ $info_data->customer_name }}</td>
                                </tr>
                                <tr>
                                    <td>Billing Type</td>
                                    <td>{{ $info_data->billing->billing_type }}</td>
                                </tr>
                                <tr>
                                    <td>PPPoE Username</td>
                                    <td>{{ $info_data->pppUser->username }}</td>
                                </tr>
                                <tr>
                                    <td>Address</td>
                                    <td>
                                        @foreach ($info_data->customerAddress as $address)
                                            {{ $address->input_type_dropdown }},
                                            {{ $address->input_type_test }},
                                            {{ $address->input_type_textarea }}
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <td>Status</td>
                                    @php
                                        $badge = match($info_data->status) {
                                            'active' => 'badge-subtle-success',
                                            'pending' => 'badge-subtle-warning',
                                            'free' => 'badge-subtle-info',
                                            default => 'badge-subtle-danger',
                                        };
                                    @endphp

                                    <td><span class="badge rounded-pill {{ $badge }}">{{ ucfirst($info_data->status) }}</span></td>
                                </tr>
                                <tr>
                                    <td>Expire Date</td>
                                    <td>{{ \Carbon\Carbon::parse($this->info_data->billing->auto_disable_date)->format('d-M-Y') }} + {{ $this->info_data->billing->auto_disable_month }} = {{ \Carbon\Carbon::parse($this->info_data->billing->auto_disable_date)->addMonths($this->info_data->billing->auto_disable_month)->format('d-M-Y') }}, AutoDisable: {{ $this->info_data->billing->auto_disable }}</td>
                                </tr>
                                <tr>
                                    <td>Monthly Rent</td>
                                    <td class="text-end">{{ $info_data->billing->monthly_rent }}</td>
                                </tr>
                                @if ($info_data->billing->additional_charge != 0)
                                    <tr>
                                        <td>Additional Charge :</td>
                                        <td class="text-end">{{ $info_data->billing->additional_charge }}</td>
                                    </tr>
                                @endif
                                </tr>
                                @if ($info_data->billing->discount != 0)
                                    <tr>
                                        <td>Discount :</td>
                                        <td class="text-end">{{ $info_data->billing->discount }}</td>
                                    </tr>
                                @endif
                                </tr>
                                @if ($info_data->billing->advance != 0)
                                    <tr>
                                        <td>Advance :</td>
                                        <td class="text-end">{{ $info_data->billing->advance }}</td>
                                    </tr>
                                @endif
                                </tr>
                                @if ($info_data->billing->vat != 0)
                                    <tr>
                                        <td>vat :</td>
                                        <td class="text-end">{{ $info_data->billing->vat }}</td>
                                    </tr>
                                @endif
                                @if ($info_data->billing->previous_due != 0)
                                    <tr>
                                        <td>Previous Due :</td>
                                        <td class="text-end">{{ $info_data->billing->previous_due }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <th>Sum :</th>
                                    <td class="text-end">{{ $info_data->billing->total_amount }}</td>
                                </tr>
                                <tr>
                                    <th>Total Payable Amount :</th>
                                    <th class="text-end">{{ $info_data->billing->due_amount }}</th>
                                </tr>
                                {{-- @foreach ($info_data->paymentSummary as $paymentSummary)
                                use this some data collect
                                    <tr>
                                        <td>Collected Amount :</td>
                                        <td class="text-end">
                                        </td>
                                    </tr>
                                @endforeach --}}
                                <tr>
                                    <td>Collected Amount :</td>
                                    <td class="text-end">
                                        @foreach ($collectionSummary as $collectionSummary)
                                        {{ $collectionSummary->customer_collection_unique_id }} -> {{ \Carbon\Carbon::parse($collectionSummary->collection_date)->format('d-M-Y') }} -> {{ $collectionSummary->collection_amount }} <br/>
                                        @endforeach
                                        {{ $paid_amount ?? 0 }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Current Payable</th>
                                    <th class="text-end">{{$due_amount}}</th>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-12">
                            <form wire:submit.prevent="savePayment" class="row mt-3">
                                <input type="number" class="form-control col-md m-1" name="paid_amount" id="paid_amount" wire:model="paid_amount" wire:keyup="calculatePayment" min="1" autofocus placeholder="Pay Amount" required>
                                <input type="text" class="form-control col-md m-1" name="invoice" id="invoice" wire:model.live="invoice" placeholder="Invoice No">
                                <input type="date" class="form-control col-md m-1" name="expire_date" id="expire_date" wire:model.live="expire_date">
                                <button class="btn btn-success btn-sm col-md m-1">Pay Now</button>
                            </form>
                        </div>
                    </x-slot>
                </x-mikrotik.section-form>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Listen for the 'focusInput' event from Livewire
    Livewire.on('focusInput', () => {
        // Set focus to the customer_list input after form submission
        document.getElementById('customer_list').focus();
    });
</script>
@endpush
