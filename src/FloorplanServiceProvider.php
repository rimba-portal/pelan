<?php

declare(strict_types=1);

namespace Rimba\Floorplan;

use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Support\Facades\FilamentView;
use Filament\Tables\View\TablesRenderHook;
use Filament\View\PanelsRenderHook;
use Rimba\Base\Services\BitesServiceProvider;
use Rimba\Floorplan\Http\UI\Staff\Pages\FloorPlan;
use Rimba\Floorplan\Http\UI\Team\Resources\Locations\LocationResource;

class FloorplanServiceProvider extends BitesServiceProvider
{
    protected string $viewsPath = __DIR__.'/../resources/views';

    protected string $iconsPath = __DIR__.'/../resources/svg';

    protected function bootPackage(): void
    {
        $this->loadMigrationsFrom(
            __DIR__.'/../database/migrations'
        );

        $this->registerFloorPlanTableHooks();
        $this->registerFloorPlanShortcut();
    }

    protected function registerPackage(): void
    {
        //
    }

    /*
    |--------------------------------------------------------------------------
    | Floor Plan Table Hooks
    |--------------------------------------------------------------------------
    */
    protected function registerFloorPlanTableHooks(): void
    {
        /*
         * Floor Plan filters: All | With Plans
         */
        FilamentView::registerRenderHook(
            TablesRenderHook::TOOLBAR_START,
            fn (): string => view('bites::floor-plan-tabs')->render(),
            scopes: [FloorPlan::class],
        );
        /*
         * Location management shortcut.
         *
         * Only shown when the user may create or edit locations. All record maintenance is delegated to the Team panel.
         */
        FilamentView::registerRenderHook(
            TablesRenderHook::TOOLBAR_SEARCH_AFTER,
            fn (): string => $this->renderManageLocationsAction(),
            scopes: [FloorPlan::class],
        );
    }

    protected function renderManageLocationsAction(): string
    {
        auth()->user();
        if (! FloorPlan::canManage()) {
            return '';
        }

        return Action::make('manageLocations')
            ->label('Edit')->icon('heroicon-o-pencil-square')->badge()
            ->url(LocationResource::getUrl('index', panel: 'team'))
            ->toHtml();
    }

    /*
    |--------------------------------------------------------------------------
    | Global Floor Plan Shortcut
    |--------------------------------------------------------------------------
    */
    protected function registerFloorPlanShortcut(): void
    {
        FilamentView::registerRenderHook(
            PanelsRenderHook::GLOBAL_SEARCH_BEFORE,
            fn (): string => $this->renderFloorPlanShortcut(),
        );
    }

    protected function renderFloorPlanShortcut(): string
    {
        /*
         * The lobby intentionally has no Floor Plan shortcut.
         */
        if (Filament::getCurrentPanel()?->getId() === 'lobby') {
            return '';
        }

        /*
         * Do not expose the shortcut when the user cannot
         * access the FloorPlan page.
         */
        if (! FloorPlan::canAccess()) {
            return '';
        }

        return Action::make('floorPlan')
            ->label('Floor Plan')->icon('bites-location')->iconButton()->badge()
            ->url(route('filament.staff.pages.floor-plan'))
            ->toHtml();
    }
}
