<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Competency extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $fillable = [
        'name',
        'description',
    ];

    protected $casts = [
        'id' => 'integer',
    ];

    public $translatable = [
        'name',
        'description',
    ];

    public function modules(): MorphToMany
    {
        return $this->morphedByMany(Module::class, 'competencyable');
    }

    public function competencyCategories(): BelongsToMany
    {
        return $this->belongsToMany(CompetencyCategory::class, 'competency_category_competency');
    }
}
