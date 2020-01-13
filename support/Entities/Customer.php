<?php

namespace OpenCore\Support\Entities;

use Illuminate\Database\Eloquent\Model;
use OpenCore\Support\Entities\CustomerTransaction;

class Customer extends Model
{
    protected $primaryKey = 'customer_id';
    protected $table = 'customer';

    const CREATED_AT = 'date_added';
    const UPDATED_AT = null;

    /**
     * The storage format of the model's date columns.
     *
     * @var string
     */
    protected $dateFormat = 'Y-m-d H:i:s';

    public function transactions()
    {
        return $this->hasMany(CustomerTransaction::class);
    }
}
