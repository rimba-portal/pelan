<?php

declare(strict_types=1);

namespace Rimba\Floorplan\Http\UI\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Rimba\Floorplan\Models\Location;

final class LocationAssignmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'locationAssignments';

    protected static ?string $title = 'Location Assignments';

    protected static ?string $recordTitleAttribute = 'id';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([Section::make('Assignment')
                ->schema([
                    Select::make('location_id')

                        ->label('Location')
                        ->options(fn (): array => Location::query()
                            ->orderBy('name')
                            ->get()
                            ->mapWithKeys(fn (Location $location): array => [$location
                                ->getKey() => trim(($location
                                ->code ? $location
                                ->code.' - ' : '').$location
                                ->name)])
                            ->all())
                        ->required()
                        ->searchable()
                        ->preload(),
                    Select::make('type')
                        ->options(['primary' => 'Primary', 'secondary' => 'Secondary', 'temporary' => 'Temporary'])
                        ->required()
                        ->default('primary'),
                    DatePicker::make('start_date'),
                    DatePicker::make('end_date')
                        ->afterOrEqual('start_date'),
                    KeyValue::make('attributes')
                        ->columnSpanFull(),
                ])
                ->columns(2)]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('start_date', 'desc')
            ->columns([
                TextColumn::make('location.code')
                    ->label('Code')
                    ->searchable(),
                TextColumn::make('location.name')
                    ->label('Location')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('location.type')
                    ->label('Location Type')
                    ->badge(),
                TextColumn::make('type')
                    ->badge(),
                TextColumn::make('start_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('end_date')
                    ->date()
                    ->placeholder('Open')
                    ->sortable(),
            ])
            ->filters([SelectFilter::make('type')
                ->options(['primary' => 'Primary', 'secondary' => 'Secondary', 'temporary' => 'Temporary'])])
            ->headerActions([CreateAction::make()])
            ->recordActions([EditAction::make(), DeleteAction::make()]);
    }
}
