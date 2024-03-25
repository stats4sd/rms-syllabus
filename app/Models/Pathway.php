<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Pathway extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $fillable = [
        'name',
        'description',
    ];

    public $translatable = [
        'name',
        'description',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function modules(): BelongsToMany
    {
        return $this->belongsToMany(Module::class, 'module_pathway')
                    ->withPivot('module_order')
                    ->orderBy('module_order');
    }
}
