<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'activity_user')
                    ->withPivot('is_complete');
    }

    public function getCompletionStatusAttribute()
    {
        if(Auth::guest()) {
            $user ='guest';
        }
        else {
            $user = $this->users->where('id', Auth::user()->id);
        }

        if ($user=='guest') {
            $status = 'guest';
        }
        elseif ($user->isEmpty()) {
            $status = 'Not Completed';
        }
        elseif ($user->first()->pivot->is_complete === 1){
            $status = 'Completed';
        }

        return $status;
    }
    
}
