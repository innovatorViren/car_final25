<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wash extends MyModel
{
    use HasFactory;
    protected $table = 'washes';
    protected $revisionCleanup = true;
    protected $historyLimit = 500;
    protected $guarded = [];
}
