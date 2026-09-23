<?php

declare(strict_types=1);

namespace Rimba\Floorplan\Http\UI\Team\Resources\Locations\Pages;

use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;
use Rimba\Floorplan\Http\UI\Team\Resources\Locations\LocationResource;

final class ListLocations extends ListRecords
{
    protected static string $resource = LocationResource::class;

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All'),

            'with_floorplan' => Tab::make('With Floor Plan')
                ->modifyQueryUsing(
                    fn (Builder $query) => $query
                        ->whereRaw('json_valid(attributes) = 1')
                        ->whereRaw("json_extract(attributes, '$.floorplan_img') IS NOT NULL")
                        ->whereRaw("json_extract(attributes, '$.floorplan_img') != ''")
                ),

            'without_floorplan' => Tab::make('Without Floor Plan')
                ->modifyQueryUsing(
                    fn (Builder $query) => $query
                        ->where(function (Builder $query): void {
                            $query
                                ->whereNull('attributes')
                                ->orWhereRaw('json_valid(attributes) = 0')
                                ->orWhereRaw("json_extract(attributes, '$.floorplan_img') IS NULL")
                                ->orWhereRaw("json_extract(attributes, '$.floorplan_img') = ''");
                        })
                ),
        ];
    }
}
