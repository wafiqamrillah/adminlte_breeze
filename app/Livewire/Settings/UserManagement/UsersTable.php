<?php

namespace App\Livewire\Settings\UserManagement;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\{
    Column,
    BooleanColumn
};

class UsersTable extends DataTableComponent
{
    public array $bulkActions = [
        'export' => 'Export'
    ];

    public function builder() : \Illuminate\Database\Eloquent\Builder
    {
        return \App\Models\User::query();
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setDefaultSort('name', 'asc')
            ->setSortingEnabled()
            ->setPaginationEnabled()
            ->setPaginationVisibilityEnabled()
            ->setPerPageVisibilityEnabled()
            ->setLoadingPlaceholderEnabled()
            ->setLoadingPlaceHolderIconAttributes([
                'class' => 'fas fa-spinner fa-spin fa-2x fa-fw',
                'default' => false,
            ]);
    }

    public function columns(): array
    {
        return [
            Column::make("Name", "name")
                ->sortable()
                ->searchable(),
            Column::make("Email", "email")
                ->sortable(),
            Column::make("Created at", "created_at")
                ->sortable(),
            Column::make("Updated at", "updated_at")
                ->sortable(),
        ];
    }
}
