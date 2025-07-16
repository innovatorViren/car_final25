<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sentinel;
use Schema;
use Session;
use Auth;

class MyModel extends Model
{

    use SoftDeletes;

    protected $table;
    protected $dependency;

    public function deleteValidate($id)
    {
        $msg = array();
        if (!empty($this->dependency) && !empty($id)) {
            foreach ($this->dependency as $k => $row) {
                $row = (object) $row;
                $model = app()->make($row->model);
                if ($model->where($row->field, $id)->count()) {
                    $msg[] = $k;
                }
            }
            if (!empty($msg)) {
                $msg = implode(", ", $msg);
            }
        }
        return $msg;
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        $loginUser = Sentinel::getUser();
        $user_id = $loginUser ? $loginUser->id : 0;
        if(empty($loginUser) && Auth::check()) {
            $user_id = Auth::user()->id;
        }
        static::creating(function ($model) use ($user_id) {
            if (Schema::hasColumn($model->getTable(), 'created_by')) {
                $model->created_by = $user_id;
            }
            if (Schema::hasColumn($model->getTable(), 'ip')) {
                $model->ip = request()->ip();
            }
            if (Schema::hasColumn($model->getTable(), 'year_id')) {
                if (Session::has('default_year') && empty($model->year_id)) {
                    $default_year = Session::get('default_year');
                    $model->year_id = $default_year->id;
                }
            }
        });

        static::updating(function ($model) use ($user_id) {
            if (Schema::hasColumn($model->getTable(), 'updated_by')) {
                if($model->id && count($model->getDirty())) {
                    $model->updated_by = $user_id;
                    $model->update_from_ip = request()->ip();
                } else if($model->id) {
                    $model->timestamps = false;
                }
            }
            if (Schema::hasColumn($model->getTable(), 'year_id')) {
                if (Session::has('default_year') && empty($model->year_id)) {
                    $default_year = Session::get('default_year');
                    $model->year_id = $default_year->id;
                }
            }
        });
    }

    public function  createdBy()
    {
        if (Schema::hasColumn($this->getTable(), 'created_by')) {
            return $this->belongsTo('App\Models\User', 'created_by', 'id');
        }
    }
}
