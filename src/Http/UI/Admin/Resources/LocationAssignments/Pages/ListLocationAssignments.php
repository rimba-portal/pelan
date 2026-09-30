<?php

namespace Rimba\Floorplan\Http\UI\Admin\Resources\LocationAssignments\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLocationAssignments extends ListRecords
{
    protected static string $resource = \Rimba\Floorplan\Http\UI\Admin\Resources\LocationAssignments\LocationAssignmentResource::class;

    protected static ?string $title = 'Location Allocation Logs';

    protected ?string $subheading = 'Track time-boxed personnel space claims and workspace setups.';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
