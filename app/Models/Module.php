<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;

class Module extends Model implements HasMedia
{
    use HasFactory;
    use HasTranslations;
    use InteractsWithMedia;

    protected $fillable = [
        'name',
        'description',
        'research_component_id',
    ];

    protected $casts = [
        'id' => 'integer',
        'research_component_id' => 'integer',
    ];

    public $translatable = [
        'name',
        'description',
    ];

    public function sections(): HasMany
    {
        return $this->hasMany(Section::class);
    }

    public function competencies(): MorphToMany
    {
        return $this->morphToMany(Competency::class, 'competencyable');
    }

    public function researchComponent(): BelongsTo
    {
        return $this->belongsTo(ResearchComponent::class);
    }

    public function pathways(): BelongsToMany
    {
        return $this->belongsToMany(Pathway::class, 'module_pathway', 'pathway_id', 'module_id')
                    ->withPivot('module_order');
    }
    
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'module_user', 'user_id', 'module_id')
                    ->withPivot('is_complete');
    }
}
