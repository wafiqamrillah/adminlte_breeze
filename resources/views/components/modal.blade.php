@props([
    'element' => 'div',
    'show' => false,
    'name' => null,
    'title' => null,
    'footer' => null,
    'size' => 'md'
])

@php
    $modalSize = [
        'sm' => 'modal-sm',
        'md' => '',
        'lg' => 'modal-lg',
        'xl' => 'modal-xl',
    ][$size];
@endphp

<{{ $element }} name="{{ $name }}"
    x-cloak
    x-data="{
        show: {{ $show ? 'true' : 'false' }},
        backdrop: null,
        name: '{{ $name  }}',
        preventClose: false,
        focusables() {
            // All focusable element types...
            let selector = 'a, input:not([type=\'hidden\']), textarea, select, details, [tabindex]:not([tabindex=\'-1\'])'
            return [...$el.querySelectorAll(selector)]
                // All non-disabled elements...
                .filter(el => ! el.hasAttribute('disabled'))
        },
        firstFocusable() { return this.focusables()[0] },
        lastFocusable() { return this.focusables().slice(-1)[0] },
        nextFocusable() { return this.focusables()[this.nextFocusableIndex()] || this.firstFocusable() },
        prevFocusable() { return this.focusables()[this.prevFocusableIndex()] || this.lastFocusable() },
        nextFocusableIndex() { return (this.focusables().indexOf(document.activeElement) + 1) % (this.focusables().length + 1) },
        prevFocusableIndex() { return Math.max(0, this.focusables().indexOf(document.activeElement)) -1 },
        toggleModal(value = false) {
            if (value) {
                document.body.classList.add('modal-open');
                this.backdrop = document.createElement('div');
                document.body.appendChild(this.backdrop);
                this.backdrop.classList.add('modal-backdrop', 'fade');
                setTimeout(() => this.backdrop.classList.add('show'), 150);
                {{ $attributes->has('focusable') ? 'setTimeout(() => this.firstFocusable().focus(), 100)' : '' }}
                $el.style.display = 'block';
            } else {
                document.body.classList.remove('modal-open');
                setTimeout(() => $el.style.display = 'none', 150);
                
                if (this.backdrop) {
                    this.backdrop.classList.remove('show');
                    setTimeout(() => this.backdrop.remove(), 150);
                }
            }
        },
        closeModalOnClickAway() {
            if (this.preventClose) return;

            toggleModal(false);
        }
    }"
    x-init="() => {
        toggleModal(show);
        $watch('show', value => toggleModal(value));
    }"
    x-on:open-modal.window="$event.detail == '{{ $name }}' ? show = true : null;"
    x-on:close.stop="show = false"
    x-on:keydown.escape.window="show = false"
    x-on:keydown.tab.prevent="$event.shiftKey || nextFocusable().focus()"
    x-on:keydown.shift.tab.prevent="prevFocusable().focus()"
    class="modal"
    tabindex="-1"
    x-bind:class="{
        'animate__animated animate__fadeInDown animate__faster': show,
        'animate__animated animate__fadeOutUp': !show
    }"
    x-bind:style="{
        display : show ? 'block' : 'none'
    }"
    aria-hidden="true"
    role="dialog"
    {{ $attributes }}
>
    <div
        x-show="show"
        class="modal-dialog {{ $modalSize }}"
        x-on:click.outside="closeModalOnClickAway()"
        x-trap.noscroll.inert="show"
        aria-modal="true"
    >
        <div class="modal-content">
            <div class="modal-header">
                @isset($title)
                <h5 {{ $title->attributes->merge(['class' => 'modal-title']) }}>
                    {{ $title }}
                </h5>
                @endisset
                <button type="button" x-on:click.prevent="show = false" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{ $slot }}
            </div>
            @isset($footer)
                <div {{ $footer->attributes->merge(['class' => 'modal-footer']) }}>
                    {{ $footer }}
                </div>
            @endisset
        </div>
    </div>
</{{ $element }}>