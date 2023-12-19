<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

new class extends Component
{
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Update the password for the currently authenticated user.
     */
    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        $this->dispatch('password-updated');
    }
}; ?>

<section>
    <div class="row">
        <h4 class="card-title">
            {{ __('Update Password') }}

            <small class="text-muted">
                {{ __('Ensure your account is using a long, random password to stay secure.') }}
            </small>
        </h4>
    </div>

    <form wire:submit="updatePassword" class="mt-3">
        <!-- Current Password -->
        <div class="form-group">
            <label for="current_password">
                {{ __('Current Password') }}
            </label>
            <input
                type="password"
                wire:model="current_password"
                id="current_password"
                name="current_password"
                class="form-control @error('current_password') is-invalid @enderror"
                required
                autocomplete="current-password">

            <x-input-error class="mt-2" :messages="$errors->get('current_password')" />
        </div>

        <!-- New Password -->
        <div class="form-group">
            <label for="password">
                {{ __('New Password') }}
            </label>
            <input
                type="password"
                wire:model="password"
                id="password"
                name="password"
                class="form-control @error('password') is-invalid @enderror"
                required
                autocomplete="new-password">

            <x-input-error class="mt-2" :messages="$errors->get('password')" />
        </div>

        <!-- Confirm New Password -->
        <div class="form-group">
            <label for="password_confirmation">
                {{ __('Confirm Password') }}
            </label>
            <input
                type="password"
                wire:model="password_confirmation"
                id="password_confirmation"
                name="password_confirmation"
                class="form-control @error('password_confirmation') is-invalid @enderror"
                required
                autocomplete="new-password">

            <x-input-error class="mt-2" :messages="$errors->get('password_confirmation')" />
        </div>

        <div>
            <button type="submit" class="btn btn-primary">
                {{ __('Save') }}
            </button>

            <x-action-message class="align-middle float-right" on="password-updated">
                {{ __('Saved.') }}
            </x-action-message>
        </div>
    </form>
</section>