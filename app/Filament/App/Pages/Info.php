<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;
use Filament\Pages\Actions\Action;
use Filament\Support\Enums\MaxWidth;

class Info extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-information-circle';

    protected static string $view = 'filament.app.pages.info';

    protected static ?string $navigationLabel = 'Info';

    protected static ?string $title = 'Find Out More';

    public function getMaxContentWidth(): MaxWidth
    {
        return MaxWidth::Full;
    }
}
