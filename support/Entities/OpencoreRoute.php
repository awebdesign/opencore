<?php

namespace OpenCore\Support\Entities;

use Illuminate\Database\Eloquent\Model;

class OpencoreRoute extends Model
{
    /**
     * The storage format of the model's date columns.
     *
     * @var string
     */
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $guarded = ['id'];

    public function getUniqueKeyAttribute()
    {
        return $this->method . $this->uri . $this->name;
    }
}
