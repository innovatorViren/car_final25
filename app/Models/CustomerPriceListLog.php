<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerPriceListLog extends MyModel
{
    use HasFactory;

    protected $table = 'customer_price_list_log';
    protected $revisionCleanup = true;
    protected $historyLimit = 500;
    protected $guarded = [];
}
