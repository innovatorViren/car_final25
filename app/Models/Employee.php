<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends MyModel
{
    use HasFactory;
    use \Venturecraft\Revisionable\RevisionableTrait;
    
    protected $table = 'employees';
    protected $revisionCleanup = true;
    protected $historyLimit = 500;
    protected $guarded = [];
    public function employeeAddress()
    {
        return $this->hasOne(EmployeeAddress::class)->with(['presentState','permanentCity']);
    }

    public function employeeDocument()
    {
        return $this->hasOne(EmployeeDocument::class);
    }
    
}
