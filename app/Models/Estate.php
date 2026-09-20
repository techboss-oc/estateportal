<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estate extends Model
{
    protected $guarded = [];

    /* ------------------------------------------------------------------ */
    /* Relationships                                                        */
    /* ------------------------------------------------------------------ */

    public function plots()
    {
        return $this->hasMany(Plot::class);
    }

    public function allocations()
    {
        return $this->hasManyThrough(PlotAllocation::class, Plot::class);
    }

    public function activeAllocations()
    {
        return $this->hasManyThrough(PlotAllocation::class, Plot::class)
            ->where('plot_allocations.status', 'active');
    }

    /* ------------------------------------------------------------------ */
    /* Helpers                                                              */
    /* ------------------------------------------------------------------ */

    public function getLayoutUrlAttribute(): ?string
    {
        return $this->layout_file ? asset('storage/' . $this->layout_file) : null;
    }

    public function plotCountByStatus(): array
    {
        return $this->plots->groupBy('status')->map->count()->toArray();
    }
}
