<?php

namespace AwebCore\App;

use AwebCore\App\User;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'awebcore_tasks';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * Get the user that owns the task.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
