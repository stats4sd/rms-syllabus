<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Competency;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Models\CompetencyCategory;
use App\Filament\Clusters\Competencies;
use Filament\Forms\Components\Component;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Actions\Action;
use Filament\Resources\Concerns\Translatable;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CompetencyCategoryResource\Pages;
use App\Filament\Resources\CompetencyCategoryResource\RelationManagers;

class CompetencyCategoryResource extends Resource
{
    use Translatable;
    
    protected static ?string $model = CompetencyCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Categories';

    protected static ?string $cluster = Competencies::class;

    public static function form(Form $form): Form
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
                        Forms\Components\TextArea::make('description_en')
                                        ->label('English'),
                                        // ->requiredWithoutAll('description_es, description_fr')
                                        // ->validationMessages(['required_without_all' => 'Enter the description in at least one language']),
                        Forms\Components\TextArea::make('description_es')
                                        ->label('Spanish'),
                                        // ->requiredWithoutAll('description_en, description_fr')
                                        // ->validationMessages(['required_without_all' => 'Enter the description in at least one language']),
                        Forms\Components\TextArea::make('description_fr')
                                        ->label('French'),
                                        // ->requiredWithoutAll('description_es, description_en')
                                        // ->validationMessages(['required_without_all' => 'Enter the description in at least one language']),
                    ]),

                Forms\Components\Select::make('competencies')
                    ->relationship('competencies', 'name')
                    ->multiple()
                    ->preload()
                    ->placeholder('Select competencies')
                    ->loadingMessage('Loading competencies...')
                    ->searchable()
                    ->noSearchResultsMessage('No competencies match your search')
                    ->getOptionLabelFromRecordUsing(fn($record, $livewire) => $record->getTranslation('name', 'en'))
                    ->createOptionForm([
                        Forms\Components\Fieldset::make('name_field')
                            ->label('Name')
                            ->columns(3)
                            ->schema([
                                Forms\Components\TextInput::make('name')->hidden(),
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
                                Forms\Components\TextInput::make('description')->hidden(),
                                Forms\Components\TextArea::make('description_en')
                                                ->label('English'),
                                                // ->requiredWithoutAll('description_es, description_fr')
                                                // ->validationMessages(['required_without_all' => 'Enter the description in at least one language']),
                                Forms\Components\TextArea::make('description_es')
                                                ->label('Spanish'),
                                                // ->requiredWithoutAll('description_en, description_fr')
                                                // ->validationMessages(['required_without_all' => 'Enter the description in at least one language']),
                                Forms\Components\TextArea::make('description_fr')
                                                ->label('French'),
                                                // ->requiredWithoutAll('description_es, description_en')
                                                // ->validationMessages(['required_without_all' => 'Enter the description in at least one language']),
                            ]),
                    ])
                    ->createOptionAction(
                        fn (Action $action) => $action
                            ->modalWidth('3xl')
                            ->mutateFormDataUsing(function (array $data): array {
                                $data['name'] = 'names added after creation';
                                $data['description'] = 'descriptions added after creation';
                                return $data;
                            })
                            ->after(function (array $data, Component $component, Action $action) {

                                $competencyIds = $component->getState();
                                $newCompetencyId = last($competencyIds);
                                $competency = Competency::find($newCompetencyId);

                                $competency->name = '';
                                $competency->description = '';
                        
                                if(!is_null($data['name_en'])){
                                    $competency->setTranslation('name', 'en', $data['name_en']);
                                }
                        
                                if(!is_null($data['name_es'])){
                                    $competency->setTranslation('name', 'es', $data['name_es']);
                                }
                        
                                if(!is_null($data['name_fr'])){
                                    $competency->setTranslation('name', 'fr', $data['name_fr']);
                                }
                        
                                if(!is_null($data['description_en'])){
                                    $competency->setTranslation('description', 'en', $data['description_en']);
                                }
                        
                                if(!is_null($data['description_es'])){
                                    $competency->setTranslation('description', 'es', $data['description_es']);
                                }
                        
                                if(!is_null($data['description_fr'])){
                                    $competency->setTranslation('description', 'fr', $data['description_fr']);
                                }
                        
                                $competency->save();
                            })
                    )
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->wrap()->sortable(),
                Tables\Columns\TextColumn::make('description')->wrap(),
                Tables\Columns\TextColumn::make('competencies.name')->listWithLineBreaks()->badge(),
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
            // RelationManagers\CompetenciesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCompetencyCategories::route('/'),
            'create' => Pages\CreateCompetencyCategory::route('/create'),
            'edit' => Pages\EditCompetencyCategory::route('/{record}/edit'),
        ];
    }
}
