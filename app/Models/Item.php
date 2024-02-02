<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $fillable = [
        'topic_id',
        'trove_id',
        'description',
    ];

    protected $casts = [
        'id' => 'integer',
        'topic_id' => 'integer',
        'trove_id' => 'integer',
    ];

    public $translatable = [
        'description',
    ];

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    public function trove(): BelongsTo
    {
        return $this->belongsTo(Trove::class);
    }
}
