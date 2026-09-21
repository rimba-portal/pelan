<?php

declare(strict_types=1);

namespace Rimba\Floorplan\Models\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Rimba\Floorplan\Models\LocationAssignment;

trait HasLocationAssignments
{
    public function locationAssignments(): MorphMany
    {
        return $this->morphMany(LocationAssignment::class, 'assignable');
    }
}
