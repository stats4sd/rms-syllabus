<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Module;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Concerns\Translatable;
use App\Filament\Resources\ModuleResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ModuleResource\RelationManagers;

class ModuleResource extends Resource
{
    use Translatable;
    
    protected static ?string $model = Module::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Section::make('')
                    ->schema([
                        Forms\Components\Select::make('research_component_id')
                            ->relationship('researchComponent', 'name')
                            ->required()
                            ->preload()
                            ->loadingMessage('Loading research components...')
                            ->searchable()
                            ->noSearchResultsMessage('No research components match your search')
                            ->placeholder('Select a research component')
                            ->getOptionLabelFromRecordUsing(fn($record, $livewire) => $record->getTranslation('name', 'en')),
                        ]),

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

                Forms\Components\Section::make('Competencies')
                    ->description('description here........')
                    ->schema([
                        Forms\Components\Select::make('competencies')
                                            ->label('')
                                            ->relationship('competencies', 'name')
                                            ->placeholder('Select competencies')
                                            ->multiple()
                                            ->preload()
                                            ->loadingMessage('Loading competencies...')
                                            ->noSearchResultsMessage('No competencies match your search')
                                            ->getOptionLabelFromRecordUsing(fn($record, $livewire) => $record->getTranslation('name', 'en'))
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('researchComponent.name')->wrap()->sortable(),
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
            RelationManagers\SectionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListModules::route('/'),
            'create' => Pages\CreateModule::route('/create'),
            'edit' => Pages\EditModule::route('/{record}/edit'),
        ];
    }
}
