<?php

declare(strict_types=1);

namespace Rimba\Floorplan;

use Filament\Actions\Action;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Rimba\Base\BitesServiceProvider;

class FloorplanServiceProvider extends BitesServiceProvider
{
    protected string $configFile = __DIR__.'/../config/bites.php';

    protected string $viewsPath = __DIR__.'/../resources/views';

    protected string $iconsPath = __DIR__.'/../resources/svg';

    protected function bootPackage(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        FilamentView::registerRenderHook(
            PanelsRenderHook::GLOBAL_SEARCH_BEFORE,
            fn (): string => Action::make('FloorPlan')
                ->label('Floor Plan')
                ->iconButton()
                ->badge()
                ->icon('bites-location')
                ->url(route('filament.staff.pages.floor-plan'))
                ->toHtml(),
        );

    }

    protected function registerPackage(): void
    {
        //
    }
}
