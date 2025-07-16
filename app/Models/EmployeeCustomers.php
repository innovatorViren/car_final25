<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeCustomers extends MyModel
{
    use HasFactory;
    protected $table = 'employee_customers';
    protected $revisionCleanup = true;
    protected $historyLimit = 500;
    protected $guarded = [];
}
