<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeleteLog extends MyModel
{
    use HasFactory;

    protected $table = 'delete_logs';
    protected $revisionCleanup = true;
    protected $historyLimit = 500;    
    protected $guarded = [];
}
