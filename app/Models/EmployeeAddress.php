<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeAddress extends MyModel
{
    use HasFactory;
    protected $table = 'employee_addresses';
    protected $revisionCleanup = true;
    protected $historyLimit = 500;
    protected $guarded = [];

    public function presentState()
    {
        return $this->belongsTo(State::class, 'present_state_id', 'id');
    }

    public function presentCity()
    {
        return $this->belongsTo(City::class, 'present_city', 'id');
    }

    public function permanentState()
    {
        return $this->belongsTo(State::class, 'permanent_state_id', 'id');
    }

    public function permanentCity()
    {
        return $this->belongsTo(City::class, 'permanent_city', 'id');
    }
}
