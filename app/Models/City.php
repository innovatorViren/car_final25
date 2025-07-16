<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\ActiveScope;

class City extends MyModel
{
    use HasFactory;
    protected $table = 'cities';
    protected $revisionCleanup = true;
    protected $historyLimit = 500;
    protected $fillable = [
        'country_id', 'state_id', 'name', 'is_active', 'created_by', 'updated_by'
    ];
    protected $guarded = [];
    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        //static::addGlobalScope(new ActiveScope);
    }
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
    public function state()
    {
        return $this->belongsTo(State::class);
    }
}
