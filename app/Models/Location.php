<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $table = 'locations';
    //
    protected $fillable = ['latitude','longitude'];
    public $timestamps = false;
    //
    public function hike_location()
    {
        return $this->belongsTo('App\Models\Hike', 'location_id', 'id');
    }
    
}
