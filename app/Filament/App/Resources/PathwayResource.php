<?php

namespace App\Filament\App\Resources;

use App\Filament\Infolists\Actions\LoginPromptAction;
use App\Filament\Infolists\Components\SpatieMediaLibraryImageEntryInRepeater;
use Filament\Forms;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Split;
use Filament\Tables;
use App\Models\Module;
use App\Models\Pathway;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\FontWeight;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Actions;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\RepeatableEntry;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\App\Resources\PathwayResource\Pages;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use App\Filament\App\Resources\PathwayResource\RelationManagers;

class PathwayResource extends Resource
{
    protected static ?string $model = Pathway::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationLabel = 'Pathway';

    public static function getNavigationItems(): array
    {
        return [];
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
                RepeatableEntry::make('modules')->label('')
                    ->schema([
                        Section::make('')
                            ->schema([
                                Grid::make(3)
                                ->schema([

                                    SpatieMediaLibraryImageEntryInRepeater::make('cover_image')
                                        ->label('')
                                        ->collection('module_cover')
                                        ->size(300)
                                        ->alignEnd()
                                        ->circular(),


                                    Grid::make(1)
                                        ->schema([
                                            TextEntry::make('name')
                                                ->label('')
                                                ->color('stats4sd')
                                                ->size(TextEntry\TextEntrySize::Large)
                                                ->weight(FontWeight::Bold),
                                            TextEntry::make('time_estimate')
                                                ->label('')
                                                ->icon('heroicon-m-clock')
                                                ->prefix('Est. duration: ')
                                                ->suffix(' hours'),
                                            TextEntry::make('description')->label(''),
                                            TextEntry::make('researchComponent.name')
                                                ->label('')
                                                ->badge()
                                                ->color('darkblue'),
                                            TextEntry::make('completion_status')
                                                ->label('')
                                                ->color('stats4sd')
                                                ->badge()
                                                ->hidden(Auth::guest())
                                                ->visible(fn(Module $record) => $record->completion_status != 'Not Started'),
                                            Actions::make([
                                                LoginPromptAction::make('view')
                                                ->cancelRedirectsTo(fn(Module $record) => ModuleResource::getUrl('view', ['record' => $record])),
                                                Action::make('view')
                                                    ->label('View')
                                                    ->icon('heroicon-m-arrow-long-right')
                                                    ->color('stats4sd')
                                                    ->action(function (Module $record) {
                                                        if ($record->view_status === 'Not Viewed' && $record->completion_status != 'Completed') {
                                                            $record->users()->attach(auth()->id(), ['viewed' => 1, 'is_complete' => 0]);
                                                        } elseif ($record->view_status === 'Not Viewed' && $record->completion_status === 'Completed') {
                                                            $record->users()->updateExistingPivot(auth()->id(), ['viewed' => 1]);
                                                        }
                                                        redirect(ModuleResource::getUrl('view', ['record' => $record]));
                                                    })
                                                    ->hidden(Auth::guest()),
                                            ])
                                                ->alignRight(),
                                        ])
                                    ->columnSpan(2)

                                ]),
                                   // ->columns(3),
                            ])
                    ])
                ->columns(1)
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),

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
            // 'index' => Pages\ListPathways::route('/'),
            'view' => Pages\ViewPathway::route('/{record}'),
            // 'create' => Pages\CreatePathway::route('/create'),
            // 'edit' => Pages\EditPathway::route('/{record}/edit'),
        ];
    }
}
