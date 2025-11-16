<?php

namespace App\Models;

use App\Enums\TaskRole;
use App\Enums\TaskVisibility;
use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'status',
        'visibility',
        'slug'
    ];
    //
    // protected $hidden = ['created_at']; // Pouer cacher la date de création lors de la reponse du serveur

    protected $casts = [
        'status' => TaskStatus::class,
        'visibility' => TaskVisibility::class,
        'archived_at' => 'datetime',
    ];


    //relations:

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('role')
            ->withTimestamps();
    }

    public function activities(): HasMany
    {
        return $this->hasMany(TaskActivity::class);
    }


    //Scope:
    public function scopeOwnedBy($q, int $userId)
    {
        return $q->where('owner_id', $userId);
    }

    public function scopeSharedWith($q, int $userId)
    {
        return $q->whereHas('members', fn($qq) => $qq->where('user_id', $userId));
    }

    public function scopePublic($q)
    {
        return $q->where('visibility', TaskVisibility::PUBLIC);
    }

    public function scopeActive($q)
    {
        return $q->whereNull('archived_at');
    }

    public function scopeArchived($q)
    {
        return $q->whereNotNull('archived_at');
    }

    //Helpers:
    public function isOwner(User $user): bool
    {
        return $this->owner_id === $user->id;
    }

    public function roleOf(User $user): ?TaskRole
    {
        $pivot = $this->members()->where('user_id', $user->id)->first()->pivot;

        return $pivot
            ?
            TaskRole::from($pivot->role)
            :
            ($this->isOwner($user) ? TaskRole::OWNER : null);
    }

}


