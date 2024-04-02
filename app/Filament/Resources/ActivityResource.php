<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Activity;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Concerns\Translatable;
use App\Filament\Resources\ActivityResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ActivityResource\RelationManagers;

class ActivityResource extends Resource
{
    use Translatable;
    
    protected static ?string $model = Activity::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Section::make('')
                    ->schema([
                        Forms\Components\Select::make('section_id')
                            ->relationship('sections', 'name')
                            ->required()
                            ->preload()
                            ->multiple()
                            ->loadingMessage('Loading sections...')
                            ->searchable()
                            ->noSearchResultsMessage('No sections match your search')
                            ->placeholder('Select the sections for this activity')
                            ->getOptionLabelFromRecordUsing(fn($record, $livewire) => $record->getTranslation('name', 'en')),
                        ]),

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
                                                ->requiredWithoutAll('name_es, label_fr')
                                                ->validationMessages(['required_without_all' => 'Enter the name in at least one language']),
                                Forms\Components\Textarea::make('name_es')
                                                ->label('Spanish')
                                                ->rows(2)
                                                ->requiredWithoutAll('name_en, label_fr')
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
                            
                Forms\Components\Section::make('')
                    ->schema([
                        Forms\Components\Select::make('type')
                                            ->options([
                                                'document' => 'Document',
                                                'website' => 'Website',
                                                'video' => 'Video',
                                                'presentation' => 'Presentation',
                                                'picture' => 'Picture',
                                                'course' => 'Course',
                                                'other' => 'Other',
                                            ])
                    ]),

                Forms\Components\Section::make('')
                    // ->description('description here........')
                    ->schema([
                        Forms\Components\Select::make('trove_id')
                                            ->relationship('trove', 'title')
                                            ->placeholder('Select a trove')
                                            ->preload()
                                            // ->required()
                                            ->loadingMessage('Loading troves...')
                                            ->searchable()
                                            ->noSearchResultsMessage('No troves match your search')
                                            ->getOptionLabelFromRecordUsing(fn($record, $livewire) => $record->getTranslation('title', 'en'))
                    ]),

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
                Tables\Columns\TextColumn::make('description')->wrap(),
                Tables\Columns\TextColumn::make('section.name')->wrap()->sortable(),
                Tables\Columns\TextColumn::make('section.module.name')->wrap()->sortable(),
                Tables\Columns\TextColumn::make('section.module.researchComponent.name')->wrap()->sortable(),
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
