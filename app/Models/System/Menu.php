<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'parent_id',
        'type',
        'order',
        'link',
        'link_type',
        'icon_class',
        'is_active',
        'use_translation',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'is_active' => 'boolean',
        'use_translation' => 'boolean',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    /**
     * Including relationships to be automatically loaded.
     */
    protected $with = ['children'];

    /**
     * Get the parent menu.
     */
    public function parent() : \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    /**
     * Get the child menus.
     */
    public function children() : \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Menu::class, 'parent_id');
    }

    /**
     * Scope a query to only include active menus.
     */
    public function scopeActive( \Illuminate\Database\Eloquent\Builder $query) : \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('is_active', true);
    }
}
