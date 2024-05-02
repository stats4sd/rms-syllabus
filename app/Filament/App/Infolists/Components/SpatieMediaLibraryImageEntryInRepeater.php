<?php

namespace App\Filament\App\Infolists\Components;

use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Illuminate\Support\Arr;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Throwable;

class SpatieMediaLibraryImageEntryInRepeater extends SpatieMediaLibraryImageEntry
{
    public function getImageUrl(?string $state = null): ?string
    {
        $record = $this->getRecord();

        if (!$record) {
            return null;
        }

        /** @var ?Media $media */

        $media = $record->media->first(fn(Media $media): bool => $media->uuid === $state);

        if (!$media) {
            return null;
        }

        $conversion = $this->getConversion();

        if ($this->getVisibility() === 'private') {
            try {
                return $media->getTemporaryUrl(
                    now()->addMinutes(5),
                    $conversion ?? '',
                );
            } catch (Throwable $exception) {
                // This driver does not support creating temporary URLs.
            }
        }

        return $media->getAvailableUrl(Arr::wrap($conversion));
    }
}
