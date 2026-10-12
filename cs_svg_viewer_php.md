# php
```php
<?php

declare(strict_types=1);

namespace Rimba\Floorplan\Http\UI\Staff\Pages;

use BackedEnum;
use Filament\Pages\Page;
use Illuminate\Support\Facades\File;

class SvgViewer extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-map';

    protected static ?string $title = 'Interactive Floorplan';

    protected string $view = 'bites::svg-viewer';

    public string $floorplanSvg = '';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public function mount(): void
    {
        $svgPath = public_path('storage/floorplan/floorplan.svg');
        // dd($svgPath);
        if (! File::exists($svgPath)) {
            $this->floorplanSvg = '
                <svg viewBox="0 0 300 300">
                    <rect width="500" height="300" fill="#3b82f6"/>
                    <text x="250" y="150" text-anchor="middle" fill="white">
                        Floorplan Not Found
                    </text>
                </svg>
            ';

            return;
        }

        $this->floorplanSvg = File::get($svgPath);
    }
}
```


# blade 
bites::svg-viewer
```php
<x-filament::page>
    <div class="space-y-4">
        {{-- <div class="flex items-center justify-between p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm"> --}}
        <div>
            <div>
                <h3 class="text-lg font-semibold">Interactive Floorplan</h3>

                <p class="text-sm text-gray-500">Scroll to zoom • Drag to pan</p>
            </div>

            <div class="flex gap-2">
                <x-filament::button color="gray" x-on:click="$dispatch('floorplan-zoom-in')"> + </x-filament::button>

                <x-filament::button color="gray" x-on:click="$dispatch('floorplan-zoom-out')"> - </x-filament::button>

                <x-filament::button color="gray" x-on:click="$dispatch('floorplan-reset')"> Reset </x-filament::button>
            </div>
        </div>

        <div
            x-data="{
                panZoom: null,

                init() {
                    const boot = () => {
                        const svg = this.$refs.viewport.querySelector('svg');

                        if (! svg) {
                            console.error('SVG not found');
                            return;
                        }

                        svg.removeAttribute('width');
                        svg.removeAttribute('height');

                        svg.style.width = '100%';
                        svg.style.height = '100%';

                        this.panZoom = svgPanZoom(svg, {
                            zoomEnabled: true,
                            panEnabled: true,
                            fit: true,
                            center: true,
                            minZoom: 0.1,
                            maxZoom: 50,
                            zoomScaleSensitivity: 0.2,
                        });
                    };

                    if (window.svgPanZoom) {
                        boot();
                        return;
                    }

                    const script = document.createElement('script');

                    script.src = 'https://cdn.jsdelivr.net/npm/svg-pan-zoom@3.6.1/dist/svg-pan-zoom.min.js';

                    script.onload = boot;

                    document.head.appendChild(script);
                },
            }"
            x-init="init()"
            x-on:floorplan-zoom-in.window="
                if (panZoom) {
                    panZoom.zoomIn();
                }
            "
            x-on:floorplan-zoom-out.window="
                if (panZoom) {
                    panZoom.zoomOut();
                }
            "
            x-on:floorplan-reset.window="
                if (panZoom) {
                    panZoom.resetZoom();
                    panZoom.center();
                    panZoom.fit();
                }
            "
            class="overflow-hidden rounded-xl bg-white shadow-sm dark:bg-gray-800"
        >
            <div
                x-ref="viewport"
                class="flex h-full min-h-[75vh] w-full cursor-move items-center justify-center select-none"
            >
                {!! $floorplanSvg !!}
            </div>
        </div>
    </div>
</x-filament::page>
```