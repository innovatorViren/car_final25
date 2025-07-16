<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sentinel;
use Schema;
use Session;
use Auth;


class Setting extends MyModel
{
    use HasFactory;
    protected $guarded = [];



    public function updatedData()
    {
        if (Schema::hasColumn($this->getTable(), 'updated_by')) {
            return $this->belongsTo('App\Models\User', 'updated_by', 'id');
        }
    }
}
