<?php

namespace App\Livewire\Settings\MenuManagement;

use Livewire\Attributes\{ Validate, Locked };
use LivewireUI\Modal\ModalComponent;

class EditMenuModal extends ModalComponent
{
    public \App\Livewire\Forms\System\Menu\MenuForm $form;

    public ?\App\Models\System\Menu $parent_menu = null;

    protected static array $maxWidths = [
        'lg' => 'modal-lg',
    ];

    public static function modalMaxWidth(): string
    {
        return 'lg';
    }

    public function mount(\App\Models\System\Menu $menu) : void
    {
        collect($menu->toArray())->each(function($item, $key) {
            $this->form->{$key} = $item;
        });

        $this->parent_menu = $menu->parent;
    }
    
    public function submit() : void
    {
        $this->form->update();

        $this->dispatch('menu-updated');

        $this->closeModal();
    }

    public function render() : \Illuminate\View\View
    {
        return view('livewire.settings.menu-management.edit-menu-modal');
    }
}
