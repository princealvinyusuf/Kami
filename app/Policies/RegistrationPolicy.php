<?php

namespace App\Policies;

use App\Models\Registration;
use App\Models\User;

class RegistrationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->is_admin;
    }

    public function view(User $user, Registration $registration): bool
    {
        return $user->is_admin;
    }

    public function update(User $user, Registration $registration): bool
    {
        return $user->is_admin;
    }
}
