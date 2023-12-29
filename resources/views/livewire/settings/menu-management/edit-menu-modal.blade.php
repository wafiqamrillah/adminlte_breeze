<form wire:submit="submit">
    <div class="modal-header">
        <h5 class="modal-title">
            {{ __('Edit Menu') }}
        </h5>

        <button type="button" wire:click="$dispatch('closeModal')" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="modal-body">
        <div class="form-row">
            <!-- Name -->
            <div class="form-group col-xs-12 col-md-6">
                <label for="name">
                    {{ __('Name') }}
                </label>

                <input
                    type="text"
                    wire:model.blur="form.name"
                    id="name"
                    name="name"
                    class="form-control @error('form.name') is-invalid @enderror"
                    placeholder="{{ __('Name') }}"
                    required
                />

                <x-input-error :messages="$errors->get('form.name')" class="mt-2" />
            </div>

            <!-- Parent -->
            <div class="form-group col-xs-12 col-md-6">
                <label for="parent">
                    {{ __('Parent Menu') }}
                </label>
                
                <input type="text"
                    class="form-control"
                    id="parent"
                    name="parent"
                    placeholder="{{ __('Parent Menu') }}"
                    value="{{ $parent_menu ? ($parent_menu['use_translation'] ? __($parent_menu['name']) : $parent_menu['name']) : '' }}"
                    disabled />
            </div>
        </div>

        <div class="form-row">
            <!-- Menu Type -->
            <div class="form-group col-xs-12 col-md-6">
                <label for="type">
                    {{ __('Type') }}
                </label>

                <select name="type" wire:model.blur="form.type" id="type" class="form-control">
                    <option value="header">
                        {{ __('Header') }}
                    </option>
                    <option value="menu">
                        {{ __('Menu') }}
                    </option>
                    <option value="navbar">
                        {{ __('Navbar') }}
                    </option>
                </select>

                <x-input-error :messages="$errors->get('form.type')" class="mt-2" />
            </div>

            <!-- Icon -->
            <div class="form-group col-xs-12 col-md-6">
                <label for="icon_class">
                    {{ __('Icon') }}
                </label>

                <input
                    type="text"
                    wire:model.blur="form.icon_class"
                    id="icon_class"
                    name="icon_class"
                    class="form-control @error('form.icon_class') is-invalid @enderror"
                    placeholder="{{ __('Icon') }}"
                />

                <x-input-error :messages="$errors->get('form.icon_class')" class="mt-2" />
            </div>
        </div>

        <div class="form-row">
            <!-- Link type and Link -->
            <div class="form-group col-12">
                <label for="link">
                    {{ __('Link') }}
                </label>
                
                <div class="input-group">
                    <!-- Link type -->
                    <select name="link_type" id="link_type" wire:model.blur="form.link_type" class="form-control">
                        <option value="route">
                            {{ __('Route') }}
                        </option>
                        <option value="url">
                            {{ __('URL') }}
                        </option>
                    </select>
    
                    <!-- Link -->
                    <input
                        type="text"
                        wire:model.blur="form.link"
                        x-ref="link"
                        id="link"
                        name="link"
                        class="form-control @error('form.link') is-invalid @enderror"
                        placeholder="{{ __('Link') }}"
                        required
                    />

                    <x-input-error :messages="$errors->get('form.link_type')" class="mt-2" />
                    <x-input-error :messages="$errors->get('form.link')" class="mt-2" />
                </div>
            </div>
        </div>

        <div class="form-row">
            <!-- Is Active -->
            <div class="form-group">
                <div class="form-check form-check-inline">
                    <input
                        type="checkbox"
                        wire:model.blur="form.is_active"
                        id="is_active"
                        name="is_active"
                        class="form-check-input"
                    />

                    <label for="is_active" class="form-check-label">
                        {{ __('Active') }}
                    </label>
                </div>
            </div>

            <!-- Use Translation -->
            <div class="form-group">
                <div class="form-check form-check-inline">
                    <input
                        type="checkbox"
                        wire:model.blur="form.use_translation"
                        id="use_translation"
                        name="use_translation"
                        class="form-check-input"
                    />

                    <label for="use_translation" class="form-check-label">
                        {{ __('Use Translation') }}
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" wire:click="$dispatch('closeModal')" class="btn btn-default" data-dismiss="modal">
            {{ __('Cancel') }}
        </button>

        <button type="submit" class="btn btn-success">
            {{ __('Update') }}
        </button>
    </div>
</form>
