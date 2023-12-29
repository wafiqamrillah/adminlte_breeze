<x-app-layout>
    <x-slot name="header">
        <h1 class="m-0">
            {{ __('User Management') }}
        </h1>
    </x-slot>

    <div class="card card-primary card-outline">
        <div class="card-body">
            <livewire:settings.user-management.users-table />
        </div>
    </div>
</x-app-layout>