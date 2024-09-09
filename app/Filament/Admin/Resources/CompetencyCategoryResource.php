<?php

namespace App\Filament\Admin\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Competency;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Models\CompetencyCategory;
use App\Filament\Admin\Clusters\Competencies;
use Filament\Forms\Components\Component;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Actions\Action;
use Filament\Resources\Concerns\Translatable;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\CompetencyCategoryResource\Pages;
use App\Filament\Admin\Resources\CompetencyCategoryResource\RelationManagers;

class CompetencyCategoryResource extends Resource
{
    use Translatable;
    
    protected static ?string $model = CompetencyCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Competency Categories';

    protected static ?string $cluster = Competencies::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Section::make('Competency Category Name')
                    ->columns(3)
                    ->extraAttributes(['style' => 'background-color: #E6E6E6;'])
                    ->icon('heroicon-m-chat-bubble-oval-left-ellipsis')
                    ->iconColor('primary')
                    ->description('A sensible, descriptive name for the competency category. Ideally not too long!')
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

                Forms\Components\Section::make('Competency Category Description')
                    ->icon('heroicon-m-document-text')
                    ->iconColor('primary')
                    ->extraAttributes(['style' => 'background-color: #E6E6E6;'])
                    ->description('A brief explanation of the competency category.')
                    ->columns(3)
                    ->schema([
                        Forms\Components\TextInput::make('description')->hiddenOn(['edit', 'create']),
                        Forms\Components\Textarea::make('description_en')
                                        ->label('English')
                                        ->rows(6),
                                        // ->requiredWithoutAll('description_es, description_fr')
                                        // ->validationMessages(['required_without_all' => 'Enter the description in at least one language']),
                        Forms\Components\Textarea::make('description_es')
                                        ->label('Spanish')
                                        ->rows(6),
                                        // ->requiredWithoutAll('description_en, description_fr')
                                        // ->validationMessages(['required_without_all' => 'Enter the description in at least one language']),
                        Forms\Components\Textarea::make('description_fr')
                                        ->label('French')
                                        ->rows(6),
                                        // ->requiredWithoutAll('description_es, description_en')
                                        // ->validationMessages(['required_without_all' => 'Enter the description in at least one language']),
                    ]),
         
                Forms\Components\Section::make('Competencies')
                    ->icon('heroicon-m-arrow-turn-down-right')
                    ->iconColor('primary')
                    ->extraAttributes(['style' => 'background-color: #E6E6E6;'])
                    ->description('Which competencies belong in this competency category? To create a new competency, click on the plus icon.')
                    ->columns(3)
                    ->schema([
                        Forms\Components\Select::make('competencies')
                            ->relationship('competencies', 'name')
                            ->columnSpan(2)
                            ->label('')
                            ->multiple()
                            ->preload()
                            ->placeholder('Select competencies')
                            ->loadingMessage('Loading competencies...')
                            ->searchable()
                            ->noSearchResultsMessage('No competencies match your search')
                            ->getOptionLabelFromRecordUsing(fn($record, $livewire) => $record->getTranslation('name', 'en'))
                            ->createOptionForm([
                                Forms\Components\Section::make('Competency Name')
                                    ->columns(3)
                                    ->extraAttributes(['style' => 'background-color: #E6E6E6;'])
                                    ->icon('heroicon-m-chat-bubble-oval-left-ellipsis')
                                    ->iconColor('primary')
                                    ->description('A sensible, descriptive name for the competency. Ideally not too long!')
                                    ->schema([
                                        Forms\Components\TextInput::make('name')->hidden(),
                                        Forms\Components\Textarea::make('name_en')
                                                        ->label('English')
                                                        ->rows(4)
                                                        ->regex('/^(?=.*[^\W_])[^\n]+$/')
                                                        ->requiredWithoutAll('name_es, name_fr')
                                                        ->validationMessages(['regex' => 'Name cannot only contain special characters',
                                                                            'required_without_all' => 'Enter the name in at least one language']),
                                        Forms\Components\Textarea::make('name_es')
                                                        ->label('Spanish')
                                                        ->rows(4)
                                                        ->regex('/^(?=.*[^\W_])[^\n]+$/')
                                                        ->requiredWithoutAll('name_en, name_fr')
                                                        ->validationMessages(['regex' => 'Name cannot only contain special characters',
                                                                            'required_without_all' => 'Enter the name in at least one language']),
                                        Forms\Components\Textarea::make('name_fr')
                                                        ->label('French')
                                                        ->rows(4)
                                                        ->regex('/^(?=.*[^\W_])[^\n]+$/')
                                                        ->requiredWithoutAll('name_es, name_en')
                                                        ->validationMessages(['regex' => 'Name cannot only contain special characters',
                                                                            'required_without_all' => 'Enter the name in at least one language']),
                                    ]),

                                Forms\Components\Section::make('Competency Description')
                                    ->icon('heroicon-m-document-text')
                                    ->iconColor('primary')
                                    ->extraAttributes(['style' => 'background-color: #E6E6E6;'])
                                    ->description('A brief explanation of the competency.')
                                    ->columns(3)
                                    ->schema([
                                        Forms\Components\TextInput::make('description')->hidden(),
                                        Forms\Components\Textarea::make('description_en')
                                                        ->label('English')
                                                        ->rows(6),
                                                        // ->requiredWithoutAll('description_es, description_fr')
                                                        // ->validationMessages(['required_without_all' => 'Enter the description in at least one language']),
                                        Forms\Components\Textarea::make('description_es')
                                                        ->label('Spanish')
                                                        ->rows(6),
                                                        // ->requiredWithoutAll('description_en, description_fr')
                                                        // ->validationMessages(['required_without_all' => 'Enter the description in at least one language']),
                                        Forms\Components\Textarea::make('description_fr')
                                                        ->label('French')
                                                        ->rows(6),
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
                    ])
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
