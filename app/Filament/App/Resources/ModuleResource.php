<?php

namespace App\Filament\App\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Module;
use App\Models\Activity;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconPosition;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Actions;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use App\Filament\App\Resources\ModuleResource;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\RepeatableEntry;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\App\Resources\ModuleResource\Pages;
use App\Filament\Infolists\Components\ListRepeatableEntry;
use App\Filament\App\Resources\ModuleResource\RelationManagers;

class ModuleResource extends Resource
{
    protected static ?string $model = Module::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationItems(): array 
    {
        return [] ;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('name')
                    ->label('')
                    ->weight(FontWeight::Bold)
                    ->size(TextEntry\TextEntrySize::Large),

                Fieldset::make('')
                    ->schema([
                        TextEntry::make('time_estimate')
                            ->label('')
                            ->icon('heroicon-m-clock')
                            ->prefix('Est. duration: ')
                            ->suffix(' hours'),
                        TextEntry::make('completion_status')
                            ->label('')
                            ->hidden(Auth::guest())
                            ->icon(fn(string $state): string => match ($state) {
                                'guest' => 'heroicon-m-arrow-right-end-on-rectangle',
                                'Not Started' => 'heroicon-m-exclamation-circle',
                                'In Progress' => 'heroicon-m-cog-8-tooth',
                                'Completed' => 'heroicon-m-check-badge',
                            }),
                        Actions::make([
                            Action::make('continue')
                                ->label('Continue learning')
                                ->icon('heroicon-s-arrow-long-right')
                                ->color('gray')
                                // ->outlined()
                                ->url(function (Module $record) {
                                    $next_record= $record->next->first();
                                    return ModuleResource::getUrl('view', ['record' => $next_record]);
                                })
                                ->hidden(fn(Module $record) => $record->last ===1),
                        ]),
                    ])->columns(3),

                Actions::make([
                    Action::make('return_path')
                        ->label('Return to pathway')
                        ->icon('heroicon-m-chevron-left')
                        ->color('stats4sd')
                        ->link()
                        ->url(fn(): string => PathwayResource::getUrl('view', ['record' => 'essential-research-methods-for-agroecology'])),
                ]),

                Section::make('')
                    ->schema([
                        TextEntry::make('description')
                            ->label(''),

                        // TextEntry::make('previous')
                        //             ->label('Before completing this module, you should be familiar with the contents of the following modules:')
                        //             ->listWithLineBreaks()
                        //             ->bulleted()
                        //             ->hidden(fn(Module $record) => $record->first ===1)
                        //             ->formatStateUsing(fn (string $state): string => strtoupper($state)),

                        ListRepeatableEntry::make('previous.name')
                                            ->label('Before completing this module, you should be familiar with the contents of the following modules:')
                                            ->contained(false)
                                            ->extraAttributes(['class' => 'space-y-0'])
                                            ->hidden(fn(Module $record) => $record->first ===1)
                                            ->schema([
                                                TextEntry::make('previous.name')->hiddenLabel()->columnSpanFull()
                                                ->formatStateUsing(fn($state): HtmlString => new HtmlString("<li class='list-disc list-inside'> {$state}</li>"))
                                                    ->extraAttributes(['class' => 'y-0']),
                                            ]),

                        TextEntry::make('competencies.name')
                            ->label('This module is linked to the following competencies:')
                            ->listWithLineBreaks()
                            ->bulleted(),

                    ]),

                Section::make('Activities')
                    ->schema([
                    ]),

                RepeatableEntry::make('sections')->label('')
                    ->schema([
                        TextEntry::make('name')
                            ->label('')
                            ->weight(FontWeight::Bold)
                            ->size(TextEntry\TextEntrySize::Large),
                        TextEntry::make('description')
                            ->label(''),

                        RepeatableEntry::make('activities')->label('')
                            ->schema([
                                // IconEntry::make('type')
                                //             ->color('stats4sd')
                                //             ->label('')
                                //             ->icon(fn (string $state): string => match ($state) {
                                //                 'document' => 'heroicon-m-document-duplicate',
                                //                 'website' => 'heroicon-m-link',
                                //                 'video' => 'heroicon-m-video-camera',
                                //                 'presentation' => 'heroicon-m-presentation-chart-bar',
                                //                 'picture' => 'heroicon-m-photo',
                                //                 'course' => 'heroicon-m-academic-cap',
                                //                 'other' => 'heroicon-m-ellipsis-horizontal-circle',
                                //             }),
                                TextEntry::make('name')
                                    ->label('')
                                    ->weight(FontWeight::Bold)
                                    ->size(TextEntry\TextEntrySize::Large)
                                    ->iconColor('stats4sd')
                                    ->icon(fn(Activity $record): string => match ($record->type) {
                                        'document' => 'heroicon-m-document-duplicate',
                                        'website' => 'heroicon-m-link',
                                        'video' => 'heroicon-m-video-camera',
                                        'presentation' => 'heroicon-m-presentation-chart-bar',
                                        'picture' => 'heroicon-m-photo',
                                        'course' => 'heroicon-m-academic-cap',
                                        'other' => 'heroicon-m-ellipsis-horizontal-circle',
                                    }),
                                TextEntry::make('description')
                                    ->label('')
                                    ->columnStart(1),
                                Actions::make([
                                    Action::make('open')
                                        ->label('Open')
                                        ->icon('heroicon-m-arrow-top-right-on-square')
                                        ->color('stats4sd')
                                        ->url('stats4sd.org'),
                                ]),
                                TextEntry::make('completion_status')
                                    ->label('')
                                    ->hidden(Auth::guest())
                                    ->icon(fn(string $state): string => match ($state) {
                                        'guest' => 'heroicon-m-arrow-right-end-on-rectangle',
                                        'Not Completed' => 'heroicon-m-exclamation-circle',
                                        'Completed' => 'heroicon-m-check-badge',
                                    }),
                                Actions::make([
                                    Action::make('mark_complete_guest')
                                        ->label('Mark complete ')
                                        ->icon('heroicon-m-pencil-square')
                                        ->iconPosition(IconPosition::After)
                                        ->color('darkblue')
                                        ->requiresConfirmation()
                                        ->modalHeading('Keep Track of Your Journey')
                                        ->modalIcon('heroicon-o-bookmark')
                                        ->modalIconColor('stats4sd')
                                        ->modalDescription('Save your progress with a free account.')
                                        ->modalAlignment(Alignment::Start)
                                        ->modalSubmitActionLabel('Login or Signup')
                                        ->modalCancelActionLabel('Continue without tracking')
                                        ->modalCancelAction(false)
                                        ->action(fn(Module $record) => redirect(ModuleResource::getUrl('view', ['record' => $record])))
                                        ->visible(Auth::guest()),
                                    Action::make('mark_complete')
                                        ->label('Mark complete ')
                                        ->icon('heroicon-m-pencil-square')
                                        ->iconPosition(IconPosition::After)
                                        ->color('darkblue')
                                        ->action(function (Activity $record) {
                                            $record->users()->attach(Auth::User()->id, ['is_complete' => 1]);
                                            $record->refresh();
                                            $record->users;
                                        })
                                        ->hidden(Auth::guest())
                                        ->visible(fn(Activity $record) => $record->completion_status != 'Completed'),
                                    Action::make('mark_incomplete')
                                        ->label('Mark incomplete ')
                                        ->badge()
                                        ->icon('heroicon-m-arrow-uturn-left')
                                        ->iconPosition(IconPosition::After)
                                        ->color('darkblue')
                                        ->action(function (Activity $record) {
                                            $record->users()->detach(Auth::User()->id);
                                            $record->refresh();
                                            $record->users;
                                        })
                                        ->hidden(Auth::guest())
                                        ->visible(fn(Activity $record) => $record->completion_status == 'Completed'),
                                ]),
                            ])->columns(4)
                    ]),

                Actions::make([
                    Action::make('return_path2')
                        ->label('Return to pathway')
                        ->icon('heroicon-m-arrow-long-left')
                        ->color('stats4sd')
                        ->url(fn(): string => PathwayResource::getUrl('view', ['record' => 'essential-research-methods-for-agroecology'])),
                    Action::make('mark_mod_complete_guest')
                        ->label('Mark module complete ')
                        ->icon('heroicon-m-pencil-square')
                        ->iconPosition(IconPosition::After)
                        ->color('darkblue')
                        ->requiresConfirmation()
                        ->modalHeading('Keep Track of Your Journey')
                        ->modalIcon('heroicon-o-bookmark')
                        ->modalIconColor('stats4sd')
                        ->modalDescription('Save your progress with a free account.')
                        ->modalAlignment(Alignment::Start)
                        ->modalSubmitActionLabel('Login or Signup')
                        ->modalCancelActionLabel('Continue without tracking')
                        ->modalCancelAction(false)
                        ->action(fn(Module $record) => redirect(ModuleResource::getUrl('view', ['record' => $record])))
                        ->visible(Auth::guest()),
                    Action::make('mark_mod_complete')
                        ->label('Mark module complete ')
                        ->icon('heroicon-m-pencil-square')
                        ->iconPosition(IconPosition::After)
                        ->color('darkblue')
                        ->action(function (Module $record) {
                                            $record->users()->attach(Auth::User()->id, ['is_complete' => 1]);
                                            $record->refresh();
                                            $record->users;
                                        })
                        ->visible(fn(Module $record) => $record->completion_status != 'Completed')
                        ->hidden(Auth::guest()),
                    Action::make('mark_mod_incomplete')
                        ->label('Mark module incomplete ')
                        ->icon('heroicon-m-arrow-uturn-left')
                        ->iconPosition(IconPosition::After)
                        ->color('darkblue')
                        ->badge()
                        ->action(function (Module $record) {
                                            $record->users()->detach(Auth::User()->id);
                                            $record->refresh();
                                            $record->users;
                                        })
                        ->visible(fn(Module $record) => $record->completion_status == 'Completed')
                        ->hidden(Auth::guest()),
                    Action::make('next_module')
                            ->label('Next module')
                            ->icon('heroicon-s-arrow-long-right')
                            ->color('stats4sd')
                            ->visible(fn (Module $record) => $record->completion_status == 'Completed')
                            ->url(function (Module $record) {
                                $next_record= $record->next->first();
                                return ModuleResource::getUrl('view', ['record' => $next_record]);
                            })
                ])->alignment(Alignment::Center),

            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('researchComponent.name')->wrap()->sortable(),
                Tables\Columns\TextColumn::make('name')->wrap()->sortable(),
                Tables\Columns\TextColumn::make('description')->wrap(),
                Tables\Columns\TextColumn::make('time_estimate')->suffix(' hours'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                // Tables\Actions\EditAction::make(),
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
            // 'index' => Pages\ListModules::route('/'),
            'view' => Pages\ViewModule::route('/{record}'),
            // 'create' => Pages\CreateModule::route('/create'),
            // 'edit' => Pages\EditModule::route('/{record}/edit'),
        ];
    }
}
