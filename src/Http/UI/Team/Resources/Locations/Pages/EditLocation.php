<?php

declare(strict_types=1);

namespace Rimba\Floorplan\Http\UI\Team\Resources\Locations\Pages;

use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Rimba\Floorplan\Http\UI\Team\Resources\Locations\LocationResource;

final class EditLocation extends EditRecord
{
    protected static string $resource = LocationResource::class;

    protected function getHeaderActions(): array
    {
        return [ViewAction::make(), DeleteAction::make()];
    }
}
