<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerAddress extends MyModel
{
    use HasFactory;

    protected $table = 'customer_addresses';

    protected $guarded = [];

    protected $fillable = [
        'customer_id',
        'address_line1',
        'address_line2',
        'phone',
        'country_id',
        'state_id',
        'city_id',
        'pincode',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
