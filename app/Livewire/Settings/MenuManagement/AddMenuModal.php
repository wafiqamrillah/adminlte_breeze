<?php

namespace App\Livewire\Settings\MenuManagement;

use Livewire\Attributes\{ Validate, Locked };
use LivewireUI\Modal\ModalComponent;

class AddMenuModal extends ModalComponent
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

    public function mount(?int $parent_id = null) : void
    {
        $this->parent_menu = $parent_id ? \App\Models\System\Menu::select(['id', 'name'])->find($parent_id) : null;
        $this->form->parent_id = $parent_id;
    }

    public function submit() : void
    {
        $this->form->store();

        $this->dispatch('menu-updated');

        $this->closeModal();
    }

    public function render() : \Illuminate\View\View
    {
        return view('livewire.settings.menu-management.add-menu-modal');
    }
}
