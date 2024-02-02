<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubTopic extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $fillable = [
        'topic_id',
        'label',
        'description',
    ];

    protected $casts = [
        'id' => 'integer',
        'topic_id' => 'integer',
    ];

    public $translatable = [
        'label',
        'description',
    ];

    public function aims(): HasMany
    {
        return $this->hasMany(Aim::class);
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }
}
