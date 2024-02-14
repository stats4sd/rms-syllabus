<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Models\ResearchComponent;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Concerns\Translatable;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ResearchComponentResource\Pages;
use App\Filament\Resources\ResearchComponentResource\RelationManagers;

class ResearchComponentResource extends Resource
{
    use Translatable;
    
    protected static ?string $model = ResearchComponent::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                
                Forms\Components\Section::make('')
                    ->schema([
                        Forms\Components\Fieldset::make('name_field')
                            ->label('Name')
                            ->columns(3)
                            ->schema([
                                Forms\Components\TextInput::make('name')->hiddenOn(['edit', 'create']),
                                Forms\Components\TextInput::make('name_en')
                                                ->label('English')
                                                ->requiredWithoutAll('name_es, label_fr')
                                                ->validationMessages(['required_without_all' => 'Enter the name in at least one language']),
                                Forms\Components\TextInput::make('name_es')
                                                ->label('Spanish')
                                                ->requiredWithoutAll('name_en, label_fr')
                                                ->validationMessages(['required_without_all' => 'Enter the name in at least one language']),
                                Forms\Components\TextInput::make('name_fr')
                                                ->label('French')
                                                ->requiredWithoutAll('name_es, name_en')
                                                ->validationMessages(['required_without_all' => 'Enter the name in at least one language']),
                            ]),

                        Forms\Components\Fieldset::make('description_field')
                            ->label('Description')
                            ->columns(3)
                            ->schema([
                                Forms\Components\TextInput::make('description')->hiddenOn(['edit', 'create']),
                                Forms\Components\Textarea::make('description_en')
                                                ->label('English'),
                                                // ->requiredWithoutAll('description_es, description_fr')
                                                // ->validationMessages(['required_without_all' => 'Enter the description in at least one language']),
                                Forms\Components\Textarea::make('description_es')
                                                ->label('Spanish'),
                                                // ->requiredWithoutAll('description_en, description_fr')
                                                // ->validationMessages(['required_without_all' => 'Enter the description in at least one language']),
                                Forms\Components\Textarea::make('description_fr')
                                                ->label('French'),
                                                // ->requiredWithoutAll('description_es, description_en')
                                                // ->validationMessages(['required_without_all' => 'Enter the description in at least one language']),
                            ]),
                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->wrap()->sortable(),
                Tables\Columns\TextColumn::make('description')->wrap(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ModulesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListResearchComponents::route('/'),
            'create' => Pages\CreateResearchComponent::route('/create'),
            'edit' => Pages\EditResearchComponent::route('/{record}/edit'),
        ];
    }
}
