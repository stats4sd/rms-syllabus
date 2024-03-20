<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Pathway;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Repeater;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\TextEntry;
use App\Filament\Resources\PathwayResource\Pages;
use Filament\Infolists\Components\RepeatableEntry;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PathwayResource\RelationManagers;

class PathwayResource extends Resource
{
    protected static ?string $model = Pathway::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Fieldset::make('name_field')
                    ->label('Name')
                    ->columns(3)
                    ->schema([
                        Forms\Components\TextInput::make('name')->hiddenOn(['edit', 'create']),
                        Forms\Components\Textarea::make('name_en')
                                        ->label('English')
                                        ->rows(2)
                                        ->requiredWithoutAll('name_es, label_fr')
                                        ->validationMessages(['required_without_all' => 'Enter the name in at least one language']),
                        Forms\Components\Textarea::make('name_es')
                                        ->label('Spanish')
                                        ->rows(2)
                                        ->requiredWithoutAll('name_en, label_fr')
                                        ->validationMessages(['required_without_all' => 'Enter the name in at least one language']),
                        Forms\Components\Textarea::make('name_fr')
                                        ->label('French')
                                        ->rows(2)
                                        ->requiredWithoutAll('name_es, name_en')
                                        ->validationMessages(['required_without_all' => 'Enter the name in at least one language']),
                    ]),

                Forms\Components\Section::make('Modules')
                    ->schema([
                        Repeater::make('modules')
                                ->label('')
                                ->defaultItems(1)
                                ->addActionLabel('Add another module')
                                ->columns(2)
                                ->schema([
                                    Forms\Components\Select::make('modules')
                                        ->relationship('modules', 'name')
                                        ->label('')
                                        ->distinct()
                                        ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                        ->preload()
                                        ->placeholder('Select a module')
                                        ->loadingMessage('Loading modules...')
                                        ->searchable()
                                        ->noSearchResultsMessage('No modules match your search')
                                        ->getOptionLabelFromRecordUsing(fn($record, $livewire) => $record->getTranslation('name', 'en')),
                                ])
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('description'),
                Tables\Columns\TextColumn::make('modules_count')
                                ->counts('modules')
                                ->label('# Modules')
                                ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPathways::route('/'),
            'create' => Pages\CreatePathway::route('/create'),
            'view' => Pages\ViewPathway::route('/{record}'),
            'edit' => Pages\EditPathway::route('/{record}/edit'),
        ];
    }
}
