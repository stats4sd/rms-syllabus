<?php

namespace App\Filament\Admin\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Module;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Concerns\Translatable;
use App\Filament\Admin\Resources\ModuleResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\ModuleResource\RelationManagers;

class ModuleResource extends Resource
{
    use Translatable;

    protected static ?string $model = Module::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Module Name')
                            ->columns(3)
                            ->extraAttributes(['style' => 'background-color: #E6E6E6;'])
                            ->icon('heroicon-m-chat-bubble-oval-left-ellipsis')
                            ->iconColor('primary')
                            ->description('A sensible, descriptive name for the module. Ideally not too long!')
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

                Forms\Components\Section::make('Module Description')
                            ->icon('heroicon-m-document-text')
                            ->iconColor('primary')
                            ->extraAttributes(['style' => 'background-color: #E6E6E6;'])
                            ->description('A brief explanation of the topic and the scope/focus of the module. ')
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

                Forms\Components\Section::make('Why do this module?')
                            ->extraAttributes(['style' => 'background-color: #E6E6E6;'])
                            ->description('In what roles/projects is this helpful? What are the advantages of learning this? When/why might you use this knowledge or skill?')
                            ->icon('heroicon-m-question-mark-circle')
                            ->iconColor('primary')
                            ->columns(3)
                            ->schema([
                                Forms\Components\TextInput::make('why')->hiddenOn(['edit', 'create']),
                                Forms\Components\Textarea::make('why_en')
                                                ->label('English')
                                                ->rows(8),
                                                // ->requiredWithoutAll('why_es, why_fr')
                                                // ->validationMessages(['required_without_all' => 'Enter the why in at least one language']),
                                Forms\Components\Textarea::make('why_es')
                                                ->label('Spanish')
                                                ->rows(8),
                                                // ->requiredWithoutAll('why_en, why_fr')
                                                // ->validationMessages(['required_without_all' => 'Enter the why in at least one language']),
                                Forms\Components\Textarea::make('why_fr')
                                                ->label('French')
                                                ->rows(8),
                                                // ->requiredWithoutAll('why_es, why_en')
                                                // ->validationMessages(['required_without_all' => 'Enter the why in at least one language']),
                            ]),

                Forms\Components\Section::make('What will you learn?')
                            ->extraAttributes(['style' => 'background-color: #E6E6E6;'])
                            ->description('When you have completed this module, you will be able to... ')
                            ->icon('heroicon-m-book-open')
                            ->iconColor('primary')
                            ->columns(3)
                            ->schema([
                                Forms\Components\TextInput::make('learning_outcome')->hiddenOn(['edit', 'create']),
                                Forms\Components\RichEditor::make('learning_outcome_en')
                                                ->label('English')
                                                ->toolbarButtons(['bulletList']),
                                                // ->requiredWithoutAll('learning_outcome_es, learning_outcome_fr')
                                                // ->validationMessages(['required_without_all' => 'Enter the learning_outcome in at least one language']),
                                Forms\Components\RichEditor::make('learning_outcome_es')
                                                ->label('Spanish')
                                                ->toolbarButtons(['bulletList']),
                                                // ->requiredWithoutAll('learning_outcome_en, learning_outcome_fr')
                                                // ->validationMessages(['required_without_all' => 'Enter the learning_outcome in at least one language']),
                                Forms\Components\RichEditor::make('learning_outcome_fr')
                                                ->label('French')
                                                ->toolbarButtons(['bulletList']),
                                                // ->requiredWithoutAll('learning_outcome_es, learning_outcome_en')
                                                // ->validationMessages(['required_without_all' => 'Enter the learning_outcome in at least one language']),
                            ]),

                Forms\Components\Section::make('Competencies')
                    ->extraAttributes(['style' => 'background-color: #E6E6E6;'])
                    ->description('Which competency/competencies are linked to this module?')
                    ->icon('heroicon-m-tag')
                    ->iconColor('primary')
                    ->schema([
                        Forms\Components\Select::make('competencies')
                                            ->label('')
                                            ->columnSpan(2)
                                            ->relationship('competencies', 'name')
                                            ->placeholder('Select competencies')
                                            ->multiple()
                                            ->preload()
                                            ->loadingMessage('Loading competencies...')
                                            ->noSearchResultsMessage('No competencies match your search')
                                            ->getOptionLabelFromRecordUsing(fn($record, $livewire) => $record->getTranslation('name', 'en'))
                                            ->searchable()
                    ])->columns(3),

                    Forms\Components\Section::make('Prerequisites')
                    ->extraAttributes(['style' => 'background-color: #E6E6E6;'])
                    ->description('Before completing this module, you should be familiar with the contents of the following modules')
                    ->icon('heroicon-m-arrow-left')
                    ->iconColor('primary')
                        ->schema([
                            Forms\Components\Select::make('prerequisites')
                            ->label('')
                            ->columnSpan(2)
                            ->relationship('prerequisites', 'name')
                            ->placeholder('Select modules')
                            ->multiple()
                            ->preload()
                            ->loadingMessage('Loading modules...')
                            ->noSearchResultsMessage('No modules match your search')
                            ->getOptionLabelFromRecordUsing(fn($record, $livewire) => $record->getTranslation('name', 'en'))
                            ->searchable()
                        ])->columns(3),

                Forms\Components\Section::make('Time estimate')
                    ->extraAttributes(['style' => 'background-color: #E6E6E6;'])
                    ->description('The estimated time it takes to complete the moudle in hours e.g., 1 hour, 2.5 hours')
                    ->columns(3)
                    ->icon('heroicon-m-clock')
                    ->iconColor('primary')
                    ->schema([
                        Forms\Components\TextInput::make('time_estimate')
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

                Forms\Components\Section::make('Guidance')
                        ->extraAttributes(['style' => 'background-color: #E6E6E6;'])
                        ->description('What guidance would you give students if you were delivering this as a course? Instructions for completing the module that do not fit into one specific section/activity, general guidance, any other useful context.')
                        ->icon('heroicon-m-academic-cap')
                        ->iconColor('primary')
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

                Forms\Components\Section::make('Research Components')
                    ->description('Which category/categories does this module fit into?')
                    ->extraAttributes(['style' => 'background-color: #E6E6E6;'])
                    ->icon('heroicon-m-tag')
                    ->iconColor('primary')
                    ->schema([
                        Forms\Components\Select::make('research_component_id')
                            ->label('')
                            ->columnSpan(2)
                            ->relationship('researchComponents', 'name')
                            ->placeholder('Select research components')
                            ->multiple()
                            ->preload()
                            ->loadingMessage('Loading research components...')
                            ->noSearchResultsMessage('No research components match your search')
                            ->getOptionLabelFromRecordUsing(fn($record, $livewire) => $record->getTranslation('name', 'en'))
                            ->searchable()
                        ])->columns(3),

                Forms\Components\Section::make('Cover image')
                    ->icon('heroicon-m-photo')
                    ->iconColor('primary')
                    ->extraAttributes(['style' => 'background-color: #E6E6E6;'])
                    ->schema([
                        Forms\Components\SpatieMediaLibraryFileUpload::make('cover_image')
                                                                        ->collection('module_cover')
                                                                        ->label('')
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
                Tables\Columns\SpatieMediaLibraryImageColumn::make('cover_image')->collection('module_cover'),
                Tables\Columns\TextColumn::make('sections_count')
                                            ->counts('sections')
                                            ->label('# Sections')
                                            ->sortable(),
                Tables\Columns\TextColumn::make('pathways_count')
                                            ->counts('pathways')
                                            ->label('# Pathways')
                                            ->sortable(),
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
