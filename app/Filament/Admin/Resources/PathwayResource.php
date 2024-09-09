<?php

namespace App\Filament\Admin\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Pathway;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Repeater;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Concerns\Translatable;
use App\Filament\Admin\Resources\PathwayResource\Pages;
use Filament\Infolists\Components\RepeatableEntry;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\PathwayResource\RelationManagers;

class PathwayResource extends Resource
{
    use Translatable;
    
    protected static ?string $model = Pathway::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Pathway Name')
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

                Forms\Components\Section::make('Pathway Description')
                ->icon('heroicon-m-document-text')
                ->iconColor('primary')
                ->extraAttributes(['style' => 'background-color: #E6E6E6;'])
                ->description('A brief explanation of the topic and the scope/focus of the pathway.')
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
                            
                Forms\Components\Section::make('Modules')
                    ->extraAttributes(['style' => 'background-color: #E6E6E6;'])
                    ->icon('heroicon-m-bars-4')
                    ->iconColor('primary')
                    ->description('Which modules belong in this pathway? To change the order, click on the double arrow icon and drag the module.')
                    ->schema([
                        Repeater::make('modulePathways')
                                ->relationship()
                                ->label('')
                                ->defaultItems(0)
                                ->addActionLabel('Add a module')
                                ->columnSpan(2)
                                ->simple(
                                    Forms\Components\Select::make('module_id')
                                        ->relationship('module', 'name')
                                        ->label('')
                                        ->distinct()
                                        ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                        ->preload()
                                        ->placeholder('Select a module')
                                        ->loadingMessage('Loading modules...')
                                        ->searchable()
                                        ->noSearchResultsMessage('No modules match your search')
                                        ->getOptionLabelFromRecordUsing(fn($record, $livewire) => $record->getTranslation('name', 'en'))
                                )->orderColumn('module_order')
                        ])->columns(3),

                Forms\Components\Hidden::make('creator_id')->default(Auth::user()->id),

                Forms\Components\Section::make('Status')
                    ->extraAttributes(['style' => 'background-color: #E6E6E6;'])
                    ->icon('heroicon-m-exclamation-triangle')
                    ->iconColor('primary')
                    ->description('Pathways kept in draft mode will only be visible on the frontend with the correct URL. Mark the pathway as published for it to be visible to anyone expoloring/searching the frontend.')
                    ->schema([
                        Forms\Components\ToggleButtons::make('status')
                            ->label('')
                            ->columnStart(1)
                            ->columnSpan(2)
                            ->inline()
                            ->default('Draft') 
                            ->options([
                                'Draft' => 'Draft - in progress',
                                'Published' => 'Published - live on frontend'
                            ])
                            ->icons([
                                'Draft' => 'heroicon-o-cog-8-tooth',
                                'Published' => 'heroicon-o-check-circle',
                            ])
                            ->colors([
                                'Draft' => 'warning',
                                'Published' => 'success',
                            ]),
                        ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->wrap(),
                Tables\Columns\TextColumn::make('modules_count')
                                ->counts('modules')
                                ->label('# Modules')
                                ->sortable(),
                Tables\Columns\TextColumn::make('status')
                                ->badge()
                                ->sortable()
                                ->color(fn (string $state): string => match ($state) {
                                    'Draft' => 'warning',
                                    'Published' => 'success',
                                }),
                Tables\Columns\TextColumn::make('user.name')
                                ->label('Created by')
                                ->sortable(),
            ])

            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('view_frontend')
                                ->label('View on frontend')
                                ->icon('heroicon-o-eye')
                                ->url(fn (Pathway $record): string => '/pathways/' . $record->slug)
                                ->openUrlInNewTab()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListPathways::route('/'),
            'create' => Pages\CreatePathway::route('/create'),
            'edit' => Pages\EditPathway::route('/{record}/edit'),
        ];
    }
}
