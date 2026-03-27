<div class="zoom-in">
    <x-slot name="header">
        {{ __('Main Site Setup') }}
    </x-slot>

    <div class="row g-2 justify-content-center">
        <div class="col-12">
            <x-mikrotik.section-form>
                <x-slot name="title">{{ __('Full Website Management') }}</x-slot>
                <x-slot name="aside">
                    
                    <form wire:submit.prevent="save">
                        {{ $this->form }}

                        <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-primary px-5 py-2 fw-bold text-uppercase shadow-sm">
                                <i class="bi bi-save me-2"></i> Save All Changes
                            </button>
                        </div>
                    </form>

                    <x-filament-actions::modals />
                </x-slot>
            </x-mikrotik.section-form>
        </div>
    </div>
</div>
