<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Pathway extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    public $translatable = [
        'name',
        'description',
    ];

    public function modules(): BelongsToMany
    {
        return $this->belongsToMany(Module::class, 'module_pathway', 'module_id', 'pathway_id');
    }
}
