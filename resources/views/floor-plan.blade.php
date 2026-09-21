<x-filament-panels::page>
    <div class="space-y-6">
        <x-filament::section>
            <x-slot name="heading">{{ $this->selectedLocation?->name ?? 'Floor Plan Viewer' }}</x-slot>
            <x-slot name="description">Use the mouse wheel or controls to zoom. Drag the floor plan to pan.</x-slot>
            <div
                x-data="{
                    scale: 1,
                    x: 0,
                    y: 0,
                    dragging: false,
                    px: 0,
                    py: 0,
                    zoom(delta) {
                        this.scale = Math.min(5, Math.max(0.25, this.scale + delta));
                    },
                    reset() {
                        this.scale = 1;
                        this.x = 0;
                        this.y = 0;
                    },
                    start(e) {
                        this.dragging = true;
                        this.px = e.clientX - this.x;
                        this.py = e.clientY - this.y;
                    },
                    move(e) {
                        if (this.dragging) {
                            this.x = e.clientX - this.px;
                            this.y = e.clientY - this.py;
                        }
                    },
                }"
                x-on:floorplan-location-changed.window="reset()"
                class="space-y-3"
            >
                <div class="flex gap-2">
                    <x-filament::button size="sm" color="gray" x-on:click="zoom(0.2)">Zoom In</x-filament::button>
                    <x-filament::button size="sm" color="gray" x-on:click="zoom(-0.2)">Zoom Out</x-filament::button>
                    <x-filament::button size="sm" color="gray" x-on:click="reset()">Reset</x-filament::button>
                </div>
                <div
                    class="h-[36rem] overflow-hidden rounded-xl border border-gray-200 bg-gray-50 dark:border-white/10 dark:bg-gray-900"
                    x-on:wheel.prevent="zoom($event.deltaY < 0 ? 0.1 : -0.1)"
                    x-on:mousedown="start($event)"
                    x-on:mousemove.window="move($event)"
                    x-on:mouseup.window="dragging = false"
                    x-on:mouseleave="dragging = false"
                >
                    @if ($this->floorplanUrl)
                        <img
                            src="{{ $this->floorplanUrl }}"
                            alt="{{ $this->selectedLocation?->name }} floor plan"
                            draggable="false"
                            class="h-full w-full object-contain select-none"
                            x-bind:style="
                                `transform: translate(${x}px, ${y}px) scale(${scale}); transform-origin: center; cursor: ${dragging ? 'grabbing' : 'grab'};`
                            "
                        />
                    @else
                        <div class="flex h-full items-center justify-center text-sm text-gray-500">
                            Select a location with an uploaded SVG floor plan.
                        </div>
                    @endif
                </div>
            </div>
        </x-filament::section>
        <x-filament::section>
            <x-slot name="heading">Locations</x-slot>
            {{ $this->table }}
        </x-filament::section>
    </div>
</x-filament-panels::page>
