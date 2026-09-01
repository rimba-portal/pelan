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
