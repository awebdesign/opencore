<?php

namespace OpenCore\Support\Entities;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $primaryKey = 'order_id';
    protected $table = 'order';

    const CREATED_AT = 'date_added';
    const UPDATED_AT = 'date_modified';

    /**
     * The storage format of the model's date columns.
     *
     * @var string
     */
    protected $dateFormat = 'Y-m-d H:i:s';
}
