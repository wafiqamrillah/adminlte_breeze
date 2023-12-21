<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component
{
    public string $password = '';

    /**
     * Delete the currently authenticated user.
     */
    public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }
}; ?>


<section>
    <div class="row">
        <h4 class="card-title">
            {{ __('Delete Account') }}
        </h4>
    </div>

    <p class="mt-3">
        {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
    </p>

    <button
        x-data=""
        data-has-alpine-state="false"
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="btn btn-danger">
        {{ __('Delete Account') }}
    </button>
    
    <!--Modal-->
    <x-modal
        element="form"
        wire:submit="deleteUser"
        name="confirm-user-deletion"
        :show="$errors->isNotEmpty()"
        focusable>

        <h2 class="text-lg">
            {{ __('Are you sure you want to delete your account?') }}
        </h2>

        <p class="mt-1 text-sm">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
        </p>

        <div class="mt-6 form-group">
            <label for="password" class="sr-only">
                {{ __('Password') }}
            </label>

            <input
                type="password"
                wire:model="password"
                id="password"
                name="password"
                class="form-control @error('current_password') is-invalid @enderror"
                required />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <x-slot:footer class="justify-content-between">
            <button x-on:click="$dispatch('close')" type="button" class="btn btn-default" data-dismiss="modal">
                {{ __('Cancel') }}
            </button>
            <button type="submit" class="btn btn-danger">
                {{ __('Delete Account') }}
            </button>
        </x-slot:footer>
    </x-modal>
</section>
