<div class="container">
    <div class="row px-5 mx-5">
        <div class="col-md-4">
            <div class="row mt-2">
                <div class="col-md-12">
                    <h4 class="box-shadow text-center bg-info h3 p-2">Collection</h4>
                </div>
                <div class="col-md-12">
                    <input
                        type="search"
                        name="customer_list"
                        class="form-control w-100"
                        placeholder="FCNET-XXX , customer name, mobile, name"
                        wire:model.live="customer_list"
                        autocomplete="off"
                        autofocus=""
                        tabindex="1"
                    >
                    <ul class="list-group position-absolute" style="z-index: 1000;">
                        @foreach ($customers as $customer)
                            <li
                                wire:click="selectCustomer('{{ encrypt($customer->customer_unique_id) }}')"
                                {{-- wire:key="customer-{{ $customer->id }}" --}}
                                class="list-group-item"
                                style="cursor: pointer;"
                            >
                                {{ $customer->customer_unique_id }}, {{ $customer->customer_name }},
                                @foreach ($customer->customerAddress as $address)
                                {{-- {{dd($address)}} --}}
                                    {{ $address->input_type_test }},
                                    {{ $address->input_type_dropdown }},
                                    {{ $address->input_type_textarea }}
                                @endforeach
                                , {{ $customer->mobile }}, {{ $customer->username }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            @if (!empty($info_data))
                <div class="row mt-2">
                    <div class="col-md-12">
                        <h4 class="box-shadow text-center bg-info h3 p-2">Customer Info</h4>
                    </div>
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
                                <td>{{ $info_data->status }}</td>
                            </tr>
                            <tr>
                                <td>Expire Date</td>
                                <td>{{ \Carbon\Carbon::parse($this->info_data->billing->auto_disable_date)->format('d-M-Y') }}</td>
                            </tr>
                            <tr>
                                <td>Monthly Rent</td>
                                <td class="text-right">{{ $info_data->billing->monthly_rent }}</td>
                            </tr>
                            @if ($info_data->billing->additional_charge != 0)
                                <tr>
                                    <td>Additional Charge :</td>
                                    <td class="text-right">{{ $info_data->billing->additional_charge }}</td>
                                </tr>
                            @endif
                            </tr>
                            @if ($info_data->billing->discount != 0)
                                <tr>
                                    <td>Discount :</td>
                                    <td class="text-right">{{ $info_data->billing->discount }}</td>
                                </tr>
                            @endif
                            </tr>
                            @if ($info_data->billing->advance != 0)
                                <tr>
                                    <td>Advance :</td>
                                    <td class="text-right">{{ $info_data->billing->advance }}</td>
                                </tr>
                            @endif
                            </tr>
                            @if ($info_data->billing->vat != 0)
                                <tr>
                                    <td>vat :</td>
                                    <td class="text-right">{{ $info_data->billing->vat }}</td>
                                </tr>
                            @endif
                            @if ($info_data->billing->previous_due != 0)
                                <tr>
                                    <td>Previous Due :</td>
                                    <td class="text-right">{{ $info_data->billing->previous_due }}</td>
                                </tr>
                            @endif
                            <tr>
                                <th>Sum :</th>
                                <td class="text-right">{{ $info_data->billing->total_amount }}</td>
                            </tr>
                            <tr>
                                <th>Total Payable Amount :</th>
                                <th class="text-right">{{ $info_data->billing->due_amount }}</th>
                            </tr>
                            {{-- @foreach ($info_data->paymentSummary as $paymentSummary)
                            use this some data collect
                                <tr>
                                    <td>Collected Amount :</td>
                                    <td class="text-right">
                                    </td>
                                </tr>
                            @endforeach --}}
                            <tr>
                                <td>Collected Amount :</td>
                                <td class="text-right">
                                    @foreach ($collectionSummary as $collectionSummary)
                                       {{ $collectionSummary->customer_collection_unique_id }} -> {{ \Carbon\Carbon::parse($collectionSummary->collection_date)->format('d-M-Y') }} -> {{ $collectionSummary->collection_amount }} <br/>
                                    @endforeach
                                    {{ $paid_amount ?? 0 }}
                                </td>
                            </tr>
                            <tr>
                                <th>Current Payable</th>
                                <th class="text-right">{{$due_amount}}</th>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-12">
                        <form wire:submit.prevent="savePayment" class="row mt-3">
                            <input type="number" class="form-control col m-1" name="paid_amount" id="paid_amount" wire:model="paid_amount" wire:keyup="calculatePayment" min="1" placeholder="Pay Amount" required>

                            <input type="text" class="form-control col m-1" name="invoice" id="invoice" wire:model.live="invoice" placeholder="Invoice No">

                            <input type="date" class="form-control col m-1" name="expire_date" id="expire_date" wire:model.live="expire_date">

                            <button class="btn btn-success btn-sm col m-1">Pay Now</button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
