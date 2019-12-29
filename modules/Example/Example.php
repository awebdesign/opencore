<?php

namespace Modules\Example;

use Illuminate\Database\Eloquent\Model;

class Example extends Model
{
    protected $table = 'opencore_examples';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];
}
