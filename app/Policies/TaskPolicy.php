<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Task;

class TaskPolicy
{
    public function view(User $user, Task $task): bool
    {
        return $user->farm_id === $task->farm_id || $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->farm_id !== null && $user->role !== 'buyer';
    }

    public function update(User $user, Task $task): bool
    {
        return ($user->farm_id === $task->farm_id && $user->role !== 'buyer') || $user->isAdmin();
    }

    public function delete(User $user, Task $task): bool
    {
        return ($user->farm_id === $task->farm_id && $user->role === 'admin') || $user->isAdmin();
    }
}
