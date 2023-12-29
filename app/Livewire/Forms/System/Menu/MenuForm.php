<?php

namespace App\Livewire\Forms\System\Menu;

use Livewire\Form;

class MenuForm extends Form
{
    #[Locked]
    public ?int $id = null;
    public ?int $parent_id = null;

    public string $name = '';
    public string $type = 'menu';
    public string $link = '#';
    public string $link_type = 'url';
    public string $icon_class = 'fas fa-circle';
    public int $order = 0;

    public bool $is_active = true, $use_translation = true;

    public function store() : void {
        $form = $this->validate(
            (new \App\Http\Requests\System\Menu\StoreMenuRequest)->rules()
        );

        \DB::transaction(function() use($form) {
            $menu = new \App\Models\System\Menu($form);
            $menu->order = \App\Models\System\Menu::where('parent_id', $this->parent_id)->count() + 1;

            $menu->save();
        });

        $this->reset();
    }

    public function update() : void {
        $form = $this->validate(
            (new \App\Http\Requests\System\Menu\UpdateMenuRequest)->rules()
        );

        \DB::transaction(function() use($form) {
            $menu = \App\Models\System\Menu::find($this->id);

            $menu->update($form);
        });

        $this->reset();
    }
}
