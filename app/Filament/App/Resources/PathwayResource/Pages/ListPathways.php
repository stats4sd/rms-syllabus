<?php

namespace App\Filament\App\Resources\PathwayResource\Pages;

use Filament\Support\Enums\MaxWidth;
use Filament\Resources\Pages\ListRecords;
use App\Filament\App\Resources\PathwayResource;

class ListPathways extends ListRecords
{
    protected static string $resource = PathwayResource::class;

    public function getHeader(): ?\Illuminate\Contracts\View\View
    {
        return view('filament.app.resources.pathways.pathways_list_header');
    }

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

}
