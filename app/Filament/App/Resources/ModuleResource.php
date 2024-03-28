<?php

namespace App\Filament\App\Resources;

use App\Filament\Infolists\Actions\LoginPromptAction;
use App\Models\Pathway;
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
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\RepeatableEntry;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\App\Resources\ModuleResource\Pages;
use App\Filament\Infolists\Components\ListRepeatableEntry;
use App\Filament\App\Resources\ModuleResource\RelationManagers;
use Illuminate\Support\HtmlString;

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
                                                if ($next_record->view_status === 'Not Viewed' && $next_record->completion_status != 'Completed') {
                                                    $next_record->users()->attach(auth()->id(), ['viewed' => 1, 'is_complete' => 0]);
                                                }
                                                elseif ($next_record->view_status === 'Not Viewed' && $next_record->completion_status === 'Completed') {
                                                    $next_record->users()->updateExistingPivot(auth()->id(), ['viewed' => 1]);
                                                }
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

                        ListRepeatableEntry::make('previous')
                                            ->label('Before completing this module, you should be familiar with the contents of the following modules:')
                                            ->contained(false)
                                            ->extraAttributes(['class' => 'space-y-0'])
                                            ->hidden(fn(Module $record) => $record->first ===1)
                                            ->schema([
                                                TextEntry::make('name')->hiddenLabel()->columnSpanFull()
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
                                    Action::make('open_guest')
                                        ->label('Open')
                                        ->icon('heroicon-m-arrow-top-right-on-square')
                                        ->color('stats4sd')
                                        ->url('stats4sd.org')
                                        ->openUrlInNewTab()
                                        ->visible(Auth::guest()),
                                    Action::make('open')
                                        ->label('Open')
                                        ->icon('heroicon-m-arrow-top-right-on-square')
                                        ->color('stats4sd')
                                        ->action(function (Activity $record) {
                                                            if ($record->link_status === 'Not Opened' && $record->completion_status != 'Completed') {
                                                                $record->users()->attach(auth()->id(), ['link_opened' => 1, 'is_complete' => 0]);
                                                                $record->refresh();
                                                                $record->users;
                                                            }
                                                            elseif ($record->link_status === 'Not Opened' && $record->completion_status === 'Completed') {
                                                                $record->users()->updateExistingPivot(auth()->id(), ['link_opened' => 1]);
                                                                $record->refresh();
                                                                $record->users;
                                                            }
                                                            return url('/stats4sd.org');
                                                        })
                                        ->hidden(Auth::guest()),
                                ]),

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
                                    LoginPromptAction::make('Mark as Complete'),
                                    Action::make('mark_complete')
                                        ->label('Mark complete ')
                                        ->icon('heroicon-m-pencil-square')
                                        ->iconPosition(IconPosition::After)
                                        ->color('darkblue')
                                        ->action(function (Activity $record) {
                                                            if($record->completion_status === 'In Progress') {
                                                                $record->users()->updateExistingPivot(auth()->id(), ['is_complete' => 1]);
                                                            }
                                                            elseif($record->completion_status === 'Not Started') {
                                                                $record->users()->attach(auth()->id(), ['link_opened' => 0, 'is_complete' => 1]);
                                                            }
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
                                                            if($record->link_status==='Opened') {
                                                                $record->users()->updateExistingPivot(auth()->id(), ['is_complete' => 0]);
                                                            }
                                                            else {
                                                                $record->users()->detach(auth()->id());
                                                            }
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
                   LoginPromptAction::make('Mark Module Complete'),
                    Action::make('mark_mod_complete')
                        ->label('Mark module complete ')
                        ->icon('heroicon-m-pencil-square')
                        ->iconPosition(IconPosition::After)
                        ->color('darkblue')
                        ->action(function (Module $record) {
                                            if($record->view_status==='Viewed') {
                                                $record->users()->updateExistingPivot(auth()->id(), ['is_complete' => 1]);
                                            }
                                            else {
                                                $record->users()->attach(auth()->id(), ['is_complete' => 1, 'viewed' => 1]);
                                            }
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
                                            if($record->view_status==='Viewed') {
                                                $record->users()->updateExistingPivot(auth()->id(), ['is_complete' => 0]);
                                            }
                                            else {
                                                $record->users()->detach(auth()->id());
                                            }
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
                                            if ($next_record->view_status === 'Not Viewed' && $next_record->completion_status != 'Completed') {
                                                $next_record->users()->attach(auth()->id(), ['viewed' => 1, 'is_complete' => 0]);
                                            }
                                            elseif ($next_record->view_status === 'Not Viewed' && $next_record->completion_status === 'Completed') {
                                                $next_record->users()->updateExistingPivot(auth()->id(), ['viewed' => 1]);
                                            }
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
