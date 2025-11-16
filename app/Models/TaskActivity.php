<?php

namespace App\Models;

use App\Enums\TaskActivity as ActivityType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'actor_id',
        'type',
        'meta'
    ];

    protected $casts = [
        'type' => ActivityType::class,
        'meta' => 'array'
    ];

    //
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function actor()
    {
        return $this->belongsTo(User::class, 'actor_id');
    }

    //
    public function label():string
    {
        return match ($this->type){
            ActivityType::CREATED => 'Tâche créée',
            ActivityType::UPDATED => 'Tâche modifiée',
            ActivityType::ARCHIVED => 'Tâche archivée',
            ActivityType::STATUS_CHANGER => 'Statut modifié',
            ActivityType::RESTORED => 'Tâche restaurée',
            ActivityType::SHARED_WITH => 'Tâche partagée avec un utilisateur',
            ActivityType::INVITATION_ACCEPTED => 'Invitation acceptée',
            ActivityType::VISIBILITY_CHANGED => 'Visibilité modifiée',
            default => ucfirst($this->type->value)
        };
    }

    public function metaValue(string $key, $default = null):string
    {
        return $this->meta[$key] ?? $default;
    }
}
