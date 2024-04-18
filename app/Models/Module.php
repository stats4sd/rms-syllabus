<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Spatie\MediaLibrary\InteractsWithMedia;
use App\Filament\App\Resources\ModuleResource;
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
        'slug'
    ];

    protected $casts = [
        'id' => 'integer',
        'research_component_id' => 'integer',
    ];

    public $translatable = [
        'name',
        'description',
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

    public function getFirstAttribute() {  // pass in the pathway id instead of 1 when we upgrade to multiple pathways
        
        $mod_pathway = $this->pathways->where('id', 1)->first();

        return $mod_pathway->pivot->module_order === 1 ? 1 : 0;

    }

    public function getLastAttribute() {  // pass in the pathway id instead of 1 when we upgrade to multiple pathways

        $mod_pathway = $this->pathways->where('id', 1)->first();
        
        $max = Pathway::find(1)->modules->collect()->map(function ($module) {
            return $module->pivot->module_order;
        })->max();

        return $mod_pathway->pivot->module_order === $max ? 1 : 0;

    }

    public function getPreviousAttribute() {  // pass in the pathway id instead of 1 when we upgrade to multiple pathways
        if ($this->first===0) {
            $this_mod_order = $this->pathways->where('id', 1)->first()->pivot->module_order;
            $prev_modules = Pathway::find(1)->modules->collect()->where('pivot.module_order', '<', $this_mod_order);
            return $prev_modules;
        }
    }

    public function getNextAttribute() {  // pass in the pathway id instead of 1 when we upgrade to multiple pathways
        if ($this->last===0) {
            $this_mod_order = $this->pathways->where('id', 1)->first()->pivot->module_order;
            $next_order = $this_mod_order + 1;
            $next_module = Pathway::find(1)->modules->collect()->where('pivot.module_order', $next_order);
            return $next_module;
        }
    }

    public function nextRecordUrl()
    {
        $nextRecord_collection = $this->next;

        if($nextRecord_collection) {
            $nextRecord = $nextRecord_collection->first();

            if ($nextRecord->view_status === 'Not Viewed') {
                if ($nextRecord->completion_status != 'Completed') {
                    $nextRecord->users()->attach(auth()->id(), ['viewed' => 1, 'is_complete' => 0]);
                } elseif ($nextRecord->completion_status === 'Completed') {
                    $nextRecord->users()->updateExistingPivot(auth()->id(), ['viewed' => 1]);
                }
            }
        
        return ModuleResource::getUrl('view', ['record' => $nextRecord]);

        }
    }

}
