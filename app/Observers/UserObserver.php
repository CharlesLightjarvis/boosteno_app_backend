<?php

namespace App\Observers;

use App\Models\User;
use Faker\Factory;


class UserObserver
{

    public function creating(User $user)
    {
        $faker = Factory::create();
        // Générer un UUID générique lors de la création
        if (empty($user->uuid)) {
            $user->uuid = $faker->uuid; // UUID générique sans préfixe
        }
    }
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        //
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
