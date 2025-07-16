<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Cartalyst\Sentinel\Roles\EloquentRole;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Role extends EloquentRole
{
    use HasFactory;

    protected $table = 'roles';
    protected $revisionCleanup = true;
    protected $historyLimit = 500;

    protected $guarded = [];

    use SoftDeletes;
}
