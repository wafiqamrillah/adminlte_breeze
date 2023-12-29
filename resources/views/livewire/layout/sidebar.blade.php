<?php

use Livewire\Volt\Component;
use function Livewire\Volt\{computed, protect};

$menus = computed(function() {
    return \App\Models\System\Menu::query()
        ->withActiveChildren()
        ->whereNull('parent_id')
        ->whereIn('type', ['menu', 'header'])
        ->active()
        ->get()
        ->toArray();
});
?>

<aside x-cloak class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ route('dashboard') }}" class="brand-link" wire:navigate>
        <img src="https://adminlte.io/themes/v3/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">
            {{ config('app.name', 'Laravel') }}
        </span>
    </a>
    
    <div x-on:menu-updated.window="$wire.$refresh()" class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                @foreach ($this->menus as $menu)
                    <x-layouts.menu.sidebar-menu :menu="$menu" />
                @endforeach
            </ul>
        </nav>
    </div>
</aside>