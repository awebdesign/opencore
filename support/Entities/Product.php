<?php

namespace OpenCore\Support\Entities;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $primaryKey = 'product_id';
    protected $table = 'product';

    const CREATED_AT = 'date_added';
    const UPDATED_AT = 'date_modified';

    /**
     * The storage format of the model's date columns.
     *
     * @var string
     */
    protected $dateFormat = 'Y-m-d H:i:s';
}
