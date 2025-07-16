<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends MyModel
{
    use HasFactory;
    // use \Venturecraft\Revisionable\RevisionableTrait;

    protected $table = 'states';
    protected $revisionCleanup = true;
    protected $historyLimit = 500;

    protected $fillable = [
        'country_id', 'name', 'is_active', 'created_by', 'updated_by'
    ];

    protected $guarded = [];

    protected $dependency = array(
        'City' => array('field' => 'state_id', 'model' => City::class),
    );

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }
}
