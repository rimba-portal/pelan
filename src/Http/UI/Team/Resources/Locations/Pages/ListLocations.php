<?php

declare(strict_types=1);

namespace Rimba\Floorplan\Http\UI\Team\Resources\Locations\Pages;

use Filament\Resources\Pages\ListRecords;
use Rimba\Floorplan\Http\UI\Team\Resources\Locations\LocationResource;

final class ListLocations extends ListRecords
{
    protected static string $resource = LocationResource::class;
}
