<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Distributor extends MyModel
{
    use HasFactory;
    use \Venturecraft\Revisionable\RevisionableTrait;

    protected $revisionCleanup = true;
    protected $historyLimit = 500;
    protected $guarded = [];
}
