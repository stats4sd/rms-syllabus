<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Trove extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $table = 'troves';
    protected $connection = 'trove_mysql';

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.connections.trove_mysql.database') . '.' . $this->table;
        parent::__construct($attributes);
    }

    protected $translatable = [
        'title',
    ];

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }
}
