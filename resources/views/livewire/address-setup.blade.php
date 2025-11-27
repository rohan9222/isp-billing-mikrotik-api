<div class="zoom-in">
    <x-slot name="header">
        {{ __('Address Setup') }}
    </x-slot>

    <div class="row g-2">
        <div class="col-md-3">
            <x-mikrotik.section-form>
                <x-slot name="title">{{ __('Edit Section') }}</x-slot>
                <x-slot name="aside">
                    @if(auth()->user()->can('address-setup-create') || $addressFieldId)
                        <form wire:submit.prevent="submit" x-data="{ input_type: @entangle('input_type') }">
                            {{-- 1st input --}}
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <div class="room border p-3">
                                        <!--start select region-->
                                        <div class="row">
                                            <div class="col-md-12">
                                                <x-mikrotik.form-group
                                                    column="col-12"
                                                    label="Label Name"
                                                    name="label"
                                                    type="text"
                                                    labelClass="text-info"
                                                />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-5 col-sm-12">
                                                <div class="form-group">
                                                    <label for="required" class="checkbox-inline">
                                                        <input type="checkbox" id="required" class="form-check-input" wire:model="required" >Required<span style="color:red">*</span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-7 col-sm-12">
                                                <div class="form-group pull-right">
                                                    <label for="print_preview" class="checkbox-inline">
                                                        <input type="checkbox" id="print_preview" class="form-check-input" wire:model="print_preview">
                                                        Preview in receipt
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-md-12 col-sm-12">
                                                <div class="form-group">
                                                    <label for="complain_preview" class="checkbox-inline">
                                                        <input type="checkbox" id="complain_preview" class="form-check-input" wire:model="complain_preview">
                                                        Preview in Complain List
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- 2nd input --}}
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <div class="room border p-3">
                                        <div class="row">
                                            <div class="col-md-12">
                                                {{-- <div class="form-group">
                                                    <label for="type" style="color:purple">Type</label>
                                                    <select class="form-control" wire:model="input_type" id="input_type">
                                                        <option value="" label="Select Type" selected="selected">Select Type</option>
                                                        <option value="dropdown" label="Dropdown">Dropdown</option>
                                                        <option value="text" label="Input Text">Input Text</option>
                                                        <option value="textarea" label="Input Box">Input Box</option>
                                                    </select>
                                                    <x-error name='input_type' />
                                                </div> --}}

                                                <x-mikrotik.form-group
                                                    x-model="input_type"
                                                    column="col-12 mb-2"
                                                    label="Type"
                                                    type="dropdownKey"
                                                    name="input_type"
                                                    placeholder="Select Type"
                                                    :options="['dropdown' => 'Dropdown', 'text' => 'Input Text', 'textarea' => 'Input Box']"
                                                />

                                                <!-- Show dropdown input if 'dropdown' is selected -->
                                                <div class="mb-3" x-show="input_type === 'dropdown'" x-cloak>
                                                    <label for="dropdown_input" class="form-label">Type Input</label>
                                                    <div class="input-group mb-2">
                                                        <input type="text" wire:model="dropdown_input" id="dropdown_input" class="form-control" placeholder="Enter type input">
                                                        <button type="button" class="btn btn-secondary" wire:click="addTypeToList">Add to List</button>
                                                    </div>
                                                    <x-input-error for='dropdown_input' />

                                                    <!-- Type List Display -->
                                                    @if (!empty($dropdown_list))
                                                        <ul class="list-group">
                                                            {{-- @dd($dropdown_list) --}}
                                                            @foreach ($dropdown_list as $index => $type)
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                {{ $type }}
                                                                <button type="button" class="btn btn-danger btn-sm" wire:click="removeTypeFromList({{ $index }})">X</button>
                                                            </li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                    <x-input-error for='dropdown_list' />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <div class="form-group pull-right  justify-end">
                                        <button type="submit" class="btn btn-success">Save</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    @else
                        <form>
                            <fieldset disabled>
                                {{-- 1st input --}}
                                <div class="row mt-2">
                                    <div class="col-md-12">
                                        <div class="room border p-3">
                                            <!--start select region-->
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="label" style="color:#00b3ee">Label Name</label>
                                                        <div class="col">
                                                            <input class="form-control" type="text" id="label">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-5 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="required" class="checkbox-inline">
                                                            <input type="checkbox" id="required" class="form-check-input">Required<span style="color:red">*</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-7 col-sm-12">
                                                    <div class="form-group pull-right">
                                                        <label for="print_preview" class="checkbox-inline">
                                                            <input type="checkbox" id="print_preview" class="form-check-input">
                                                            Preview in receipt
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="col-md-12 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="complain_preview" class="checkbox-inline">
                                                            <input type="checkbox" id="complain_preview" class="form-check-input">
                                                            Preview in Complain List
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- 2nd input --}}
                                <div class="row mt-2">
                                    <div class="col-md-12">
                                        <div class="room border p-3">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group mb-2">
                                                        <label for="type" style="color:purple">Type</label>
                                                        <select class="form-control" wire:model="input_type" id="input_type" x-model="input_type">
                                                            <option value="" label="Select Type" selected="selected">Select Type</option>
                                                            <option value="dropdown" label="Dropdown">Dropdown</option>
                                                            <option value="text" label="Input Text">Input Text</option>
                                                            <option value="textarea" label="Input Box">Input Box</option>
                                                        </select>
                                                    </div>

                                                    <!-- Show dropdown input if 'dropdown' is selected -->
                                                    <div class="mb-3" x-show="input_type === 'dropdown'" x-cloak>
                                                        <label for="dropdown_input" class="form-label">Type Input</label>
                                                        <div class="input-group mb-2">
                                                            <input type="text" id="dropdown_input" class="form-control" placeholder="Enter type input">
                                                            <button type="button" class="btn btn-secondary" wire:click="addTypeToList">Add to List</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-md-12">
                                        <div class="form-group pull-right justify-end">
                                            <button class="btn btn-success">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    @endcan
                </x-slot>
            </x-mikrotik.section-form>
        </div>

        {{-- Address Section --}}
        <div class="col-md-6">
            <x-mikrotik.section-form>
                <x-slot name="title">{{ __('Address Section') }}</x-slot>
                <x-slot name="aside">
                    <h4>Address Fields</h4>
                    <div class="p-1">
                        @if (!empty($addressFields))
                            <ul wire:sortable="updateSortOrderAddress" id="sortable-list" class="list-group">
                                @foreach ($addressFields as $field)
                                    <li wire:sortable.item="{{ $field['id'] }}" wire:key="field-{{ $field['id'] }}" class="list-group-item">
                                        <div class="row">
                                            <div class="col-md-4">
                                        <i wire:sortable.handle class="bi bi-dpad-fill"></i>
                                                {{ $field['label'] }}
                                                @if ($field['required'] == 1)
                                                    <span style="color: #ff0000">*</span>
                                                @endif
                                            </div>
                                            <div class="col-md-5">
                                                @if ($field['input_type'] == 'dropdown')
                                                    <select class="form-control">
                                                        @foreach (json_decode($field['dropdown_list']) as $type)
                                                            <option value="{{ $type }}">{{ $type }}</option>
                                                        @endforeach
                                                    </select>
                                                @else
                                                    <input type="text" class="form-control" placeholder="{{ $field['input_type'] }}" disabled>
                                                @endif
                                            </div>
                                            <div class="col-md-3">
                                                @can('address-setup-edit')
                                                    <button type="button" class="btn btn-sm btn-info" wire:click="edit({{ $field['id'] }})"><i class="bi bi-pencil-square"></i></button>
                                                @else
                                                    <button type="button" class="btn btn-sm btn-info disabled"><i class="bi bi-pencil-square"></i></button>
                                                @endcan

                                                @can('address-setup-delete')
                                                    <button type="button" class="btn btn-sm btn-danger" wire:click="delete({{ $field['id'] }})"><i class="bi bi-trash"></i></button>
                                                @else
                                                    <button type="button" class="btn btn-sm btn-danger disabled"><i class="bi bi-trash"></i></button>
                                                @endcan

                                                @if ( $field['print_preview'] == 1 )
                                                    <button type="button" class="btn btn-sm btn-primary"><i class="bi bi-printer"></i></button>
                                                @endif
                                                @if ( $field['complain_preview'] == 1 )
                                                    <button type="button" class="btn btn-sm btn-primary"><i class="bi bi-person-arms-up"></i></button>
                                                @endif
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                            <!-- Save Button -->
                            <button type="button" class="btn btn-success mt-3" wire:click="saveSortOrderAddress">Save Order</button>
                        @else
                            <p>No address fields available.</p>
                        @endif
                    </div>
                </x-slot>
            </x-mikrotik.section-form>
        </div>

        {{-- Receipt Section --}}
        <div class="col-md-3">
            <x-mikrotik.section-form>
                <x-slot name="title">{{ __('Receipt Section') }}</x-slot>
                <x-slot name="aside">
                    <h4>Address Fields</h4>
                    <div wire:sortable="updateSortOrderReceipt" class="p-1">
                        @if (!empty($receiptOrders))
                            @foreach ($receiptOrders as $field)
                                <div wire:sortable.item="{{ $field['id'] }}" wire:key="field-{{ $field['id'] }}" class="row">
                                    <div wire:sortable.handle class="col-md-12 d-block p-1 m-1 border border-gray-300">
                                        {{ $field['label'] }}
                                    </div>
                                </div>
                            @endforeach
                            <!-- Save Button -->
                            <button type="button" class="btn btn-success mt-3" wire:click="saveSortOrderReceipt">Save Order</button>
                        @else
                            <p>No address fields available.</p>
                        @endif
                    </div>
                </x-slot>
            </x-mikrotik.section-form>
        </div>
    </div>
</div>
