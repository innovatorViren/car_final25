<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends MyModel
{
    use HasFactory;
    protected $table = 'banners';
    protected $revisionCleanup = true;
    protected $historyLimit = 500;
    protected $guarded = [];
}
