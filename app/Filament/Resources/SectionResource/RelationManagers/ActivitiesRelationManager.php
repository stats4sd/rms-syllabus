<?php

namespace App\Filament\Resources\SectionResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Activity;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ActivityResource;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\RelationManagers\Concerns\Translatable;

class ActivitiesRelationManager extends RelationManager
{
    use Translatable;

    protected static string $relationship = 'activities';

    public function form(Form $form): Form
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
                                Forms\Components\TextInput::make('name_en')
                                                ->label('English')
                                                ->requiredWithoutAll('name_es, name_fr')
                                                ->validationMessages(['required_without_all' => 'Enter the name in at least one language']),
                                Forms\Components\TextInput::make('name_es')
                                                ->label('Spanish')
                                                ->requiredWithoutAll('name_en, name_fr')
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

                    Forms\Components\Fieldset::make('trove_field')
                    ->label('Trove')
                    ->columns(3)
                    ->schema([
                        Forms\Components\TextInput::make('trove_id')->hiddenOn(['edit', 'create']),
                        Forms\Components\Select::make('trove_id_en')
                                        ->label('English')
                                        ->relationship('trove', 'title')
                                        ->placeholder('Select a trove')
                                        ->preload()
                                        ->loadingMessage('Loading troves...')
                                        ->searchable()
                                        ->noSearchResultsMessage('No troves match your search')
                                        ->getOptionLabelFromRecordUsing(fn($record, $livewire) => $record->getTranslation('title', 'en'))
                                        ->requiredWithoutAll('trove_es, trove_fr')
                                        ->validationMessages(['required_without_all' => 'A trove must be selected in at least one lanugage']),
                        Forms\Components\Select::make('trove_id_es')
                                        ->label('Spanish')
                                        ->relationship('trove', 'title')
                                        ->placeholder('Select a trove')
                                        ->preload()
                                        ->loadingMessage('Loading troves...')
                                        ->searchable()
                                        ->noSearchResultsMessage('No troves match your search')
                                        ->getOptionLabelFromRecordUsing(fn($record, $livewire) => $record->getTranslation('title', 'en'))
                                        ->requiredWithoutAll('trove_en, trove_fr')
                                        ->validationMessages(['required_without_all' => 'A trove must be selected in at least one lanugage']),
                        Forms\Components\Select::make('trove_id_fr')
                                        ->label('French')
                                        ->relationship('trove', 'title')
                                        ->placeholder('Select a trove')
                                        ->preload()
                                        ->loadingMessage('Loading troves...')
                                        ->searchable()
                                        ->noSearchResultsMessage('No troves match your search')
                                        ->getOptionLabelFromRecordUsing(fn($record, $livewire) => $record->getTranslation('title', 'en'))
                                        ->requiredWithoutAll('trove_es, trove_en')
                                        ->validationMessages(['required_without_all' => 'A trove must be selected in at least one lanugage']),
                    ]),

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
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
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\LocaleSwitcher::make(),
                Tables\Actions\AttachAction::make()
                                            ->preloadRecordSelect(),
                                            // ->getOptionLabelFromRecordUsing(fn($record, $livewire) => $record->getTranslation('name', 'en')),
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['name'] = 'names added after creation';
                        $data['description'] = 'descriptions added after creation';
                        $data['trove_id'] = 'trove ids added after creation';
                        return $data;
                    })
                    ->after(function (Activity $record, array $data) {
                        $record->name = '';
                        $record->description = '';
                        $record->trove_id = '';
                
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
                
                        if(!is_null($data['trove_id_en'])){
                            $record->setTranslation('trove_id', 'en', $data['trove_id_en']);
                        }
                
                        if(!is_null($data['trove_id_es'])){
                            $record->setTranslation('trove_id', 'es', $data['trove_id_es']);
                        }
                
                        if(!is_null($data['trove_id_fr'])){
                            $record->setTranslation('trove_id', 'fr', $data['trove_id_fr']);
                        }

                        $record->save();
                    })
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            -> recordUrl(fn(Activity $record) => ActivityResource::getUrl('edit', ['record' => $record])
        );
    }
}
