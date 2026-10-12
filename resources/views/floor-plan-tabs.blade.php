<x-filament::tabs>
    <x-filament::tabs.item
        :active="$this->planFilter === 'all'"
        wire:click="setPlanFilter('all')"
    >
        All
    </x-filament::tabs.item>

    <x-filament::tabs.item
        :active="$this->planFilter === 'with-plans'"
        wire:click="setPlanFilter('with-plans')"
        icon="heroicon-o-map"
    >
        With Plans
    </x-filament::tabs.item>
</x-filament::tabs>