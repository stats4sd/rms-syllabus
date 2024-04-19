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
                    ]),

                Forms\Components\Section::make('Time estimate')
                    ->description('Enter an estimate of the time it takes to complete the moudle in hours e.g., 1 hour, 2.5 hours')
                    ->columns(3)
                    ->icon('heroicon-m-clock')
                    ->schema([
                        Forms\Components\TextInput::make('time_estimate_field')
                            ->label('Time estimate')
                            ->hiddenOn(['edit', 'create']),
                        Forms\Components\TextInput::make('time_estimate_en')
                            ->label('English')
                            ->numeric()
                            ->inputMode('decimal')
                            ->placeholder('Enter a time estimate')
                            ->requiredWithoutAll('time_estimate_es, time_estimate_fr')
                            ->validationMessages(['required_without_all' => 'A trove must be selected in at least one lanugage']),
                        Forms\Components\TextInput::make('time_estimate_es')
                            ->label('Spanish')
                            ->numeric()
                            ->inputMode('decimal')
                            ->placeholder('Enter a time estimate')
                            ->requiredWithoutAll('time_estimate_en, time_estimate_fr')
                            ->validationMessages(['required_without_all' => 'A trove must be selected in at least one lanugage']),
                        Forms\Components\TextInput::make('time_estimate_fr')
                            ->label('French')
                            ->numeric()
                            ->inputMode('decimal')
                            ->placeholder('Enter a time estimate')
                            ->requiredWithoutAll('time_estimate_en, time_estimate_es')
                            ->validationMessages(['required_without_all' => 'A trove must be selected in at least one lanugage']),
                    ]),    

                Forms\Components\Section::make('Competencies')
                    ->description('description here........')
                    ->icon('heroicon-m-tag')
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
                                            ->searchable()
                    ]),
                
                Forms\Components\Section::make('Research Components')
                    ->description('description here........')
                    ->icon('heroicon-m-tag')
                    ->schema([
                        Forms\Components\Select::make('research_component_id')
                            ->label('')
                            ->relationship('researchComponents', 'name')
                            ->placeholder('Select research components')
                            ->multiple()
                            ->preload()
                            ->loadingMessage('Loading research components...')
                            ->noSearchResultsMessage('No research components match your search')
                            ->getOptionLabelFromRecordUsing(fn($record, $livewire) => $record->getTranslation('name', 'en'))
                            ->searchable()
                        ]),

                Forms\Components\Section::make('Cover image')
                    ->icon('heroicon-m-photo')
                    ->schema([
                        Forms\Components\SpatieMediaLibraryFileUpload::make('cover_image')
                                                                        ->collection('module_cover')
                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->wrap()->sortable(),
                Tables\Columns\TextColumn::make('description')->wrap(),
                Tables\Columns\TextColumn::make('time_estimate')->suffix(' hours'),
                Tables\Columns\SpatieMediaLibraryImageColumn::make('cover_image')->collection('module_cover'),
                Tables\Columns\TextColumn::make('sections_count')
                                            ->counts('sections')
                                            ->label('# Sections')
                                            ->sortable(),
                Tables\Columns\TextColumn::make('pathways_count')
                                            ->counts('pathways')
                                            ->label('# Pathways')
                                            ->sortable(),


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
