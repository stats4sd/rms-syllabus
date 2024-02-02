<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Topic extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'label',
        'description',
        'heading_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'heading_id' => 'integer',
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
