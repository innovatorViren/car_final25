<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use SoftDeletes;

    protected $append = ['full_name'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'users';
    protected $revisionCleanup = true;
    protected $historyLimit = 500;

    protected $fillable = [
        'emp_type',
        'emp_id',
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'password',
        'mobile',
        'roles_id',
        'image',
        'image_path',
        'allow_multi_login',
        'is_active',
        'is_ip_base',
        'permissions',
        'ip',
        'update_from_ip',
        'created_at',
        'updated_at',
        'updated_by',
        'created_by',
        'customer_id',
        'allow_access_from_other_network'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getFullNameAttribute()
    {
        if (!empty($this->first_name) || !empty($this->last_name)) {
            $full_name = $this->first_name . " " . $this->last_name;
        } else {
            $full_name = '';
        }
        return $full_name;
    }

    public function getFullName()
    {
        return $this->hasOne(User::class, 'id');
    }

    public function usersRole()
    {
        return $this->belongsTo(RoleUser::class, 'id', 'user_id');
    }

    public function roles()
    {
        return $this->hasOne(Role::class, 'roles_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'emp_id', 'id');
    }

    public function empUser()
    {
        return $this->hasOne(Employee::class, 'id', 'emp_id');
    }

    public function userIps()
    {
        return $this->hasMany(UserIp::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function role()
    {
        return $this->hasOneThrough(Role::class, RoleUser::class, 'user_id', 'id', null, 'role_id');
    }
    public function rolesData()
    {
        return $this->belongsTo(Role::class, 'roles_id');
    }
}
