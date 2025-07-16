<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Sentinel;

class Country extends MyModel
{

    // use \Venturecraft\Revisionable\RevisionableTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */

    protected $table = 'countries';
    protected $revisionCleanup = true;
    protected $historyLimit = 500;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */

    protected $fillable = [
        'name', 'code', 'phone_code',
        'is_active', 'ip', 'update_from_ip', 'created_by','updated_by'
    ];

    protected $dependency = array(
        'State' => array('field' => 'country_id', 'model' => State::class),
    );

    public function states()
    {
        return $this->hasMany(State::class, 'country_id');
    }

    protected static function boot()
    {
        parent::boot();
    }
}
