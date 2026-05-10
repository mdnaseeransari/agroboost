<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Crop;

class CropPolicy
{
    public function view(User $user, Crop $crop): bool
    {
        return $user->farm_id === $crop->farm_id || $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->farm_id !== null && $user->role !== 'viewer';
    }

    public function update(User $user, Crop $crop): bool
    {
        return ($user->farm_id === $crop->farm_id && $user->role !== 'viewer') || $user->isAdmin();
    }

    public function delete(User $user, Crop $crop): bool
    {
        return ($user->farm_id === $crop->farm_id && $user->role === 'admin') || $user->isAdmin();
    }
}
