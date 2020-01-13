<?php

namespace OpenCore\Support\Entities;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $primaryKey = 'setting_id';
    protected $table = 'setting';

    protected $fillable = ['code', 'key', 'value'];

    public $timestamps = false;
}
