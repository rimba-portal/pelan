<?php

declare(strict_types=1);

namespace Rimba\Floorplan\Http\UI\Team\Resources\Locations\Pages;

use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Rimba\Floorplan\Http\UI\Team\Resources\Locations\LocationResource;

final class ViewLocation extends ViewRecord
{
    protected static string $resource = LocationResource::class;

    protected function getHeaderActions(): array
    {
        return [EditAction::make()];
    }
}
