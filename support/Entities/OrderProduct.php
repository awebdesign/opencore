<?php

namespace OpenCore\Support\Entities;

use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    protected $primaryKey = 'order_product_id';
    protected $table = 'order_product';

    public $timestamps = false;
}
