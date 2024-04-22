<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Pathway;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Repeater;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Concerns\Translatable;
use App\Filament\Resources\PathwayResource\Pages;
use Filament\Infolists\Components\RepeatableEntry;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PathwayResource\RelationManagers;

class PathwayResource extends Resource
{
    use Translatable;
    
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
                                        ->regex('/^(?=.*[^\W_])[^\n]+$/')
                                        ->requiredWithoutAll('name_es, name_fr')
                                        ->validationMessages(['regex' => 'Name cannot only contain special characters',
                                                              'required_without_all' => 'Enter the name in at least one language']),
                        Forms\Components\Textarea::make('name_es')
                                        ->label('Spanish')
                                        ->rows(2)
                                        ->regex('/^(?=.*[^\W_])[^\n]+$/')
                                        ->requiredWithoutAll('name_en, name_fr')
                                        ->validationMessages(['regex' => 'Name cannot only contain special characters',
                                                              'required_without_all' => 'Enter the name in at least one language']),
                        Forms\Components\Textarea::make('name_fr')
                                        ->label('French')
                                        ->rows(2)
                                        ->regex('/^(?=.*[^\W_])[^\n]+$/')
                                        ->requiredWithoutAll('name_es, name_en')
                                        ->validationMessages(['regex' => 'Name cannot only contain special characters', 
                                                              'required_without_all' => 'Enter the name in at least one language']),
                    ]),

                Forms\Components\Fieldset::make('description_field')
                    ->label('Description')
                    ->columns(3)
                    ->schema([
                        Forms\Components\TextInput::make('description')->hiddenOn(['edit', 'create']),
                        Forms\Components\Textarea::make('description_en')
                                        ->label('English')
                                        ->rows(8),
                                        // ->requiredWithoutAll('description_es, description_fr')
                                        // ->validationMessages(['required_without_all' => 'Enter the description in at least one language']),
                        Forms\Components\Textarea::make('description_es')
                                        ->label('Spanish')
                                        ->rows(8),
                                        // ->requiredWithoutAll('description_en, description_fr')
                                        // ->validationMessages(['required_without_all' => 'Enter the description in at least one language']),
                        Forms\Components\Textarea::make('description_fr')
                                        ->label('French')
                                        ->rows(8),
                                        // ->requiredWithoutAll('description_es, description_en')
                                        // ->validationMessages(['required_without_all' => 'Enter the description in at least one language']),
                    ]),

                Forms\Components\Section::make('Modules')
                    ->schema([
                        Repeater::make('modulePathways')
                                ->relationship()
                                ->label('')
                                ->defaultItems(0)
                                ->addActionLabel('Add a module')
                                ->columns(2)
                                ->schema([
                                    Forms\Components\Select::make('module_id')
                                        ->relationship('module', 'name')
                                        ->label('')
                                        ->distinct()
                                        ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                        ->preload()
                                        ->placeholder('Select a module')
                                        ->loadingMessage('Loading modules...')
                                        ->searchable()
                                        ->noSearchResultsMessage('No modules match your search')
                                        ->getOptionLabelFromRecordUsing(fn($record, $livewire) => $record->getTranslation('name', 'en'))
                                ])->orderColumn('module_order')
                                ]),

                Forms\Components\Hidden::make('creator_id')->default(Auth::user()->id),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('modules_count')
                                ->counts('modules')
                                ->label('# Modules')
                                ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                                ->label('Created by')
                                ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\ViewAction::make(),
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
            'edit' => Pages\EditPathway::route('/{record}/edit'),
        ];
    }
}
