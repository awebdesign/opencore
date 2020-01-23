<?php

namespace OpenCore\Support\Entities;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $primaryKey = 'cart_id';
    protected $table = 'cart';

    const CREATED_AT = 'date_added';
    const UPDATED_AT = null;

    /**
     * The storage format of the model's date columns.
     *
     * @var string
     */
    protected $dateFormat = 'Y-m-d H:i:s';
}
