<?php

namespace App\Filament\Admin\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Trove;
use App\Models\Activity;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Concerns\Translatable;
use App\Filament\Admin\Resources\ActivityResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\ActivityResource\RelationManagers;

class ActivityResource extends Resource
{
    use Translatable;

    protected static ?string $model = Activity::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Section::make('Sections')
                    ->icon('heroicon-m-arrow-turn-left-up')
                    ->iconColor('primary')
                    ->extraAttributes(['style' => 'background-color: #E6E6E6;'])
                    ->description('Which section/sections does this activity belong in? An activity can be used as part of sections in different modules.')
                    ->schema([
                        Forms\Components\Select::make('section_id')
                            ->label('')
                            ->relationship('sections', 'name')
                            ->columnSpan(2)
                            ->required()
                            ->preload()
                            ->multiple()
                            ->loadingMessage('Loading sections...')
                            ->searchable()
                            ->noSearchResultsMessage('No sections match your search')
                            ->placeholder('Select a section/sections')
                            ->getOptionLabelFromRecordUsing(fn($record, $livewire) => $record->getTranslation('name', 'en')),
                        ])->columns(3),

                Forms\Components\Section::make('Trove')
                    ->description('Which trove does this activity correspond to?')
                    ->icon('heroicon-m-link')
                    ->iconColor('primary')
                    ->extraAttributes(['style' => 'background-color: #E6E6E6;'])
                    ->columns(3)
                    ->schema([
                        Forms\Components\TextInput::make('trove_id')->hiddenOn(['edit', 'create']),
                        Forms\Components\Select::make('trove_id_en')
                                        ->label('English')
                                        ->options(Trove::all()->pluck('title', 'id'))
                                        ->placeholder('Select a trove')
                                        ->preload()
                                        ->loadingMessage('Loading troves...')
                                        ->searchable()
                                        ->noSearchResultsMessage('No troves match your search')
                                        ->getOptionLabelFromRecordUsing(fn($record, $livewire) => $record->getTranslation('title', 'en'))
                                        ->requiredWithoutAll('trove_id_es, trove_id_fr')
                                        ->validationMessages(['required_without_all' => 'A trove must be selected in at least one lanugage']),
                        Forms\Components\Select::make('trove_id_es')
                                        ->label('Spanish')
                                        ->options(Trove::all()->pluck('title', 'id'))
                                        ->placeholder('Select a trove')
                                        ->preload()
                                        ->loadingMessage('Loading troves...')
                                        ->searchable()
                                        ->noSearchResultsMessage('No troves match your search')
                                        ->getOptionLabelFromRecordUsing(fn($record, $livewire) => $record->getTranslation('title', 'en'))
                                        ->requiredWithoutAll('trove_id_en, trove_id_fr')
                                        ->validationMessages(['required_without_all' => 'A trove must be selected in at least one lanugage']),
                        Forms\Components\Select::make('trove_id_fr')
                                        ->label('French')
                                        ->options(Trove::all()->pluck('title', 'id'))
                                        ->placeholder('Select a trove')
                                        ->preload()
                                        ->loadingMessage('Loading troves...')
                                        ->searchable()
                                        ->noSearchResultsMessage('No troves match your search')
                                        ->getOptionLabelFromRecordUsing(fn($record, $livewire) => $record->getTranslation('title', 'en'))
                                        ->requiredWithoutAll('trove_id_es, trove_id_en')
                                        ->validationMessages(['required_without_all' => 'A trove must be selected in at least one lanugage']),
                    ]),

                Forms\Components\Section::make('Activity Name')
                    ->icon('heroicon-m-chat-bubble-oval-left-ellipsis')
                    ->iconColor('primary')
                    ->extraAttributes(['style' => 'background-color: #E6E6E6;'])
                    ->description('You can use the trove name, or a version that makes more sense in context. For example, you might not need to include the name of a series of presentations if you are sharing just one.')
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

                Forms\Components\Section::make('Activity Description')
                    ->icon('heroicon-m-document-text')
                    ->iconColor('primary')
                    ->extraAttributes(['style' => 'background-color: #E6E6E6;'])
                    ->description('Specific description and a brief summary of what to expect. Any other commentary you wish to include.')
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
                    ->extraAttributes(['style' => 'background-color: #E6E6E6;'])
                    ->icon('heroicon-m-academic-cap')
                    ->iconColor('primary')
                    ->description('This should give clear instructions – e.g. do this section, read pages 12-20. Look out for this specific point. Imagine if you were presenting a class or workshop and asking people to do activities/read something - you wouldn\'t just hand it to them and say \'read this\'. What context or instructions you would give along with it?')
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

                Forms\Components\Section::make('Type')
                    ->icon('heroicon-m-squares-2x2')
                    ->iconColor('primary')
                    ->extraAttributes(['style' => 'background-color: #E6E6E6;'])
                    ->schema([
                        Forms\Components\Select::make('type')
                                            ->label('')
                                            ->columnSpan(1)
                                            ->required()
                                            ->placeholder('Select the type of this activity')
                                            ->options([
                                                'document' => 'Document',
                                                'website' => 'Website',
                                                'video' => 'Video',
                                                'presentation' => 'Presentation',
                                                'picture' => 'Picture',
                                                'course' => 'Course',
                                                'other' => 'Other',
                                            ])
                    ])->columns(3),

                Forms\Components\Hidden::make('creator_id')->default(Auth::user()->id),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\IconColumn::make('type')
                                    ->sortable()
                                    ->color('primary')
                                    ->icon(fn (string $state): string => match ($state) {
                                        'document' => 'heroicon-m-document-duplicate',
                                        'website' => 'heroicon-m-link',
                                        'video' => 'heroicon-m-video-camera',
                                        'presentation' => 'heroicon-m-presentation-chart-bar',
                                        'picture' => 'heroicon-m-photo',
                                        'course' => 'heroicon-m-academic-cap',
                                        'other' => 'heroicon-m-ellipsis-horizontal-circle',
                                    }),
                Tables\Columns\TextColumn::make('name')->wrap()->sortable(),
                // Tables\Columns\TextColumn::make('description')->wrap(),
                Tables\Columns\TextColumn::make('sections_count')
                                ->counts('sections')
                                ->label('# Modules') //sections belong to a module
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivities::route('/'),
            'create' => Pages\CreateActivity::route('/create'),
            'edit' => Pages\EditActivity::route('/{record}/edit'),
        ];
    }
}
