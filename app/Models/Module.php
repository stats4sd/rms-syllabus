<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Spatie\MediaLibrary\InteractsWithMedia;
use App\Filament\App\Resources\ModuleResource;
use App\Filament\App\Resources\PathwayResource;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Module extends Model implements HasMedia
{
    use HasFactory;
    use HasTranslations;
    use InteractsWithMedia;

    protected $fillable = [
        'name',
        'description',
        'research_component_id',
        'time_estimate',
        'slug',
        'creator_id'
    ];

    protected $casts = [
        'id' => 'integer',
        'research_component_id' => 'integer',
    ];

    public $translatable = [
        'name',
        'description',
        'time_estimate',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function sections(): HasMany
    {
        return $this->hasMany(Section::class);
    }

    public function competencies(): MorphToMany
    {
        return $this->morphToMany(Competency::class, 'competencyable');
    }

    public function researchComponents(): MorphToMany
    {
        return $this->morphToMany(ResearchComponent::class, 'componentable');
    }

    public function pathways(): BelongsToMany
    {
        return $this->belongsToMany(Pathway::class, 'module_pathway')
                    ->withPivot('module_order');
    }
    
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'module_user')
                    ->withPivot('is_complete', 'viewed');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function modulePathways(): HasMany
    {
        return $this->hasMany(ModulePathway::class);
    }

    public function getViewStatusAttribute()
    {
        if(auth()->check()) {
           $user = $this->users->find(auth()->id());

           if($user && $user->pivot->viewed === 1) {
            return 'Viewed';
           }
        }

        return 'Not Viewed';
    }

    public function getCompletionStatusAttribute()
    {
        $user = Auth::guest() ? 'guest' : $this->users->find(Auth::id());
  
        if ($user === 'guest') {
            return 'guest';
        }

        if ($user && $user->pivot->is_complete === 1){
            return 'Completed';
        }

        if ($user && $user->pivot->viewed === 1){
            $activity_complete = $this->sections->flatMap->activities->contains(function ($activity) {
                return $activity->users->where('id', auth()->id())
                                        ->where('pivot.is_complete', 1)
                                        ->isNotEmpty();
            }) ? 1 : 0;

            return $activity_complete === 1 ? 'In Progress' : 'Not Started';
        }

        return 'Not Started';

    }

}
