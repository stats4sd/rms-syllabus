<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Topic extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $fillable = [
        'label',
        'description',
        'heading_id',
    ];

    protected $casts = [
        'id' => 'integer',
        'heading_id' => 'integer',
    ];

    public $translatable = [
        'label',
        'description',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }

    public function subTopics(): HasMany
    {
        return $this->hasMany(SubTopic::class);
    }

    public function heading(): BelongsTo
    {
        return $this->belongsTo(Heading::class);
    }
}
