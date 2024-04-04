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

    protected static ?string $title = '';


}
