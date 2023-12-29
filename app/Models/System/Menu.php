<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{ Model, Builder };

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
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('order', 'asc');
        });
    }

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
     * Scope a query to include children menus.
     */
    public function scopeWithChildren(Builder $query) : Builder
    {
        return $query->with(['children' => function ($query) {
            $query->withChildren();
        }]);
    }

    /**
     * Scope a query to include active children menus.
     */
    public function scopeWithActiveChildren(Builder $query) : Builder
    {
        return $query->with(['children' => function ($query) {
            $query->active()->withActiveChildren();
        }]);
    }

    /**
     * Scope a query to only include active menus.
     */
    public function scopeActive(Builder $query) : Builder
    {
        return $query->where('is_active', true);
    }
}
