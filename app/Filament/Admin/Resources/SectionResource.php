<?php

namespace App\Filament\Admin\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Section;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Concerns\Translatable;
use App\Filament\Admin\Resources\SectionResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\SectionResource\RelationManagers;

class SectionResource extends Resource
{
    use Translatable;

    protected static ?string $model = Section::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Module Sections';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Section::make('Module')
                    ->extraAttributes(['style' => 'background-color: #E6E6E6;'])
                    ->description('Which module does this section belong in?')
                    ->icon('heroicon-m-arrow-turn-left-up')
                    ->iconColor('primary')
                    ->schema([
                        Forms\Components\Select::make('module_id')
                            ->label('')
                            ->columnSpan(2)
                            ->relationship('module', 'name')
                            ->required()
                            ->preload()
                            ->loadingMessage('Loading modules...')
                            ->searchable()
                            ->noSearchResultsMessage('No modules match your search')
                            ->placeholder('Select a module')
                            ->getOptionLabelFromRecordUsing(fn($record, $livewire) => $record->getTranslation('name', 'en')),
                    ])->columns(3),

                Forms\Components\Section::make('Section Name')
                    ->description('A sensible, descriptive name for the section.')
                    ->icon('heroicon-m-chat-bubble-oval-left-ellipsis')
                    ->iconColor('primary')
                    ->extraAttributes(['style' => 'background-color: #E6E6E6;'])
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

                Forms\Components\Section::make('Section Description')
                        ->icon('heroicon-m-document-text')
                        ->iconColor('primary')
                        ->extraAttributes(['style' => 'background-color: #E6E6E6;'])
                        ->description('What does the section include and what will you learn? Any notes, e.g. on how this relates to the previous/following sections?')
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

                Forms\Components\Section::make('Guidance')
                        ->description('What guidance would you give students if you were delivering this as a course? Any navigation instructions, advice or overall commentary you want to include here.')
                        ->icon('heroicon-m-academic-cap')
                        ->iconColor('primary')
                        ->extraAttributes(['style' => 'background-color: #E6E6E6;'])
                        ->columns(3)
                        ->schema([
                            Forms\Components\TextInput::make('guidance')->hiddenOn(['edit', 'create']),
                            Forms\Components\Textarea::make('guidance_en')
                                            ->label('English')
                                            ->rows(8),
                                            // ->requiredWithoutAll('guidance_es, guidance_fr')
                                            // ->validationMessages(['required_without_all' => 'Enter guidance in at least one language']),
                            Forms\Components\Textarea::make('guidance_es')
                                            ->label('Spanish')
                                            ->rows(8),
                                            // ->requiredWithoutAll('guidance_en, guidance_fr')
                                            // ->validationMessages(['required_without_all' => 'Enter guidance in at least one language']),
                            Forms\Components\Textarea::make('guidance_fr')
                                            ->label('French')
                                            ->rows(8),
                                            // ->requiredWithoutAll('guidance_es, guidance_en')
                                            // ->validationMessages(['required_without_all' => 'Enter guidance in at least one language']),
                        ]),

                Forms\Components\Section::make('Time estimate')
                        ->description('The estimated time it takes to complete the section in hours e.g., 1 hour, 2.5 hours')
                        ->columns(3)
                        ->extraAttributes(['style' => 'background-color: #E6E6E6;'])
                        ->icon('heroicon-m-clock')
                        ->iconColor('primary')
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
                                ->validationMessages(['required_without_all' => 'Enter the time estimate in at least one language']),
                            Forms\Components\TextInput::make('time_estimate_es')
                                ->label('Spanish')
                                ->numeric()
                                ->inputMode('decimal')
                                ->placeholder('Enter a time estimate')
                                ->requiredWithoutAll('time_estimate_en, time_estimate_fr')
                                ->validationMessages(['required_without_all' => 'Enter the time estimate in at least one language']),
                            Forms\Components\TextInput::make('time_estimate_fr')
                                ->label('French')
                                ->numeric()
                                ->inputMode('decimal')
                                ->placeholder('Enter a time estimate')
                                ->requiredWithoutAll('time_estimate_en, time_estimate_es')
                                ->validationMessages(['required_without_all' => 'Enter the time estimate in at least one language']),
                        ]),


                Forms\Components\Hidden::make('creator_id')->default(Auth::user()->id),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->wrap()->sortable(),
                // Tables\Columns\TextColumn::make('description')->wrap(),
                Tables\Columns\TextColumn::make('time_estimate')->suffix(' hours'),
                Tables\Columns\TextColumn::make('activities_count')
                                            ->counts('activities')
                                            ->label('# Activities')
                                            ->sortable(),
                Tables\Columns\TextColumn::make('module.name')->wrap()->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                                            ->label('Created by')
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
            RelationManagers\ActivitiesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSections::route('/'),
            'create' => Pages\CreateSection::route('/create'),
            'edit' => Pages\EditSection::route('/{record}/edit'),
        ];
    }
}
