<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerBankDetails extends MyModel
{
    use HasFactory;

    protected $table = 'customer_bank_details';

    protected $guarded = [];

    protected $fillable = [
        'customer_id',
        'account_no',
        'ifsc_code',
        'beneficiary_name',
        'bank_name',
        'branch_name',
        'bank_city',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
