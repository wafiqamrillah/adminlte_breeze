<?php

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.root', ['class' => 'hold-transition login-page'])] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(RouteServiceProvider::HOME, navigate: true);
    }
}; ?>

<div class="register-box">
    <div class="card">
        <div class="card-body register-card-body">
            <p class="login-box-msg">
                {{ __('Register a new membership') }}
            </p>
            
            <form wire:submit="register">
                <!-- Name -->
                <div class="input-group mb-3">
                    <input type="text" id="name" name="name" wire:model="name" class="form-control" placeholder="{{ __('Full name') }}" autofocus autocomplete="name">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>

                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>
        
                <!-- Email Address -->
                <div class="input-group mb-3">
                    <input type="email" id="email" name="email" wire:model="email" class="form-control" placeholder="{{ __('Email') }}" autocomplete="username">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                    
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>
        
                <!-- Password -->
                <div class="input-group mb-3">
                    <input type="password" id="password" name="password" wire:model="password" class="form-control" placeholder="{{ __('Password') }}" required autocomplete="new-password">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>

                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>
        
                <!-- Confirm Password -->
                <div class="input-group mb-3">
                    <input type="password" id="password_confirmation" name="password_confirmation" wire:model="password_confirmation" class="form-control" placeholder="{{ __('Confirm Password') }}" required autocomplete="new-password">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
        
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="row">
                    <div class="col-8">
                        <a href="{{ route('login') }}" wire:navigate>
                            {{ __('Already registered?') }}
                        </a>
                    </div>
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block">
                            {{ __('Register') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
