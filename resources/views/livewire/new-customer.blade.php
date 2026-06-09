<div class="zoom-in">
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('New Customer') }}
        </h2>
    </x-slot>

    <form wire:submit.prevent="save">
        <div class="row g-2">
            <div class="col-12">
                {{-- New Customer --}}
                <x-mikrotik.section-form :class="'row'">
                    <x-slot name="title">{{ __('Customer Information') }}</x-slot>
                    <x-slot name="aside">
                        <x-mikrotik.form-group
                            label="Customer Name"
                            name="customer_name"
                            type="text"
                            placeholder="Customer Name (eg. Mr. John Doe)"
                            required="true"
                        />
                        <x-mikrotik.form-group
                            label="Email Address"
                            name="email"
                            type="text"
                            placeholder="johndoe@example.com"
                        />
                        <x-mikrotik.form-group
                            label="Identification No"
                            name="identification_no"
                            type="text"
                        />
                        <x-mikrotik.form-group
                            label="Mobile Number"
                            type="mobile"
                            name="mobile"
                            required="true"
                        />
                        <x-mikrotik.form-group
                            label="Alternate Mobile Number"
                            type="mobile"
                            name="alternative_mobile"
                        />
                        <x-mikrotik.form-group
                            label="Profession Details"
                            type="text"
                            name="profession"
                            placeholder="eg. Software Engineer, Student, etc."
                        />
                        <x-mikrotik.form-group
                            label="Image"
                            type="file"
                            name="photo_url"
                        />
                        @if ($photo_url)
                            <div class="mt-3">
                                <label>Photo Preview:</label>
                                <img src="{{ $photo_url->temporaryUrl() }}" alt="Image Preview" class="img-thumbnail" style="max-width: 200px; max-height: 200px;"><button type="button" class="btn btn-white btn-sm text-danger mx-2 fs-4" wire:click="removePhoto"><i class="bi bi-x-circle-fill"></i></button>
                            </div>
                        @endif
                    </x-slot>
                    <x-section-border/>
                </x-mikrotik.section-form>
            </div>
            <div class="col-12">
                {{-- Customer Address --}}
                <x-mikrotik.section-form :class="'row'">
                    <x-slot name="title">{{ __('Customer Address') }}</x-slot>
                    <x-slot name="aside">
                        @foreach ($addressFields as $addressField)
                            <x-mikrotik.form-group
                                label="{{$addressField['label']}}"
                                type="{{ $addressField['input_type'] }}"
                                name="address.{{$addressField['label']}}"
                                required="{{$addressField['required'] == 1 ? '*' : ''}}"
                                placeholder="{{$addressField['input_type'] == 'dropdown' ? 'Select Any One' : $addressField['label']}}"
                                :options="json_decode($addressField['dropdown_list'])"
                            />
                        @endforeach
                    </x-slot>
                    <x-section-border/>
                </x-mikrotik.section-form>
            </div>
            @if(!auth()->user()->hasRole('Reseller'))
            <div class="col-12">
                {{-- Server Information --}}
                @can('mikrotik-user-create')
                    <x-mikrotik.section-form :class="'row'">
                        <x-slot name="title">{{ __('Server Information') }}</x-slot>
                        <x-slot name="aside">
                            <x-mikrotik.form-group
                                label="Connection Date"
                                type="date"
                                name="connection_date"
                            />
                            <x-mikrotik.form-group
                                label="Router Name"
                                type="dropdown"
                                name="router_name"
                                wChange="getInterface('router_name')"
                                placeholder="Select Any One"
                                :options="$routers->pluck('router_name')->toArray()"
                            />
                            <x-mikrotik.form-group
                                label="Service Type"
                                type="dropdownKey"
                                name="service"
                                placeholder="Select Any One"
                                required="true"
                                :options="['static' => 'Static', 'pppoe' => 'PPPoE']"
                                wChange="getInterface('service')"
                                :groupstyle="$router_name != '' ? '' : 'display: none;'"
                            />
                            <x-mikrotik.form-group
                                label="Profile"
                                type="dropdown"
                                name="profile"
                                wChange="packageName('profile')"
                                placeholder="Select Any One"
                                required="true"
                                :options="$profileNames"
                                :groupstyle="$service == 'pppoe' ? '' : 'display: none;'"
                            />
                            <x-mikrotik.form-group
                                label="Username/Secrets>Name"
                                type="text"
                                name="username"
                                required="true"
                                placeholder="(eg. FC-40, JohnDoe)"
                                :groupstyle="$service == 'pppoe' ? '' : 'display: none;'"
                            />
                            <x-mikrotik.form-group
                                label="Password"
                                type="text"
                                name="password"
                                :groupstyle="$service == 'pppoe' ? '' : 'display: none;'"
                            />
                            <x-mikrotik.form-group
                                label="PPPoE Remote IP Address (Optional)"
                                type="text"
                                name="ppp_remote_ip"
                                :groupstyle="$service == 'pppoe' ? '' : 'display: none;'"
                            />
                            <x-mikrotik.form-group
                                label="Interface Name"
                                type="dropdown"
                                name="interface"
                                placeholder="Select Any One"
                                required="true"
                                :options="$interfaceNames"
                                :groupstyle="$service == 'static' ? '' : 'display: none;'"
                            />
                            <x-mikrotik.form-group
                                label="Simple Queues > Name"
                                type="text"
                                name="queue_name"
                                required="true"
                                placeholder="(eg. FC-40, JohnDoe)"
                                :groupstyle="$service == 'static' ? '' : 'display: none;'"
                            />
                            <x-mikrotik.form-group
                                label="IP Address"
                                type="text"
                                name="ip_address"
                                {{-- required="true" --}}
                                :groupstyle="($router_name && $service == 'static') || !$router_name ? '' : 'display: none;'"
                            />
                            <x-mikrotik.form-group
                                label="MAC Address"
                                type="text"
                                name="caller_id"
                                placeholder="(eg. 00:11:22:33:44:55)"
                            />
                            <x-mikrotik.form-group
                                label="Bandwidth"
                                type="text"
                                name="bandwidth"
                                placeholder="(e.g.,1K/1k, 1K/1M, 1M/1M, 10M/10M)"
                                :required="$service == 'static' ? true : false"
                                :groupstyle="($router_name && $service == 'static') || !$router_name ? '' : 'display: none;'"
                            />
                            <x-mikrotik.form-group
                                label="Comment"
                                type="text"
                                name="comment"
                            />
                            {{-- this is server info but add on customer info table --}}
                            <x-mikrotik.form-group
                                checkboxLabel="Auto Temporary Disable Feature"
                                type="checkbox"
                                column="col-md-4 col-sm-4"
                                name="auto_disable"
                                :groupstyle="$router_name != '' ? '' : 'display: none;'"
                                />
                            <x-mikrotik.form-group
                                label="Expire Date"
                                type="date"
                                column="col-md-4 col-sm-4"
                                name="auto_disable_date"
                                :value="$auto_disable_date"
                                :groupstyle="$router_name != '' ? '' : 'display: none;'"
                                />
                            <x-mikrotik.form-group
                                label="Auto Temporary Month"
                                type="dropdownKey"
                                column="col-md-4 col-sm-4"
                                name="auto_disable_month"
                                placeholder="Select Any One"
                                :options="['0' => 'Current Month', '1' => '1st Month', '2' => '2nd Month', '3' => '3rd Month' , '4' => '4th Month', '5' => '5th Month', '6' => '6th Month', '7' => '7th Month', '8' => '8th Month', '9' => '9th Month', '10' => '10th Month', '11' => '11th Month', '12' => '12th Month']"
                                selectedValue="0"
                                :groupstyle="$router_name != '' ? '' : 'display: none;'"
                                />
                        </x-slot>
                        <x-section-border/>
                    </x-mikrotik.section-form>
                @endcan
            </div>
            @endif
            <div class="col-12">
                {{-- Billing Information --}}
                <x-mikrotik.section-form :class="'row'">
                    <x-slot name="title">{{ __('Billing Information') }}</x-slot>
                    <x-slot name="aside">
                    {{-- this is server info but add on customer info table --}}
                        <x-mikrotik.form-group
                            label="Package Name"
                            type="dropdown"
                            name="package_name"
                            wChange="calculateTotal('package_name')"
                            placeholder="Select Any One"
                            required="true"
                            :options="$packages->pluck('package')->toArray()"
                        />
                        <x-mikrotik.form-group
                            label="Mothly Charge"
                            type="number"
                            name="monthly_rent"
                            wInput="calculateTotal('monthly_rent')"
                            required="true"
                        />
                        <x-mikrotik.form-group
                            label="Due Amount"
                            type="number"
                            wInput="calculateTotal('due_amount')"
                            name="due_amount"
                        />
                        <x-mikrotik.form-group
                            label="Additional Charge"
                            type="number"
                            wInput="calculateTotal('additional_charge')"
                            name="additional_charge"
                        />
                        <x-mikrotik.form-group
                            label="Discount"
                            type="number"
                            wInput="calculateTotal('discount')"
                            name="discount"
                        />
                        <x-mikrotik.form-group
                            label="Advance"
                            type="number"
                            wInput="calculateTotal('advance')"
                            name="advance"
                        />
                        <x-mikrotik.form-group
                            label="Vat (%)"
                            type="number"
                            wInput="calculateTotal('vat')"
                            name="vat"
                        />
                        <x-mikrotik.form-group
                            label="Total Amount"
                            type="number"
                            name="total_amount"
                            readonly
                        />
                    </x-slot>
                    <x-section-border/>
                </x-mikrotik.section-form>
            </div>
            <div class="col-12">
                {{-- Office Information --}}
                <x-mikrotik.section-form :class="'row'">
                    <x-slot name="title">{{ __('Office Information') }}</x-slot>
                    <x-slot name="aside">
                        <x-mikrotik.form-group
                            label='Billing Type'
                            type="radio"
                            column="col-md-4 col-sm-4"
                            name="billing_type"
                            :options="['prepaid' => 'Prepaid', 'postpaid' => 'Postpaid']"
                        />
                        <x-mikrotik.form-group
                            label="Type of Connection"
                            type="radio"
                            column="col-md-4 col-sm-4"
                            name="connection_type"
                            :options="['fiber' => 'Fiber', 'wired' => 'Wired', 'wireless' => 'Wireless']"
                        />
                        <x-mikrotik.form-group
                            label="Type of Connectivity"
                            type="radio"
                            column="col-md-4 col-sm-4"
                            name="connectivity_type"
                            :options="['shared' => 'Shared', 'dedicated' => 'Dedicated']"
                        />
                        <x-mikrotik.form-group
                            label="Type of Client"
                            type="dropdownKey"
                            name="client_type"
                            placeholder="Select Any One"
                            :options="['home' => 'Home','commercial' => 'Commercial','Corporate' => 'Corporate', 'business' => 'Business']"
                        />
                        <x-mikrotik.form-group
                            label="Distribution Location Point"
                            type="dropdownKey"
                            name="distribution_location"
                            placeholder="Select Any One"
                            :options="['DC' => 'DC', 'NOC' => 'NOC', 'POP'=>'POP']"
                        />
                        <x-mikrotik.form-group
                            label="Description"
                            type="text"
                            name="description"
                        />
                        <x-mikrotik.form-group
                            label="Note"
                            type="text"
                            name="note"
                        />
                        @php
                            $usersData =[];
                            if (auth()->user()->hasRole('Super Admin')) {
                                foreach ($users as $user) {
                                    $usersData[$user->id] = $user->name;
                                }
                            } else {
                                $usersData[auth()->id()] = auth()->user()->name;
                            }
                        @endphp
                        <x-mikrotik.form-group
                            label="Connected By"
                            type="dropdownKey"
                            name="connected_by"
                            placeholder="Select Any One"
                            required="true"
                            :options="$usersData"
                        />
                        <x-mikrotik.form-group
                            label="Security Deposit"
                            type="text"
                            name="security_deposit"
                        />
                    </x-slot>

                    <x-section-border/>
                </x-mikrotik.section-form>
            </div>
            <div class="col-12">
                {{-- Buttons --}}
                <div class="p-2 m-2 position-md-fixed bottom-0 end-0">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="reset" class="btn btn-danger">Clear All</button>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // remove invalid feedback and error
            $('input, textarea, select').on('focus', function () {
                $(this).removeClass('is-invalid'); // remove invalid class
                $(this).nextAll('.invalid-feedback').remove(); // remove invalid feedback
            });

            // $("#package_name").on('change', function () {
            //     var package = $(this).val();
            //     if (package) {
            //         $("#monthly_rent").val(package).nextAll('.invalid-feedback').remove().removeClass('is-invalid');
            //         @this.set('monthly_rent', package); // Update Livewire property
            //         calculateTotal(); // Call your function to calculate total
            //     }
            // });

            // // // Function to calculate total
            // function calculateTotal() {
            //     // Get values and parse them as numbers (or default to 0 if empty)
            //     var monthlyRent = parseFloat($('#monthly_rent').val()) || 0.0;
            //     var dueAmount = parseFloat($('#due_amount').val()) || 0.0;
            //     var additionalCharge = parseFloat($('#additional_charge').val()) || 0.0;
            //     var discount = parseFloat($('#discount').val()) || 0.0;
            //     var advance = parseFloat($('#advance').val()) || 0.0;
            //     var vat = parseFloat($('#vat').val()) || 0.0;

            //     // Calculate subtotal
            //     var subtotal = monthlyRent + dueAmount + additionalCharge - discount - advance;

            //     var vatAmount = (vat / 100) * subtotal;   // Calculate VAT

            //     var total_amount = subtotal + vatAmount;    // Calculate total amount

            //     @this.set('total_amount', total_amount.toFixed(2)); // Update Livewire property total amount

            //     // Set the total amount to the "Total Amount" field
            //     $('#total_amount').val(total_amount.toFixed(2)); // Two decimal places
            // }

            // // Trigger calculation on change of any input field
            // $('#monthly_rent, #due_amount, #additional_charge, #discount, #advance, #vat').on('input', calculateTotal);

            // Listen for Mikrotik error
            Livewire.on('mikrotikError', (interfaces) => {
                Swal.fire({
                    title: "Are you sure?",
                    text: interfaces,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, create it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.createUser();
                    }
                });
            });
            Livewire.on('mikrotikError', (error) => {
                Swal.fire({
                    title: "Are you sure?",
                    text: error,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, create it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.createUser();
                    }
                });
            });
        });
    </script>
    {{-- <script>
        document.addEventListener('DOMContentLoaded', function () {
            $('#photo_url').on('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                resizeImage(file, 300, 300, function(resizedBlob) {
                    // Create a temporary URL for the resized image to preview
                    const previewUrl = URL.createObjectURL(resizedBlob);
                    $('#imagePreviewphoto_url').attr('src', previewUrl).show();

                    @this.set('imagePreview', previewUrl);
                    const resizedFile = new File([resizedBlob], file.name, { type: file.type });

                    // Set the resized file in Livewire
                    @this.upload('photo_url', resizedFile);
                    console.log(previewUrl,resizedFile);
                });
            }
        });
        });

        function resizeImage(file, maxWidth, maxHeight, callback) {
            const reader = new FileReader();
            reader.onload = function(event) {
                const img = new Image();
                img.onload = function() {
                    const canvas = document.createElement('canvas');
                    const ctx = canvas.getContext('2d');

                    let width = img.width;
                    let height = img.height;

                    // Maintain aspect ratio while resizing
                    if (width > height) {
                        if (width > maxWidth) {
                            height *= maxWidth / width;
                            width = maxWidth;
                        }
                    } else {
                        if (height > maxHeight) {
                            width *= maxHeight / height;
                            height = maxHeight;
                        }
                    }

                    canvas.width = width;
                    canvas.height = height;
                    ctx.drawImage(img, 0, 0, width, height);

                    // Convert the canvas to Blob and pass it to the callback
                    canvas.toBlob(callback, file.type, 0.8); // Adjust quality as needed
                };
                img.src = event.target.result;
            };
            reader.readAsDataURL(file);
        }
    </script> --}}

@endpush

{{-- @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            $("#router_name").on('change', function () {
            var router = $(this).val();
            if(router) {
                @this.set('showService', true);
                $('#service, #auto_disable, #auto_disable_date, #auto_disable_month, #bandwidth').parents('.form-group').show();
                $('#ip_address').parents('.form-group').hide();
            }else {
                @this.set('showService', false);
                $('#service, #auto_disable, #auto_disable_date, #auto_disable_month, #bandwidth, #interface, #queue_name, #profile, #username, #password, #ppp_remote_ip').val('').parents('.form-group').hide();
                $('#ip_address').parents('.form-group').show().val('');

                const properties = ['router_name', 'ip_address', 'service', 'interface', 'queue_name', 'profile', 'username', 'password', 'ppp_remote_ip', 'bandwidth'];
                properties.forEach(prop => {
                    @this.set(prop, '');
                });
            }
            // });


            $("#service").on('change', function () {
                var service = $(this).val();
                if(service && service == 'static') {
                    @this.set('showInterface', true);
                    @this.set('showBandwidth', true);
                    @this.set('showProfile', false);
                }else if(service && service == 'pppoe') {
                    console.log(service);
                    @this.set('showProfile', true);
                    @this.set('showInterface', false);
                    @this.set('showBandwidth', false);
                }else {
                    @this.set('showProfile', false);
                    @this.set('showInterface', false);
                    @this.set('showBandwidth', true);

                    const properties = ['router_name', 'ip_address', 'service', 'interface', 'queue_name', 'profile', 'username', 'password', 'ppp_remote_ip', 'bandwidth'];
                    properties.forEach(prop => {
                        @this.set(prop, '');
                    });
                    // $('#profile, #username, #password, #ppp_remote_ip, #bandwidth').parents('.form-group').hide();
                    // @this.set('profile, username, password, ppp_remote_ip', '');
                }
            });

            $("#package_name").on('change', function () {
                var package = $(this).val();
                if (package) {
                    $("#monthly_rent").val(package).nextAll('.invalid-feedback').remove().removeClass('is-invalid');
                    @this.set('monthly_rent', package); // Update Livewire property
                    calculateTotal(); // Call your function to calculate total
                }
            });

            // // Function to calculate total
            function calculateTotal() {
                // Get values and parse them as numbers (or default to 0 if empty)
                var monthlyRent = parseFloat($('#monthly_rent').val()) || 0.0;
                var dueAmount = parseFloat($('#due_amount').val()) || 0.0;
                var additionalCharge = parseFloat($('#additional_charge').val()) || 0.0;
                var discount = parseFloat($('#discount').val()) || 0.0;
                var advance = parseFloat($('#advance').val()) || 0.0;
                var vat = parseFloat($('#vat').val()) || 0.0;

                // Calculate subtotal
                var subtotal = monthlyRent + dueAmount + additionalCharge - discount - advance;

                // Calculate VAT
                var vatAmount = (vat / 100) * subtotal;

                // Calculate total amount
                var total_amount = subtotal + vatAmount;

                // Set the subtotal to the "Subtotal" field
                 @this.set('total_amount', total_amount.toFixed(2));
                // @this.set('monthly_rent', monthlyRent);

                // Set the total amount to the "Total Amount" field
                $('#total_amount').val(total_amount.toFixed(2)); // Two decimal places
            }

            // Trigger calculation on change of any input field
            $('#monthly_rent, #due_amount, #additional_charge, #discount, #advance, #vat').on('input', calculateTotal);

                   // Listen for Mikrotik error

           Livewire.on('mikrotikError', (interfaces) => {
                Swal.fire({
                    title: "Are you sure?",
                    text: interface,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, create it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.createUser();
                    }
                });
            });

        });
    </script>
@endpush --}}
