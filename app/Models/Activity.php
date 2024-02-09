<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class Activity extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $fillable = [
        'name',
        'description',
        'section_id',
        'trove_id',
    ];

    protected $casts = [
        'id' => 'integer',
        'section_id' => 'integer',
        'trove_id' => 'integer',
    ];

    public $translatable = [
        'name',
        'description',
    ];

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function trove(): BelongsTo
    {
        return $this->belongsTo(Trove::class);
    }
}
