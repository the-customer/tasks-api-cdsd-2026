<?php

namespace App\Policies;

use App\Enums\TaskRole;
use App\Enums\TaskVisibility;
use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TaskPolicy
{

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Task $task): bool
    {
        if($task->visibility === TaskVisibility::PUBLIC)
            return true;
        if(!$user)
            return false;
        if($task->isOwner($user))
            return true;
        return in_array($task->roleOf($user)?->value,TaskRole::cases());
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Task $task): bool
    {
        $role = $task->roleOf($user);
        return in_array($role?->value,[TaskRole::EDITOR->value,TaskRole::OWNER->value,TaskRole::DELETER->value]);
    }


    public function share(User $user, Task $task): bool
    {
        return $task->isOwner($user);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Task $task): bool
    {
        $role = $task->roleOf($user);
        return in_array($role?->value,[TaskRole::OWNER->value,TaskRole::DELETER->value]);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function changeVisibility(User $user, Task $task): bool
    {
        return $task->isOwner($user);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function archive(User $user, Task $task): bool
    {
        $role = $task->roleOf($user);
        return in_array($role?->value,[TaskRole::OWNER->value,TaskRole::DELETER->value]);
    }
}
