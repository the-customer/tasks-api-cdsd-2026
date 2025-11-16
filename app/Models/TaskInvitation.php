<?php

namespace App\Models;

use App\Enums\Enums\TaskRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TaskInvitation extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'role' => TaskRole::class,
        'expires_at' => 'datetime',
        'accepted_at' => 'datetime',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    //helper
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function isAccepted(): bool
    {
        return $this->accepted_at !== null;
    }

    public function markAsAccepted(): void
    {
        $this->accepted_at = now();
        $this->save();
    }

    //Gerener un token si non defini:
    public static function booted():void
    {
        static::creating(function ($invitation) {
            if (empty($invitation->token)) {
                // $invitation->token = Str::random(40);
                $invitation->token = Str::uuid();
            }
            if (empty($invitation->expires_at)) {
                $invitation->expires_at = now()->addDays(7);
            }
        });
    }
}
