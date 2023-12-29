<x-app-layout>
    <x-slot name="header">
        <h1 class="m-0">
            {{ __('Settings') }}
        </h1>
    </x-slot>

    <div class="card">
        <div class="card-body">
            <table class="table table-hover table-bordered">
                <tr>
                    <td x-on:click.prefetch="Livewire.navigate('{{ route('settings.users') }}')" style="cursor: pointer;">
                        <i class="fas fa-users"></i> {{ __('User Management') }}
                    </td>
                </tr>
                <tr>
                    <td x-on:click.prefetch="Livewire.navigate('{{ route('settings.menus') }}')" style="cursor: pointer;">
                        <i class="fas fa-list-ul"></i> {{ __('Menu Management') }}
                    </td>
                </tr>
            </table>
        </div>
    </div>
</x-app-layout>
