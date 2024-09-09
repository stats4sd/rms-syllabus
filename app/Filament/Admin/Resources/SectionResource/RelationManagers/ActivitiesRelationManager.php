<?php

namespace App\Filament\Admin\Resources\SectionResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Trove;
use App\Models\Activity;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Admin\Resources\ActivityResource;
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
                    ]),

                Forms\Components\Hidden::make('creator_id')->default(Auth::user()->id),
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
                        $record->guidance = '';
                
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

                        if(!is_null($data['guidance_en'])){
                            $record->setTranslation('guidance', 'en', $data['guidance_en']);
                        }
                
                        if(!is_null($data['guidance_es'])){
                            $record->setTranslation('guidance', 'es', $data['guidance_es']);
                        }
                
                        if(!is_null($data['guidance_fr'])){
                            $record->setTranslation('guidance', 'fr', $data['guidance_fr']);
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
