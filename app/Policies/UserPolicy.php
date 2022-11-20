<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     *
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function teacher(User $user)     // Sprawdza czy jest nauczycielem lub adminem (Do edycji eventów)
    {
        return $user->role->name === 'teacher' || $user->role->name === 'admin';
    }


    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function publicProfile(?User $user, User $model)
    {
        return $model->role->name === 'teacher';
    }
    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function studentProfile(User $user, User $model)
    {
        return $model->role->name === 'student';
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function owner(User $user, User $model)  // Zmienić na owner
    {
        return $user->name === $model->name;
    }

}
