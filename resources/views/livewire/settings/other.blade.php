<section class="w-full">
    @include('partials.settings-heading')

    <flux:heading class="sr-only">{{ __('Other Settings') }}</flux:heading>

    <x-settings.layout :heading="__('Other Settings')" :subheading="__('Update your other settings')">

        <form wire:submit="updateOtherInformation" class="my-6 w-full space-y-6">
            <flux:input wire:model="field_name_1" :label="__('Field Name 1')" type="text" required autofocus autocomplete="field_name_1" />
            <flux:input wire:model="field_name_2" :label="__('Field Name 2')" type="text" required autofocus autocomplete="field_name_2" />
            <flux:input wire:model="field_name_3" :label="__('Field Name 3')" type="text" required autofocus autocomplete="field_name_3" />

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button variant="primary" type="submit" class="w-full">{{ __('Save') }}</flux:button>
                </div>

                <x-action-message class="me-3" on="other-updated">
                    {{ __('Saved.') }}
                </x-action-message>
            </div>
        </form>

    </x-settings.layout>


</section>
