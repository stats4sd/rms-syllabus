<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Module extends Model
{
    use HasFactory;
    use HasTranslations;

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
}
