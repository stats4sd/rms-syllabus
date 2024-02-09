<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CompetencyCategory extends Model
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

    public function competencies(): BelongsToMany
    {
        return $this->belongsToMany(Competency::class, 'competency_category_competency');
    }
}
