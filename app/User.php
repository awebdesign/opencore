<?php

namespace AwebCore\App\User;

use Illuminate\Foundation\Auth\User as Authenticatable;
use AwebCore\App\Task;

class User extends Authenticatable
{
    // Other Eloquent Properties...

    /**
     * Get all of the tasks for the user.
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
