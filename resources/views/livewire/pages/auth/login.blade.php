<?php

use App\Livewire\Forms\LoginForm;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.root')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirect(
            session('url.intended', RouteServiceProvider::HOME),
            navigate: true
        );
    }
}; ?>

<div class="hold-transition login-page">
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="login-box">
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">
                    {{ __('Sign in to start your session') }}
                </p>

                <form wire:submit="login">
                    <!-- Email Address -->
                    <div class="input-group mb-3">
                        <input type="email" id="email" name="email" wire:model="form.email" class="form-control" placeholder="{{ __('Email') }}" autofocus autocomplete="username">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>

                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>
            
                    <!-- Password -->
                    <div class="input-group mb-3">
                        <input type="password" id="password" name="password" wire:model="form.password" class="form-control" placeholder="{{ __('Password') }}" autocomplete="current-password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
            
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>
            
                    <div class="row">
                        <!-- Remember Me -->
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="remember" name="remember" wire:model="form.remember">
                                <label for="remember">
                                    {{ __('Remember me') }}
                                </label>
                            </div>
                        </div>
            
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">
                                {{ __('Log in') }}
                            </button>
                        </div>
                    </div>
                    
                    <!-- Forgot password -->
                    @if (Route::has('password.request'))
                        <p class="mb-1">
                            <a href="{{ route('password.request') }}" wire:navigate>
                                {{ __('Forgot your password?') }}
                            </a>
                        </p>
                    @endif
                    <p class="mb-1">
                        <a href="{{ route('register') }}" wire:navigate>
                            {{ __('Register new account') }}
                        </a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>
