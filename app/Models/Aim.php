<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Aim extends Model
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

    public function items(): MorphToMany
    {
        return $this->morphedByMany(Item::class, 'aimable');
    }
}
