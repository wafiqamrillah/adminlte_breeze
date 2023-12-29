@props([
    'menu'
])

@if ($menu['type'] === 'header')
    <li class="nav-header">
        {{ $menu['name'] }}
    </li>
@else
    @php
        $link = $menu['link_type'] === 'route' ? route($menu['link']) : $menu['link'];
        $has_children = isset($menu['children']) && count($menu['children']) > 0;
    @endphp

    <li class="nav-item">
        <a
            href="{{ $has_children ? '#' : $link }}"
            class="nav-link"
            x-bind:class="{ 'active' : '{{ $link }}' === window.location.href }"
            @if(!$has_children) wire:navigate @endif
        >
            <i class="nav-icon {{ $menu['icon_class'] }}"></i>
            <p>
                {{ $menu['use_translation'] ? __($menu['name']) : $menu['name'] }}
                @if ($has_children)
                    <i class="right fas fa-angle-left"></i>
                @endif
            </p>
        </a>

        @if ($has_children)
            <ul class="nav nav-treeview">
                @foreach ($menu['children'] as $child)
                    <x-layouts.menu.sidebar-menu :menu="$child" />
                @endforeach
            </ul>
        @endif
    </li>
@endif