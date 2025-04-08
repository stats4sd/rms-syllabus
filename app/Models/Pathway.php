<?php

namespace App\Models;

use Filament\Panel;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Filament\Models\Contracts\HasTenants;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Pathway extends Model implements HasTenants
{
    use HasFactory;
    use HasTranslations;

    protected $fillable = [
        'name',
        'description',
        'slug',
        'status',
        'creator_id'
    ];

    public $translatable = [
        'name',
        'description',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function canAccessTenant(Model $tenant): bool
    {
        return true;
    }

    public function getTenants(Panel $panel):  array|Collection
    {
        return self::all();
    }

    public function modules(): BelongsToMany
    {
        return $this->belongsToMany(Module::class, 'module_pathway')
                    ->withPivot('module_order')
                    ->orderBy('module_order');
    }

    public function modulePathways(): HasMany
    {
        return $this->hasMany(ModulePathway::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
}
