<?php

namespace App\Filament\App\Resources;

use Filament\Forms;
use Filament\Tables;
use Pages\ViewModule;
use App\Models\Module;
use App\Models\Pathway;
use App\Models\Activity;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Auth;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\FontWeight;
use Filament\Infolists\Components\Grid;
use Filament\Support\Enums\IconPosition;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Actions;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use App\Filament\App\Resources\PathwayResource;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\RepeatableEntry;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\App\Resources\ModuleResource\Pages;
use App\Filament\App\Infolists\Actions\LoginPromptAction;
use App\Filament\App\Infolists\Components\ListRepeatableEntry;
use App\Filament\App\Infolists\Actions\LoginPromptWithFormAction;
use App\Filament\App\Resources\ModuleResource\RelationManagers;

class ModuleResource extends Resource
{
    protected static ?string $model = Module::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static string $parentResource = PathwayResource::class;

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
                                    })
                                    ->columnSpan(2),
                                TextEntry::make('description')
                                    ->label('')
                                    ->columnStart(1)
                                    ->columnSpan(2),
                                Actions::make([
                                    Action::make('open_guest')
                                        ->label('Open')
                                        ->icon('heroicon-m-arrow-top-right-on-square')
                                        ->color('stats4sd')
                                        ->url(function (Activity $record) {
                                            $trove = $record->trove;
                                            return 'https://stats4sd.org/resources/' . $trove->slug;
                                        })
                                        ->openUrlInNewTab()
                                        ->visible(Auth::guest()),
                                    
                                    Action::make('open_auth')
                                        ->label('Open')
                                        ->icon('heroicon-m-arrow-top-right-on-square')
                                        ->color('stats4sd')
                                        ->action(function (Activity $record) {
                                            if ($record->link_status === 'Not Opened' && $record->completion_status != 'Completed') {
                                                $record->users()->attach(auth()->id(), ['link_opened' => 1, 'is_complete' => 0]);
                                                $record->refresh();
                                                $record->users;
                                            } elseif ($record->link_status === 'Not Opened' && $record->completion_status === 'Completed') {
                                                $record->users()->updateExistingPivot(auth()->id(), ['link_opened' => 1]);
                                                $record->refresh();
                                                $record->users;
                                            }
                                           
                                            $trove = $record->trove;
                                            $url = 'https://stats4sd.org/resources/' . $trove->slug;
                                            redirect($url);
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
                    ])->extraAttributes(['class' => 'px-20']),

                Actions::make([
                    Action::make('return_path2')
                        ->label('Return to pathway')
                        ->icon('heroicon-m-arrow-long-left')
                        ->color('stats4sd')
                        ->url(fn(Pages\ViewModule $livewire): string => PathwayResource::getUrl('view', ['record' => $livewire->parent])),
                   LoginPromptWithFormAction::make('Mark Module Complete'),
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

}
