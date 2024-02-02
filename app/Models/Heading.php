<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Heading extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $fillable = [
        'label',
        'description',
    ];

    protected $casts = [
        'id' => 'integer',
    ];

    public $translatable = [
        'label',
        'description',
    ];

    public function topics(): HasMany
    {
        return $this->hasMany(Topic::class);
    }
}
