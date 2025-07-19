<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarModel extends MyModel
{
    use HasFactory;
    protected $table = 'car_models';
    protected $revisionCleanup = true;
    protected $historyLimit = 500;
    protected $guarded = [];

    
}
