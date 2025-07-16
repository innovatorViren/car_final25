<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Year extends MyModel
{
    use HasFactory;
    protected $table = 'years';
    protected $revisionCleanup = true;
    protected $historyLimit = 500;
    protected $guarded = [];

    protected $fillable = [
        'yearname', "is_default", "is_displayed", "from_date", "to_date", "is_active", "created_by", "updated_by"
    ];
}
