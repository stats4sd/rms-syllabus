<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;
use Filament\Pages\Actions\Action;
use Filament\Support\Enums\MaxWidth;

class Download extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';

    protected static string $view = 'filament.app.pages.download';

    protected static ?string $navigationLabel = 'Download';

    protected static ?string $title = 'Download my pathway (PDF)';

    public function getMaxContentWidth(): MaxWidth
    {
        return MaxWidth::Full;
    }
}
