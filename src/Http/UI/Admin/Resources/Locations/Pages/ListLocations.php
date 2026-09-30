<?php

declare(strict_types=1);

namespace Rimba\Floorplan\Http\UI\Admin\Resources\Locations\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Rimba\Floorplan\Http\UI\Admin\Resources\Locations\LocationResource;

class ListLocations extends ListRecords
{
    protected static string $resource = LocationResource::class;

    protected static ?string $title = 'Facilities & Locations';

    protected ?string $subheading = 'Manage workspace sites, parent structures, building codes, and properties.';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
