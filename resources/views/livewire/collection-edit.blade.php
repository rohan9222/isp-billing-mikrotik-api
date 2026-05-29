<div class="px-4 zoom-in" x-data @focus-paid-amount.window="document.getElementById('paid_amount').focus()">
    <x-slot name="header">
        {{ __('Collection Edit') }}
    </x-slot>
    <div class="row g-2 d-flex justify-content-center">
        <div class="col-lg-4 col-md-5 col-sm-12">
            <x-mikrotik.section-form>
                <x-slot name="title">{{ __('Collection Edit') }}</x-slot>
                <x-slot name="aside">
                    <input
                        type="search"
                        name="customer_list"
                        class="form-control w-100"
                        placeholder="{{ siteUrlSettings('customer_id_prefix') ?: 'FCNET' }}-XXX, customer name, mobile, name"
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
                <x-mikrotik.section-form :class="'row'">
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
                                    <td>Collected Amount :</td>
                                    <td class="text-end">
                                        @foreach ($collectionSummary as $collectionSummary)
                                        {{ $collectionSummary->customer_collection_unique_id }} -> {{ \Carbon\Carbon::parse($collectionSummary->collection_date)->format('d-M-Y') }} -> {{ $collectionSummary->collection_amount }}
                                        <button class="btn btn-sm btn-danger" wire:click="deleteCollection({{ $collectionSummary->id }})"><i class="bi bi-trash"></i></button><br/>
                                        @endforeach
                                    </td>
                                </tr>
                            </table>
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
