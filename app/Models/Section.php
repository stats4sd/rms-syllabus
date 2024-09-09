<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Section extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $fillable = [
        'name',
        'description',
        'module_id',
        'creator_id',
        'time_estimate',
        'guidance'
    ];

    protected $casts = [
        'id' => 'integer',
        'module_id' => 'integer',
    ];

    public $translatable = [
        'name',
        'description',
        'time_estimate',
        'guidance'
    ];

    public function activities(): BelongsToMany
    {
        return $this->belongsToMany(Activity::class);
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
}
