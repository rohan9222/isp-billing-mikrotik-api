<div class="zoom-in">
    <x-slot name="header">
        {{ __('SMS Setup') }}
    </x-slot>
    <div class="row d-flex justify-content-center">
        <div class="col-md-3">
            <x-mikrotik.section-form>
                <x-slot name="title">{{ __('SMS Balance') }}</x-slot>
                <x-slot name="aside">
                    <table class="table table-sm text-capitalize">
                        <tr>
                            <td>{{__('Your SMS Profile Name')}}</td>
                        </tr>
                        <tr>
                            <td class="text-end"><span class="badge bg-success">{{($profile['status'] != 'error') ? $profile['data']['first_name'] . ' ' . $profile['data']['last_name'] : 'N/A'}}</span></td>
                        </tr>
                        <tr>
                            <td>{{__('Your SMS Profile Email')}}</td>
                        </tr>
                        <tr>
                            <td class="text-end"><span class="badge bg-success">{{($profile['status'] != 'error') ? $profile['data']['email'] : 'N/A'}}</span></td>
                        </tr>
                        <tr>
                            <td>{{__('SMS Balance')}}</td>
                        </tr>
                        <tr>
                            <td class="text-end">
                                @if($balance['status'] == 'error')
                                    <span class="badge bg-danger">N/A</span>
                                @else
                                    @if ($balance['data']['remaining_balance'] > 0)
                                        <span class="badge bg-success">{{$balance['data']['remaining_balance']}}</span>
                                        <span class="ms-1 badge text-warning text-capitalize">Expired On: {{$balance['data']['expired_on']}}</span>
                                    @else
                                        <span class="badge bg-danger">{{$balance['data']['remaining_balance']}} </span>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    </table>
                </x-slot>
            </x-mikrotik.section-form>
        </div>
        <div class="col-md-4">
            {{-- <x-mikrotik.section-form>
                <x-slot name="title">{{ __('Create Package') }}</x-slot>
                <x-slot name="aside">
                    <div class="col-12 px-4 py-1">
                        @foreach ($smsTemps as $smsTemp)
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" wire:click="setSmsActive({{$smsTemp->id}})" id="sms-{{$smsTemp->id}}" {{ ($smsTemp->is_active == 1) ? 'checked' : ''}}>
                                <label class="form-check-label text-capitalize" for="sms-{{$smsTemp->id}}">{{ucfirst(str_replace('_', ' ', $smsTemp->template_name))}}</label>

                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#smsModal-{{$smsTemp->id}}">
                                Edit
                                </button>

                                <!-- Modal -->
                                <div class="modal fade" id="smsModal-{{$smsTemp->id}}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <form wire:submit.prevent="updateSms({{$smsTemp->id}})">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-6 text-capitalize" >{{ucfirst(str_replace('_', ' ', $smsTemp->template_name))}}</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <span class="text-center">Message</span>
                                                    <textarea class="form-control w-100" wire:model="smsTempList.{{$smsTemp->id}}" name="smsTemp-{{$smsTemp->id}}" id="smsTemp-{{$smsTemp->id}}" rows="5">{{$smsTemp->template}}</textarea>
                                                    <span class="text-center">Example</span>
                                                    <div class="mt-1 p-2 rounded-sm bg-success-subtle w-100">{{$smsTemp->template_ex_en}}</div>
                                                    <span class="text-center">In Bangla</span>
                                                    <div class="mt-1 p-2 rounded-sm bg-success-subtle w-100">{{$smsTemp->template_ex_bn}}</div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-slot>
            </x-mikrotik.section-form> --}}
            <x-mikrotik.section-form>
                <x-slot name="title">{{ __('Create Package') }}</x-slot>
                <x-slot name="aside">
                    <div class="col-12 px-4 py-1">
                        @foreach ($smsTemps as $smsTemp)
                            <div class="form-check form-switch mb-3">
                                <!-- Toggle Active Status -->
                                @can('sms-setup')
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        role="switch"
                                        wire:click="setSmsActive({{ $smsTemp->id }})"
                                        id="sms-{{ $smsTemp->id }}"
                                        {{ $smsTemp->is_active ? 'checked' : '' }}
                                    >
                                @else
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        role="switch"
                                        id="sms-{{ $smsTemp->id }}"
                                        {{ $smsTemp->is_active ? 'checked' : '' }}
                                        disabled
                                    >
                                @endcan
                                <label
                                    class="form-check-label text-capitalize"
                                    for="sms-{{ $smsTemp->id }}"
                                >
                                    {{ ucfirst(str_replace('_', ' ', $smsTemp->template_name)) }}
                                </label>

                                <!-- Edit Button -->
                                <button
                                    type="button"
                                    class="btn btn-sm btn-primary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#smsModal-{{ $smsTemp->id }}"
                                >
                                    Edit
                                </button>

                                <!-- Modal for Editing SMS Template -->
                                <div class="modal fade" id="smsModal-{{ $smsTemp->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <form wire:submit.prevent="updateSms({{ $smsTemp->id }})">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-6 text-capitalize">
                                                        {{ ucfirst(str_replace('_', ' ', $smsTemp->template_name)) }}
                                                    </h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <!-- SMS Template Message -->
                                                    @can('sms-setup')
                                                        <span class="text-center">Message</span>
                                                        <textarea
                                                            class="form-control w-100"
                                                            wire:model.defer="smsTempList.{{ $smsTemp->id }}"
                                                            id="smsTemp-{{ $smsTemp->id }}"
                                                            rows="5">
                                                        </textarea>
                                                    @endcan

                                                    <!-- Example in English -->
                                                    <span class="text-center">Example</span>
                                                    <div class="mt-1 p-2 rounded-sm bg-success-subtle w-100">
                                                        {!! $smsTemp->template_ex_en !!}
                                                    </div>

                                                    <!-- Example in Bangla -->
                                                    <span class="text-center">In Bangla</span>
                                                    <div class="mt-1 p-2 rounded-sm bg-success-subtle w-100">
                                                        {!! $smsTemp->template_ex_bn !!}
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    @can('sms-setup')
                                                        <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Save changes</button>
                                                    @endcan
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-slot>
            </x-mikrotik.section-form>
        </div>
        <div class="col-md-4">
            <x-mikrotik.section-form>
                <x-slot name="title">{{ __('Create Package') }}</x-slot>
                <x-slot name="aside">
                </x-slot>
            </x-mikrotik.section-form>
        </div>
    </div>
</div>
