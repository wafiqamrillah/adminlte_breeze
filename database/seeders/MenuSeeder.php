<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\System\Menu;

class MenuSeeder extends Seeder
{
    /**
     * Update or create menu.
     * If the menu already exists, it will be updated instead of duplicated.
     * And, if the menu has children, it will call this method recursively.
     */
    private function updateOrCreateMenu($menu, $parent = null): void
    {
        // Define children
        $children = isset($menu['children']) ? collect($menu['children']) : collect([]);
        unset($menu['children']);

        // Insert or update menu
        $data = Menu::updateOrCreate(
            [
                'name' => $menu['name']
            ],
            $menu->toArray()
        );

        // Set parent
        if ($parent) {
            $data->parent()->associate($parent);
            $data->save();
        }

        // Insert or update children
        if ($children->count() > 0) {
            $children->each(function ($child) use ($menu) {
                $this->updateOrCreateMenu($child, $data);
            });
        }
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Menu list
        $menus = collect([
            // Dashboard
            collect([
                'name' => 'Dashboard',
                'parent_id' => null,
                'type' => 'menu',
                'order' => 1,
                'link' => 'dashboard',
                'link_type' => 'route',
                'icon_class' => 'fas fa-tachometer-alt',
                'is_active' => true,
                'use_translation' => true
            ])
        ]);

        // Insert menus, but make sure if the menu already exists, it will be updated instead of duplicated
        \DB::transaction(function () use ($menus) {
            $menus->each(function ($menu) {
                $this->updateOrCreateMenu($menu);
            });
        });
    }
}
