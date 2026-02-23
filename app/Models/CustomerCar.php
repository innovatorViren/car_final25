<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerCar extends Model
{
    use HasFactory;

     protected $table = 'customer_cars';

    protected $guarded = [];

    public function carBrand()
    {
        return $this->belongsTo(CarBrand::class);
    }
    public function carModel()
    {
        return $this->belongsTo(CarModel::class);
    }
}
