<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plot extends Model
{
    protected $guarded = [];

    protected $casts = [
        'coordinates' => 'array',
        'size'        => 'float',
        'price'       => 'float',
    ];

    /* ------------------------------------------------------------------ */
    /* Relationships                                                        */
    /* ------------------------------------------------------------------ */

    public function estate()
    {
        return $this->belongsTo(Estate::class);
    }

    public function plotAllocations()
    {
        return $this->hasMany(PlotAllocation::class);
    }

    public function activeAllocation()
    {
        return $this->hasOne(PlotAllocation::class)->where('status', 'active');
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    /* ------------------------------------------------------------------ */
    /* Helpers                                                              */
    /* ------------------------------------------------------------------ */

    public function isAvailable(): bool
    {
        return in_array($this->status, ['available', 'reserved']);
    }
}
