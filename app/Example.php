<?php

namespace OpenCore\App;

use Illuminate\Database\Eloquent\Model;

class Example extends Model
{
    protected $table = 'awebcore_examples';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];
}
