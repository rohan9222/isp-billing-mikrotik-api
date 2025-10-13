<div class="container-fluid">
    <div x-data="{ isEditing: null, tempValue: {} }" class="row p-4">
        <div class="col-md-6">
            <x-mikrotik.section-form>
                <x-slot name="title">{{ __('Customer Information') }}</x-slot>
                <x-slot name="aside">
                    <table class="table table-sm">
                        @foreach ($fields['customer'] as $field => $value)
                            <tr>
                                <th>{{ ucfirst(str_replace('_', ' ', $field)) }}:</th>
                                <td>
                                    <span x-show="isEditing !== '{{ $field }}'"
                                        @click="isEditing = '{{ $field }}';
                                        tempValue['{{ $field }}'] = '{{ $fields['customer'][$field] ?? '' }}';
                                        $wire.startEditing('{{ $field }}');"
                                        style="cursor: pointer;">
                                        {{ !empty($fields['customer'][$field]) ? $fields['customer'][$field] : 'Empty' }}
                                    </span>

                                    <!-- Check if the field is 'customer_status' to show a dropdown -->
                                    <div x-show="isEditing === '{{ $field }}'"
                                        @click.away="isEditing = null;
                                        tempValue['{{ $field }}'] = '{{ $fields['customer'][$field] ?? '' }}';
                                        $wire.cancelEditing('{{ $field }}')"
                                        style="display: none;" class="input-group mt-2">

                                        @if ($field === 'status')
                                            <select x-model="tempValue['{{ $field }}']"
                                                    class="form-control form-control-sm h-50"
                                                    {{-- @change="$wire.updateCustomer('{{ $field }}', tempValue['{{ $field }}']); --}}
                                                    isEditing = null">
                                                <option value="">Select Status</option>
                                                <option value="active">Active</option>
                                                <option value="inactive">Inactive</option>
                                                <option value="pending">Pending</option>
                                            </select>
                                        @else
                                            <input type="text" x-model="tempValue['{{ $field }}']"
                                                class="form-control form-control-sm h-50"
                                                {{-- @keydown.enter="$wire.updateCustomer('{{ $field }}', tempValue['{{ $field }}']); --}}
                                                isEditing = null"
                                                placeholder="Edit {{ ucfirst(str_replace('_', ' ', $field)) }}" autofocus />
                                        @endif

                                        <button @click="$wire.updateCustomer('{{ $field }}', tempValue['{{ $field }}']);
                                                isEditing = null"
                                                class="btn btn-white text-success h-50"><i class="bi bi-check2-circle"></i></button>

                                        <button @click="isEditing = null;
                                                tempValue['{{ $field }}'] = '{{ $fields['customer'][$field] ?? '' }}';
                                                $wire.cancelEditing('{{ $field }}')"
                                                class="btn btn-white h-50 text-danger "><i class="bi bi-x-circle"></i></button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </x-slot>
            </x-mikrotik.section-form>
        </div>
    </div>
</div>

