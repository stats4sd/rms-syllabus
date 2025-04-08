<?php

namespace App\Filament\App\Resources\PathwayResource\Pages;

use App\Models\Pathway;
use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;
use Filament\Resources\Pages\ListRecords;
use App\Filament\App\Resources\PathwayResource;

class ListPathways extends ListRecords
{
    protected static string $resource = PathwayResource::class;

    protected static string $view = 'filament.app.resources.pathways.list-pathways';

    public function getTitle(): string
    {
        return '';
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }

    public function getMaxContentWidth(): MaxWidth
    {
        return MaxWidth::Full;
    }
    
    public function getPathways()
    {
        return Pathway::all();
    }

}