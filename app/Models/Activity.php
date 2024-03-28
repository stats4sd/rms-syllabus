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
        'type',
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
                    ->withPivot('is_complete', 'link_opened');
    }

    public function getLinkStatusAttribute()
    {
        if(auth()->check()) {
           $user = $this->users->find(auth()->id());

           if($user && $user->pivot->link_opened === 1) {
            return 'Opened';
           }
        }

        return 'Not Opened';
    }

    public function getCompletionStatusAttribute()
    {
        if(Auth::guest()) {
            return 'guest';
        }

        $user = $this->users->find(auth()->id());

        if (!$user || ($user->pivot->link_opened === 0 & $user->pivot->is_complete === 0)) {
            return 'Not Started';
        }
        elseif ($user->pivot->link_opened === 1 & $user->pivot->is_complete === 0){
            return 'In Progress';
        }
        elseif ($user->pivot->is_complete === 1){
            return 'Completed';
        }

        return 'unknown';
    }
    
}
