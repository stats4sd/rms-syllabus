<?php

namespace App\Filament\App\Pages;

use App\Models\Pathway;
use Filament\Pages\Page;
use Filament\Pages\Actions\Action;
use Filament\Support\Enums\IconPosition;
use App\Filament\App\Resources\PathwayResource;

class Home extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.app.pages.home';

    protected static ?string $navigationLabel = 'Home';

    protected static ?string $title = 'Research Methods Syllabus';

    protected function getHeaderActions(): array
    {
        return [
            // Actions\EditAction::make(),
            Action::make('beginLearning')
                    ->label('Begin learning')
                    ->icon('heroicon-o-arrow-long-right')
                    ->iconPosition(IconPosition::After)
                    ->color('gray')
                    ->url(fn (): string => PathwayResource::getUrl('view', ['record' => 'essential-research-methods-for-agroecology'])),
            Action::make('findOutMore')
                    ->label('Find out more')
                    ->icon('heroicon-o-light-bulb')
                    ->iconPosition(IconPosition::After)
                    ->color('gray')
                    ->url(Info::getUrl()),
        ];
    }

}
