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
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Concerns\Translatable;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\CompetencyResource\Pages;
use App\Filament\Admin\Resources\CompetencyResource\RelationManagers;

class CompetencyResource extends Resource
{
    use Translatable;
    
    protected static ?string $model = Competency::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Competencies::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Select::make('competency_category_id')
                    ->relationship('competencyCategories', 'name')
                    ->required()
                    ->multiple()
                    ->preload()
                    ->placeholder('Select categories')
                    ->loadingMessage('Loading categories...')
                    ->searchable()
                    ->noSearchResultsMessage('No categories match your search')
                    ->getOptionLabelFromRecordUsing(fn($record, $livewire) => $record->getTranslation('name', 'en')),

                Forms\Components\Fieldset::make('name_field')
                    ->label('Name')
                    ->columns(3)
                    ->schema([
                        Forms\Components\TextInput::make('name')->hiddenOn(['edit', 'create']),
                        Forms\Components\Textarea::make('name_en')
                                        ->label('English')
                                        ->rows(2)
                                        ->requiredWithoutAll('name_es, name_fr')
                                        ->validationMessages(['required_without_all' => 'Enter the name in at least one language']),
                        Forms\Components\Textarea::make('name_es')
                                        ->label('Spanish')
                                        ->rows(2)
                                        ->requiredWithoutAll('name_en, name_fr')
                                        ->validationMessages(['required_without_all' => 'Enter the name in at least one language']),
                        Forms\Components\Textarea::make('name_fr')
                                        ->label('French')
                                        ->rows(2)
                                        ->requiredWithoutAll('name_es, name_en')
                                        ->validationMessages(['required_without_all' => 'Enter the name in at least one language']),
                    ]),

                Forms\Components\Fieldset::make('description_field')
                    ->label('Description')
                    ->columns(3)
                    ->schema([
                        Forms\Components\TextInput::make('description')->hiddenOn(['edit', 'create']),
                        Forms\Components\Textarea::make('description_en')
                                        ->label('English')
                                        ->rows(4),
                                        // ->requiredWithoutAll('description_es, description_fr')
                                        // ->validationMessages(['required_without_all' => 'Enter the description in at least one language']),
                        Forms\Components\Textarea::make('description_es')
                                        ->label('Spanish')
                                        ->rows(4),
                                        // ->requiredWithoutAll('description_en, description_fr')
                                        // ->validationMessages(['required_without_all' => 'Enter the description in at least one language']),
                        Forms\Components\Textarea::make('description_fr')
                                        ->label('French')
                                        ->rows(4),
                                        // ->requiredWithoutAll('description_es, description_en')
                                        // ->validationMessages(['required_without_all' => 'Enter the description in at least one language']),
                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->wrap()->sortable(),
                Tables\Columns\TextColumn::make('description')->wrap(),
                Tables\Columns\TextColumn::make('competencyCategories.name')->listWithLineBreaks()->badge()->label('Categories'),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCompetencies::route('/'),
            'create' => Pages\CreateCompetency::route('/create'),
            'edit' => Pages\EditCompetency::route('/{record}/edit'),
        ];
    }
}
