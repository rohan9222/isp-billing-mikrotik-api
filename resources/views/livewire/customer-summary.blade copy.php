<div class="px-4">
    <div class="row px-5 mx-5">
        <div class="col-md-4">
            <div class="row mt-2">
                <div class="col-md-12">
                    <h4 class="box-shadow text-center bg-info h3 p-2">Search</h4>
                </div>
                <div class="col-md-12">
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
                    >
                    @if (!empty($customers))
                        <ul class="list-group position-absolute" style="z-index: 1000;">
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
                </div>
            </div>
        </div>

        <div class="col-md-8">
            @if (!empty($info_data))
                <div class="row mt-2" x-init="$dispatch('focus-paid-amount')">
                    <div class="col-md-12">
                        <h4 class="box-shadow text-center bg-info h3 p-2">Customer Info</h4>
                    </div>
                    <div class="col-md-12">
                        <table class="table table-sm table-striped">
                            <tr>
                                <td>ID</td>
                                <td>{{ $info_data->customer_unique_id }}</td>
                                <td>Name</td>
                                <td>{{ $info_data->customer_name }}</td>
                            </tr>
                            <tr>
                                <td>Billing Type</td>
                                <td>{{ $info_data->billing->billing_type }}</td>
                                <td>PPPoE Username</td>
                                <td>{{ $info_data->pppUser->username }}</td>
                            </tr>
                            <tr>
                                <td>Address</td>
                                <td colspan="3">
                                    @foreach ($info_data->customerAddress as $address)
                                        {{ $address->input_type_dropdown }},
                                        {{ $address->input_type_test }},
                                        {{ $address->input_type_textarea }}
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td>{{ $info_data->status }}</td>
                                <td>Expire Date</td>
                                <td>{{ \Carbon\Carbon::parse($this->info_data->billing->auto_disable_date)->format('d-M-Y') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>

    
    @if (!empty($info_data))
        <div class="row mt-2  px-4 mx-4">
            <div class="col-md-12">
                <h4 class="box-shadow
                    text-center bg-info h5 p-1">Customer Payment Summary</h4>
            </div>
            <div class="col-md-12 mt-2">
                <table id="payment_summary" class="data-table table table-striped table-hover display table-bordered table-responsive scrollbar">
                    <thead class="text-white text-center table-info">
                        <tr>
                            <th class="text-center">{{ __('Date') }}</th>
                            <th class="text-center">{{ __('Monthly Rent') }}</th>
                            <th class="text-center">{{ __('Discount') }}</th>
                            <th class="text-center">{{ __('Advance') }}</th>
                            <th class="text-center">{{ __('Add. Charge') }}</th>
                            {{-- <th class="text-center">{{ __('Sum') }}</th> --}}
                            <th class="text-center">{{ __('Vat (%)') }}</th>
                            <th class="text-center">{{ __('Previous Due') }}</th>
                            <th class="text-center">{{ __('Bill Amount') }}</th>
                            <th class="text-center">{{ __('Collection Amount') }}</th>
                            <th class="text-center">{{ __('Total Due') }}</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @foreach ($info_data->paymentSummary->sortByDesc('summary_date') as $paymentSummary)
                            @php
                                $collectionStartDate = \Carbon\Carbon::parse($paymentSummary->summary_date)->startOfMonth();
                                $collectionEndDate = \Carbon\Carbon::parse($paymentSummary->summary_date)->endOfMonth();
                            
                                // Corrected 'whereBetween' usage
                                $collections = $info_data->collectionSummary->whereBetween('collection_date', [$collectionStartDate, $collectionEndDate])->values()->toArray(); 
                                $bill_amount = ($paymentSummary->monthly_rent + $paymentSummary->additional_charge + $paymentSummary->previous_due) - ($paymentSummary->discount + $paymentSummary->advance );
                            @endphp
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($paymentSummary->summary_date)->format('d-M-Y') }}</td>
                                <td>{{ $paymentSummary->monthly_rent }}</td>
                                <td>{{ $paymentSummary->discount }}</td>
                                <td>{{ $paymentSummary->advance }}</td>
                                <td>{{ $paymentSummary->additional_charge }}</td>
                                {{-- <td>{{ ($paymentSummary->monthly_rent + $paymentSummary->additional_charge) - ($paymentSummary->discount + $paymentSummary->advance ) }}</td> --}}
                                <td>{{ $paymentSummary->vat }}</td>
                                <td>{{ $paymentSummary->previous_due }}</td>
                                <td>{{ $bill_amount }}</td>
                                <td></td>
                                <td></td>
                            </tr>
                            
                            @if ($collections)
                                @foreach ($collections as $collection)
                                        <tr>
                                            <td>{{ date('d-M-Y', strtotime($collection['collection_date'])) }}</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td class="text-end">
                                                collected by : <br>{{ $collection['collected_by'] }}
                                            </td>
                                            <td>
                                                {{ $collection['collection_amount'] }}
                                            </td>
                                            <td>{{ $bill_amount - $collection['collection_amount'] }}</td>
                                        </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>0</td>
                                    <td>{{ $bill_amount }}</td>
                                </tr>
                            @endif                        
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>    
    @endif          
</div>
@push('styles')
    <style>
        #payment_summary th, #payment_summary td {
            padding: 0.1rem;
        }
    </style>
@endpush
@push('scripts')
<script>
    function initializeDataTable() {
        setTimeout(() => {
            if ($.fn.DataTable.isDataTable('#payment_summary')) {
                $('#payment_summary').DataTable().destroy();
            }
            $('#payment_summary').DataTable({
                searching: false,
                paging: false,
                ordering: false,
                info: false,
                dom: 'Bfrtip',
                buttons: [
                    'excel',
                    'print'
                ]
            });
        }, 200);
    }

    document.addEventListener('DOMContentLoaded', function () {
        initializeDataTable();
    });

    Livewire.on('dataTable', () => {
        initializeDataTable();
    });
</script>
@endpush



