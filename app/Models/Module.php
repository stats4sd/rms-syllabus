<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Spatie\MediaLibrary\InteractsWithMedia;
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
        'time_estimate'
    ];

    protected $casts = [
        'id' => 'integer',
        'research_component_id' => 'integer',
    ];

    public $translatable = [
        'name',
        'description',
    ];

    public function sections(): HasMany
    {
        return $this->hasMany(Section::class);
    }

    public function competencies(): MorphToMany
    {
        return $this->morphToMany(Competency::class, 'competencyable');
    }

    public function researchComponent(): BelongsTo
    {
        return $this->belongsTo(ResearchComponent::class);
    }

    public function pathways(): BelongsToMany
    {
        return $this->belongsToMany(Pathway::class, 'module_pathway')
                    ->withPivot('module_order');
    }
    
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'module_user')
                    ->withPivot('is_complete');
    }

    public function getCompletionStatusAttribute()
    {
        // GET USER

        if(Auth::guest()) {
            $user ='guest';
        }
        else {
            $user = $this->users->where('id', Auth::user()->id);
        }


        // GET STATUS

        if ($user=='guest') {
            $status = 'guest';
        }

        elseif ($user->isEmpty()) {
            
            // CHECK IF AT LEAST ONE ACTIVITY HAS BEEN COMPLETED
            $activity_complete = 0;
            $sections = $this->sections->all();

            foreach ($sections as $section) {
                $activities = $section->activities->all();

                foreach($activities as $activity) {

                    $activity_user = $activity->users->where('id', Auth::user()->id);

                    if(!$activity_user->isEmpty()){
                        if($activity_user->first()->pivot->is_complete === 1) {
                            $activity_complete = 1;
                        }
                    }

                }
            }

            if($activity_complete===1) {
                $status = 'In Progress';
            }
            else {
                $status = 'Not Started';
            }
        }

        elseif ($user->first()->pivot->is_complete === 1){
            $status = 'Completed';
        }

        return $status;
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

}
