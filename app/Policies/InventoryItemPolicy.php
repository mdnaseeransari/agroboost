<?php

namespace App\Policies;

use App\Models\User;
use App\Models\InventoryItem;

class InventoryItemPolicy
{
    public function view(User $user, InventoryItem $item): bool
    {
        return $user->farm_id === $item->farm_id || $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->farm_id !== null && $user->role !== 'viewer';
    }

    public function update(User $user, InventoryItem $item): bool
    {
        return ($user->farm_id === $item->farm_id && $user->role !== 'viewer') || $user->isAdmin();
    }

    public function delete(User $user, InventoryItem $item): bool
    {
        return ($user->farm_id === $item->farm_id && $user->role === 'admin') || $user->isAdmin();
    }
}
