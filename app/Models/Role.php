<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;
use Spatie\Permission\Contracts\Role as RoleContract;

class Role extends SpatieRole implements RoleContract
{
    // Accessor pour rendre la première lettre du nom du rôle en majuscule
    public function getNameAttribute($value)
    {
        return ucfirst($value);
    }
}
