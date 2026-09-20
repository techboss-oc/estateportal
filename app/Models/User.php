<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    /* ------------------------------------------------------------------ */
    /* Accessors                                                            */
    /* ------------------------------------------------------------------ */

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    /* ------------------------------------------------------------------ */
    /* Relationships                                                        */
    /* ------------------------------------------------------------------ */

    public function plotAllocations()
    {
        return $this->hasMany(PlotAllocation::class);
    }

    public function activeAllocations()
    {
        return $this->hasMany(PlotAllocation::class)->where('status', 'active');
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    /* ------------------------------------------------------------------ */
    /* Helpers                                                              */
    /* ------------------------------------------------------------------ */

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Returns a collection of unique Estates the user has active plot allocations in.
     */
    public function estatesWithAllocations()
    {
        return Estate::whereHas('plots.plotAllocations', function ($q) {
            $q->where('user_id', $this->id)->where('status', 'active');
        })->with(['plots' => function ($q) {
            $q->whereHas('plotAllocations', fn($q2) => $q2->where('user_id', $this->id)->where('status', 'active'));
        }])->get();
    }
}
