<?php

use App\Livewire\Actions\Logout;
use App\Livewire\Forms\LogoutForm;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <!-- Sidebar Toggle Button -->
        <li class="nav-item">
            <a
                x-on:click="toggle()"
                x-on:click.away="clickAway()"
                class="nav-link"
                role="button">
                <i class="fas fa-bars"></i>
            </a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Profile Dropdown Menu -->
        <li x-data="{ name: '{{ auth()->user()->name }}', open: false }"
            x-on:profile-updated.window="name = $event.detail.name"
            class="nav-item dropdown user-menu"
            x-cloak>
            <a
                x-on:click="open = !open"
                href="javascript:void(0);"
                class="nav-link dropdown-toggle">
                <img src="https://adminlte.io/themes/v3/dist/img/user2-160x160.jpg" class="user-image img-circle elevation-2" alt="User Image">
                <span x-text="name" class="d-none d-md-inline"></span>
            </a>
            <ul
                x-show="open"
                x-bind:class="{ 'show': open }"
                x-on:click.away="open= false"
                x-cloak
                x-transition.scale.origin.top.right
                class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <!-- User image -->
                <li class="user-header bg-primary">
                    <img src="https://adminlte.io/themes/v3/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        
                    <p x-text="name"></p>
                </li>

                <!-- Menu Footer -->
                <li class="user-footer">
                    <a href="{{ route('profile') }}" class="btn btn-default btn-flat" wire:navigate>
                        {{ __('Profile') }}
                    </a>
                    <button wire:click="logout" class="btn btn-default btn-flat float-right">
                        {{ __('Log Out') }}
                    </button>
                </li>
            </ul>
        </li>
    </ul>
</nav>