<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlotAllocation extends Model
{
    protected $guarded = [];

    protected $casts = [
        'allocated_at'    => 'datetime',
        'allocation_date' => 'datetime',
        'amount_paid'     => 'float',
    ];

    /* ------------------------------------------------------------------ */
    /* Scopes                                                               */
    /* ------------------------------------------------------------------ */

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    /* ------------------------------------------------------------------ */
    /* Relationships                                                        */
    /* ------------------------------------------------------------------ */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function plot()
    {
        return $this->belongsTo(Plot::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
