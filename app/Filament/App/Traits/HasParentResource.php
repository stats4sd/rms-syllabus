<?php

namespace App\Filament\App\Traits;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
 
trait HasParentResource
{
    public Model|int|string|null $parent = null;
 
    public function bootHasParentResource(): void
    {
        if ($parent = (request()->route('parent') ?? request()->input('parent'))) {
            $parentResource = $this->getParentResource();
 
            $this->parent = $parentResource::resolveRecordRouteBinding($parent);
 
            if (!$this->parent) {
                throw new ModelNotFoundException();
            }
        }
    }
 
    public static function getParentResource(): string
    {
        $parentResource = static::getResource()::$parentResource;
 
        if (!isset($parentResource)) {
            throw new Exception('Parent resource is not set for '.static::class);
        }
 
        return $parentResource;
    }
 
}