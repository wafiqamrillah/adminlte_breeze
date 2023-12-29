<?php

use function Livewire\Volt\{state, computed, action, protect};
use Livewire\Volt\Component;

$menus = computed(function() {
    return \App\Models\System\Menu::query()
        ->withChildren()
        ->whereNull('parent_id')
        ->whereIn('type', ['menu', 'header'])
        ->get()
        ->toArray();
});

$verifySelectedMenuId = protect(function(?int $selected_menu_id = null): bool|\App\Models\System\Menu {
    // Check if the selected menu is null.
    if ($selected_menu_id === null) {
        $this->addError('selected_menu_id', __('Please select a menu.'));
        return false;
    }

    // Find the menu.
    $menu = \App\Models\System\Menu::find($selected_menu_id);
    
    // Check if the menu is null.
    if ($menu === null) {
        $this->addError('selected_menu_id', __('The selected menu is invalid.'));
        return false;
    }

    return $menu;
});

$moveUp = action(function(?int $selected_menu_id = null): void {
    // Verify the selected menu id.
    $menu = $this->verifySelectedMenuId($selected_menu_id);

    if (!$menu) return;

    // Get the previous menu.
    $previous_menu = \App\Models\System\Menu::query()
        ->where('order', '<', $menu->order)
        ->where('parent_id', $menu->parent_id)
        ->orderBy('order', 'desc')
        ->first();

    // Check if the previous menu is null.
    if ($previous_menu === null) {
        dd(__('The selected menu cannot be moved up.'));
        $this->addError('selected_menu_id', __('The selected menu cannot be moved up.'));
        return;
    }

    \DB::transaction(function() use($menu, $previous_menu) {
        // Swap the order.
        $previous_menu->update([
            'order' => $menu->order,
        ]);

        $menu->update([
            'order' => $menu->order - 1,
        ]);
    });

    // Dispatch the event.
    $this->dispatch('menu-updated');
});

$moveDown = action(function(?int $selected_menu_id = null): void {
    // Verify the selected menu id.
    $menu = $this->verifySelectedMenuId($selected_menu_id);

    if (!$menu) return;

    // Get the next menu.
    $next_menu = \App\Models\System\Menu::query()
        ->where('order', '>', $menu->order)
        ->where('parent_id', $menu->parent_id)
        ->orderBy('order')
        ->first();

    // Check if the next menu is null.
    if ($next_menu === null) {
        dd(__('The selected menu cannot be moved down.'));
        $this->addError('selected_menu_id', __('The selected menu cannot be moved down.'));
        return;
    }

    \DB::transaction(function() use($menu, $next_menu) {
        // Swap the order.
        $next_menu->update([
            'order' => $menu->order,
        ]);

        $menu->update([
            'order' => $menu->order + 1,
        ]);
    });

    // Dispatch the event.
    $this->dispatch('menu-updated');
    $this->dispatch('toggled');
});

$activate = action(function(?int $selected_menu_id = null): void {
    // Verify the selected menu id.
    $menu = $this->verifySelectedMenuId($selected_menu_id);

    if (!$menu) return;

    // Set the menu to active.
    $menu->update([
        'is_active' => true,
    ]);
    
    // Dispatch the event.
    $this->dispatch('menu-updated');

    // Close the modal.
    $this->dispatch('close');
});

$deactivate = action(function(?int $selected_menu_id = null): void {
    // Verify the selected menu id.
    $menu = $this->verifySelectedMenuId($selected_menu_id);

    if (!$menu) return;

    // Set the menu to inactive.
    $menu->update([
        'is_active' => false,
    ]);
    
    // Dispatch the event.
    $this->dispatch('menu-updated');

    // Close the modal.
    $this->dispatch('close');
});

$delete = action(function(?int $selected_menu_id = null): void {
    // Verify the selected menu id.
    $menu = $this->verifySelectedMenuId($selected_menu_id);

    if (!$menu) return;

    // Delete the menu.
    $menu->delete();

    // Dispatch the event.
    $this->dispatch('menu-updated');

    // Close the modal.
    $this->dispatch('close');
});

$renderList = action(function(array $menu = [], int|string $key = '0', bool $is_first = false, bool $is_last = false): string {
    $children_order = explode('#', $key);
    $is_children = count($children_order) > 1;
    $indent = $is_children ? 'style="padding-left: '.(count($children_order)*20).'px;"' : '';

    $html = <<<HTML
        <tr wire:key="
    HTML . ($menu['id']) . <<<HTML
    " x-show="isShown('$key')" x-bind:class="{ 'bg-primary' : '$key' === selectedKey,
    HTML . (in_array($menu['type'], ['header']) ? '\'bg-light\' : \''.$key.'\' !== selectedKey ' : ' ') . <<<HTML
        }"
    HTML . ($is_children ? ' x-cloak x-transition.scale.origin.top ' : ' ') . <<<HTML
    x-on:click="toggle('$key')" style="cursor: pointer;">
            <td $indent>
    HTML . ($menu['icon_class'] && !in_array($menu['type'], ['header']) ? '<i class="fas ' . $menu['icon_class'] . '"></i> ' : '') . $menu['name'] . (count($menu['children']) > 0 ? '<div class="float-right"><i class="fas" x-bind:class="isExpanded(\''.$key.'\') ? \'fa-caret-down\' : \'fa-caret-right\'"></i></div>' : '') . <<<HTML
    \n        </td>
        </tr>
        <tr wire:key="
    HTML . ($menu['id'] . '_action') . <<<HTML
    " x-show="selectedKey === '$key'">
            <td class="text-center">
                <div class="btn-group">
                    <button wire:click="moveUp(
    HTML . $menu['id'] . <<<HTML
                    )" type="button" class="btn btn-xs btn-primary" 
    HTML . ($is_first ? 'disabled' : '') . <<<HTML
    >
                        <i class="fas fa-arrow-up"></i> 
    HTML . __("Move up") . <<<HTML
                    </button>
                    <button wire:click="moveDown(
    HTML . $menu['id'] . <<<HTML
                    )" type="button" class="btn btn-xs btn-primary" 
    HTML . ($is_last ? 'disabled' : '') . <<<HTML
    >
                        <i class="fas fa-arrow-down"></i> 
    HTML . __("Move down") . <<<HTML
                    </button>
                    <button x-on:click="
    HTML . '$dispatch(\'openModal\', { component: \'settings.menu-management.edit-menu-modal\', arguments: { menu:' . $menu['id'] . <<<HTML
        } })" type="button" class="btn btn-xs btn-warning">
                        <i class="fas fa-edit"></i> 
    HTML . __("Edit") . <<<HTML
                    </button>
    HTML . ($menu['type'] !== 'header' ? <<<HTML
                    <button x-on:click="
    HTML . '$dispatch(\'openModal\', { component: \'settings.menu-management.add-menu-modal\', arguments: { parent_id:' . $menu['id'] . <<<HTML
        } })" type="button" class="btn btn-xs btn-primary">
                        <i class="fas fa-plus"></i> 
    HTML . __("Add Children") . <<<HTML
                    </button>
    HTML : '') . <<<HTML
    HTML . ($menu['is_active'] ? <<<HTML
                    <button type="button" x-on:click="() => {
    HTML . '$dispatch(\'open-modal\', \'deactivate-menu\'); selected_menu_id = ' . $menu['id'] . <<<HTML
    }" class="btn btn-xs btn-danger">
                        <i class="fas fa-times"></i> 
    HTML . __("Deactivate") . <<<HTML
                    </button>
    HTML : <<<HTML
                    <button type="button" x-on:click="() => {
    HTML . '$dispatch(\'open-modal\', \'activate-menu\'); selected_menu_id = ' . $menu['id'] . <<<HTML
    }" class="btn btn-xs btn-success">
                        <i class="fas fa-check"></i> 
    HTML . __("Activate") . <<<HTML
                    </button>
    HTML) . <<<HTML
                    <button type="button" x-on:click="() => {
    HTML . '$dispatch(\'open-modal\', \'delete-menu\'); selected_menu_id = ' . $menu['id'] . <<<HTML
    }" type="button" class="btn btn-xs btn-danger">
                        <i class="fas fa-trash"></i> 
    HTML . __("Delete") . <<<HTML
                    </button>
                </div>
            </td>
        </tr>
    HTML;

    if (isset($menu['children'])) {
        foreach ($menu['children'] as $index => $child) {
            $html .= $this->renderList($child, $key . '#' . last(explode('#', $key)) . '.' . $index, $index === 0, $index === count($menu['children']) - 1);
        }
    }

    return $html;
});

?>

<x-app-layout>
    <x-slot name="header">
        <h1 class="m-0">
            {{ __('Menu Management') }}
        </h1>
    </x-slot>

    @volt('menu-management')
        <div
            x-data="{
                selected_menu_id: null,
                selectedKey : '',
                isShown(key) {
                    keys = key.split('#');
                    keys.pop();

                    return this.selectedKey.split('#', keys.length).join('#') === keys.join('#');
                },
                isExpanded(key) {
                    return this.selectedKey.split('#', key.split('#').length).join('#') === key;
                },
                toggle(key) {
                    this.selectedKey = this.selectedKey === key ? 
                        key.split('#').slice(0, key.split('#').length - 1).join('#') : (
                            this.selectedKey.split('#').includes(key) ? '' : key
                        );
                }
            }"
            x-on:menu-updated.window="$wire.$refresh();"
            class="row">
            <div class="col-12">
                <div class="card card-primary card-outline">
                    <div class="card-body table-responsive p-0" style="max-height: 100vh;">
                        <table class="table table-sm table-head-fixed table-bordered table-hover table-nowrap">
                            <thead>
                                <tr>
                                    <th class="text-center">{{ __('Menu') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($this->menus as $menu)
                                    {!!  $this->renderList($menu, "$loop->index", $loop->first, $loop->last)  !!}
                                @endforeach
                                <tr>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <button wire:click="$dispatch('openModal', { component: 'settings.menu-management.add-menu-modal' })" type="button" class="btn btn-xs btn-primary">
                                                <i class="fas fa-plus"></i> {{ __("Add New Menu") }}
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Activate Menu Modal -->
            <x-modal
                element="form"
                x-on:submit.prevent="$wire.activate(selected_menu_id)"
                name="activate-menu"
                modalSize="sm"
                >
                <x-slot:title>
                    {{ __('Deactivate Menu') }}
                </x-slot:title>

                <p class="mt-1 text-sm">
                    {{ __('Are you sure to activate this menu?') }}
                </p>

                <ul class="text-xs text-danger">
                    @foreach ((array) $errors->get('selected_menu_id') as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>

                <x-slot:footer>
                    <button type="button" class="btn btn-secondary" x-on:click="() => { selected_menu_id = null; $dispatch('close'); }">
                        {{ __('Cancel') }}
                    </button>

                    <button type="submit" class="btn btn-success">
                        {{ __('Activate') }}
                    </button>
                </x-slot:footer>
            </x-modal>

            <!-- Deactivate Menu Modal -->
            <x-modal
                element="form"
                x-on:submit.prevent="$wire.deactivate(selected_menu_id)"
                name="deactivate-menu"
                modalSize="sm"
                >
                <x-slot:title>
                    {{ __('Deactivate Menu') }}
                </x-slot:title>

                <p class="mt-1 text-sm">
                    {{ __('Are you sure to deactivate this menu?') }}
                </p>

                <ul class="text-xs text-danger">
                    @foreach ((array) $errors->get('selected_menu_id') as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>

                <x-slot:footer>
                    <button type="button" class="btn btn-secondary" x-on:click="() => { $wire.set('selected_menu_id', null);$dispatch('close'); }">
                        {{ __('Cancel') }}
                    </button>

                    <button type="submit" class="btn btn-danger">
                        {{ __('Deactivate') }}
                    </button>
                </x-slot:footer>
            </x-modal>

            <!-- Delete Menu Modal -->
            <x-modal
                element="form"
                x-on:submit.prevent="$wire.delete(selected_menu_id)"
                name="delete-menu"
                modalSize="sm"
                >
                <x-slot:title>
                    {{ __('Delete Menu') }}
                </x-slot:title>

                <p class="mt-1 text-sm">
                    {{ __('Are you sure to delete this menu?') }}
                </p>

                <ul class="text-xs text-danger">
                    @foreach ((array) $errors->get('selected_menu_id') as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>

                <x-slot:footer>
                    <button type="button" class="btn btn-secondary" x-on:click="() => { $wire.set('selected_menu_id', null);$dispatch('close'); }">
                        {{ __('Cancel') }}
                    </button>

                    <button type="submit" class="btn btn-danger">
                        {{ __('Delete') }}
                    </button>
                </x-slot:footer>
            </x-modal>
        </div>
    @endvolt
</x-app-layout>
