<?php

namespace App\Filament\Resources\ResearchComponentResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Module;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ModuleResource;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\RelationManagers\Concerns\Translatable;

class ModulesRelationManager extends RelationManager
{
    use Translatable;

    protected static string $relationship = 'modules';

    public function form(Form $form): Form
    {
        return $form
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

                Forms\Components\Section::make('Time estimate')
                    ->description('Enter an estimate of the time it takes to complete the moudle in hours e.g., 1 hour, 2.5 hours')
                    ->columns(3)
                    ->schema([
                        Forms\Components\TextInput::make('time_estimate')
                            ->label('')
                            ->numeric()
                            ->inputMode('decimal')
                            ->placeholder('Enter a time estimate')
                            ->required()
                    ]),

                Forms\Components\Section::make('Cover image')
                    ->schema([
                        Forms\Components\SpatieMediaLibraryFileUpload::make('cover_image')
                                                                        ->collection('module_cover')
                    ]),

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')->wrap()->sortable(),
                Tables\Columns\TextColumn::make('description')->wrap(),
                Tables\Columns\TextColumn::make('time_estimate')->suffix(' hours'),
                Tables\Columns\SpatieMediaLibraryImageColumn::make('cover_image')->collection('module_cover'),
                Tables\Columns\TextColumn::make('sections_count')
                                            ->counts('sections')
                                            ->label('# Sections')
                                            ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\LocaleSwitcher::make(),
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['name'] = 'names added after creation';
                        $data['description'] = 'descriptions added after creation';
                        return $data;
                    })
                    ->after(function (Module $record, array $data) {
                        $record->name = '';
                        $record->description = '';
                
                        if(!is_null($data['name_en'])){
                            $record->setTranslation('name', 'en', $data['name_en']);
                        }
                
                        if(!is_null($data['name_es'])){
                            $record->setTranslation('name', 'es', $data['name_es']);
                        }
                
                        if(!is_null($data['name_fr'])){
                            $record->setTranslation('name', 'fr', $data['name_fr']);
                        }
                
                        if(!is_null($data['description_en'])){
                            $record->setTranslation('description', 'en', $data['description_en']);
                        }
                
                        if(!is_null($data['description_es'])){
                            $record->setTranslation('description', 'es', $data['description_es']);
                        }
                
                        if(!is_null($data['description_fr'])){
                            $record->setTranslation('description', 'fr', $data['description_fr']);
                        }
                
                        $record->save();
                    })
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            -> recordUrl(fn(Module $record) => ModuleResource::getUrl('edit', ['record' => $record->slug])
        );
    }
}
