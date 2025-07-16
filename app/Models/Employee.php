<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends MyModel
{
    use HasFactory;
    use \Venturecraft\Revisionable\RevisionableTrait;
    use YearWiseTrait;
    
    protected $table = 'employees';
    protected $revisionCleanup = true;
    protected $historyLimit = 500;
    protected $guarded = [];

    protected $dependency = [
        'EmployeeAddress' => ['field' => 'employee_id', 'model' => EmployeeAddress::class],
        'EmployeeDocument' => ['field' => 'employee_id', 'model' => EmployeeDocument::class],
    ];

    public function employeeAddress()
    {
        return $this->hasOne(EmployeeAddress::class)->with(['presentState','permanentCity']);
    }

    public function appointed()
    {
        return $this->belongsTo(Employee::class, 'appointed_by', 'id');
    }

    public function appointeds()
    {
        return $this->hasMany(Employee::class, 'appointed_by', 'id');
    }

    public function employeeDocument()
    {
        return $this->hasOne(EmployeeDocument::class);
    }
    public function DepartmentName()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
    public function designationName()
    {
        return $this->belongsTo(Designation::class, 'designation_id');
    }
    public function state()
    {

        return $this->belongsTo(State::class);
    }
    public function designations()
    {
        return $this->hasMany(Designation::class, 'id', 'designation_id');
    }
    public function departments()
    {
        return $this->hasMany(Department::class, 'id', 'department_id');
    }
    public function getBirthDateAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y');
    }
    
}
