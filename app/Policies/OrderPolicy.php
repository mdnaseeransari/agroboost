<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Order $order)
    {
        return $user->id === $order->buyer_id || $user->id === $order->farmer_id || $user->isAdmin();
    }

    public function update(User $user, Order $order)
    {
        return $user->id === $order->farmer_id || $user->isAdmin();
    }
}
