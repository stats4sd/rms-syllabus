<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class ModulePathway  extends Pivot
{
    use HasFactory;

    public function pathway(): BelongsTo
    {
        return $this->belongsTo(Pathway::class);
    }
 
    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }
}
