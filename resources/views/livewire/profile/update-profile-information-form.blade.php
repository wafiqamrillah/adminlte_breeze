<?php

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component
{
    public string $name = '';
    public string $email = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $path = session('url.intended', RouteServiceProvider::HOME);

            $this->redirect($path);

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section>
    <div class="row">
        <h4 class="card-title">
            {{ __('Profile Information') }}

            <small class="text-muted">
                {{ __("Update your account's profile information and email address.") }}
            </small>
        </h4>
    </div>

    <form wire:submit="updateProfileInformation" class="mt-3">
        <!-- Name -->
        <div class="form-group">
            <label for="name">
                {{ __('Name') }}
            </label>
            <input
                type="text"
                wire:model="name"
                id="name"
                name="name"
                class="form-control @error('name') is-invalid @enderror"
                required
                autofocus
                autocomplete="name">

            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <!-- Email address -->
        <div class="form-group">
            <label for="email">
                {{ __('Email') }}
            </label>
            <input
                type="text"
                wire:model="email"
                id="email"
                name="email"
                class="form-control @error('email') is-invalid @enderror"
                required
                autocomplete="username">

            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
                <!-- Email Verification Notice -->
                <div class="mt-2">
                    <p class="text-sm text-muted">
                        {{ __('Your email address is unverified.') }}

                        <button wire:click.prevent="sendVerification" class="btn btn-default">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-sm text-success">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <button type="submit" class="btn btn-primary">
                {{ __('Save') }}
            </button>

            <x-action-message class="align-middle float-right" on="profile-updated">
                {{ __('Saved.') }}
            </x-action-message>
        </div>
    </form>
</section>