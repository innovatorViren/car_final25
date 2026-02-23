<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 
class Customer extends MyModel
{
    use HasFactory;
    use \Venturecraft\Revisionable\RevisionableTrait;

    protected $table = 'customers';

    protected $revisionCleanup = true;

    protected $historyLimit = 500;

    protected $guarded = [];  

    public function customerAddress()
    {
        return $this->hasMany(CustomerAddress::class)->with('city', 'state', 'country');
    }

    public function customerCar()
    {
        return $this->hasMany(CustomerCar::class)->with('carBrand', 'carModel');
    }
}
