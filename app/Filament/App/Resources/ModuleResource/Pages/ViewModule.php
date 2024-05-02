<?php

namespace App\Filament\App\Resources\ModuleResource\Pages;

use Filament\Actions;
use App\Models\Pathway;
use Filament\Support\Enums\MaxWidth;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\App\Resources\ModuleResource;
use App\Filament\App\Traits\HasParentResource;
use App\Filament\App\Resources\PathwayResource;

class ViewModule extends ViewRecord
{
    use HasParentResource;

    protected static string $resource = ModuleResource::class;

    protected static string $view = 'filament.app.resources.modules.module_view';

    public function getBreadcrumbs(): array
    {
        return [];
    }

    public function getTitle(): string
    {
        return '';
    }

    public function getMaxContentWidth(): MaxWidth
    {
        return MaxWidth::Full;
    }

    public function firstInPathway() {
        
        // get the pathway
        $pathway = $this->parent;

        // get the modules order in the pathway
        $this_mod_order = $this->getRecord()->pathways->where('id', $pathway->id)->first()->pivot->module_order;

        // find the module with the lowest order number
        $min_module_order = $pathway->modules->collect()->map(function ($module) {
            return $module->pivot->module_order;
        })->min();

        // check if module is the last in the pathway
        $is_first = $this_mod_order === $min_module_order ? 1 : 0;

        return $is_first;

    }

    public function lastInPathway() {

        // get the pathway
        $pathway = $this->parent;

        // get the modules order in the pathway
        $this_mod_order = $this->getRecord()->pathways->where('id', $pathway->id)->first()->pivot->module_order;

        // get the max module order in the pathway
        $max_module_order = $pathway->modules->collect()->map(function ($module) {
            return $module->pivot->module_order;
        })->max();

        // check if module is the last in the pathway
        $is_last = $this_mod_order === $max_module_order ? 1 : 0;
        
        return $is_last;

    }

    public function nextInPathway() { 

        // check that the module is not last in the pathway
        if ($this->lastInPathway()===0) {

            // get the pathway
            $pathway = $this->parent;

            // get the modules order in the pathway
            $this_mod_order = $this->getRecord()->pathways->where('id', $pathway->id)->first()->pivot->module_order;

            // get the next module in the pathway
            $next_module = $pathway->modules->collect()->where('pivot.module_order', $this_mod_order + 1);

            return $next_module;

        }
    }

    public function nextModuleUrl()
    {
        $next_module_collection = $this->nextInPathway();

        if($next_module_collection) {
            $next_module = $next_module_collection->first();

            if ($next_module->view_status === 'Not Viewed') {

                if ($next_module->completion_status != 'Completed') {
                    $next_module->users()->attach(auth()->id(), ['viewed' => 1, 'is_complete' => 0]);
                }

                elseif ($next_module->completion_status === 'Completed') {
                    $next_module->users()->updateExistingPivot(auth()->id(), ['viewed' => 1]);
                }
            }

            return PathwayResource::getUrl('modules.view', ['record' => $next_module, 'parent' => $this->parent]);

        }
    }

    public function previousModules() { 

        // check that the module is not last in the pathway
        if ($this->firstInPathway()===0) {

            // get the pathway
            $pathway = $this->parent;

            // get the modules order in the pathway
            $this_mod_order = $this->getRecord()->pathways->where('id', $pathway->id)->first()->pivot->module_order;

            // get modules in the pathway before the current module
            $prev_modules = $pathway->modules->collect()->where('pivot.module_order', '<', $this_mod_order);
            
            return $prev_modules;
        }
    }
}
