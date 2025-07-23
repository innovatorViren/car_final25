<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 
class Customer extends MyModel
{
    use HasFactory;
    use \Venturecraft\Revisionable\RevisionableTrait;

    protected $table = 'customers';

    protected $revisionCleanup = true;

    protected $historyLimit = 500;

    protected $guarded = [];  
}
