<?php

declare(strict_types=1);

namespace Rimba\Floorplan\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
// <--- MOVED TO THE TOP HERE
use Illuminate\Database\Eloquent\Relations\HasMany;
use Rimba\Organization\Models\OrgCorp;

#[Fillable([
    'parent_id',
    'org_corp_id',
    'type',
    'name',
    'code',
    'description',
    'attributes',
])]
class Location extends Model
{
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'parent_id' => 'integer',
            'org_corp_id' => 'integer',
            'attributes' => 'array',
        ];
    }

    /**
     * Get the full path string from the root ancestor down to the immediate parent.
     * Returns null if this location has no parent (is already root).
     */
    protected function parentPath(): Attribute
    {
        return Attribute::make(get: function () {
            if (! $this->parent_id) {
                return null;
            }

            $ancestors = collect();
            $current = $this->parent;
            // Traverse upwards until we reach the root
            while ($current) {
                $ancestors->push($current->code);
                $current = $current->parent;
            }

            // Reverse so it reads left-to-right: Grandparent > Parent
            return $ancestors->reverse()->implode(' > ');
        });
    }

    public function children(): HasMany
    {
        return $this->hasMany(Location::class, 'parent_id');
    }

    public function locationAssignments(): HasMany
    {
        return $this->hasMany(LocationAssignment::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function orgCorp(): BelongsTo
    {
        return $this->belongsTo(OrgCorp::class);
    }
}
