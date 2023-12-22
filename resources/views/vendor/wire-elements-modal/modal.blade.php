<div id="livewire-ui-modal">
    <div
        x-data="LivewireUIModal($el)"
        x-on:close.stop="setShowPropertyTo(false)"
        x-on:keydown.escape.window="closeModalOnEscape()"
        class="modal"
        x-bind:class="{
            'animate__animated animate__fadeInDown animate__faster': show,
            'animate__animated animate__fadeOutUp': !show
        }"
        x-bind:style="{
            display : show ? 'block' : 'none'
        }"
    >
        <div
            x-show="show && showActiveComponent"
            x-bind:class="modalWidth"
            x-on:click.outside="closeModalOnClickAway()"
            class="modal-dialog"
            id="modal-container"
            x-trap.noscroll.inert="show && showActiveComponent"
            aria-modal="true"
        >
            @forelse($components as $id => $component)
                <div class="modal-content" x-show.immediate="activeComponent == '{{ $id }}'" x-ref="{{ $id }}" wire:key="{{ $id }}">
                    @livewire($component['name'], $component['arguments'], key($id))
                </div>
            @empty
            @endforelse
        </div>
    </div>
</div>
