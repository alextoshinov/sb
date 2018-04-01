<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Hike extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'hikes';
    //
    protected $fillable = ['string_id', 'name', 'locality', 'description', 'distance', 'elevation_max', 'elevation_gain', 'is_featured'];
    //
    public $timestamps = false;
    //
    protected $rules = [
        'string_id' => 'required|unique:hikes'
    ];
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'location_id','photo_facts_id', 'photo_landscape_id', 'photo_preview_id'
    ];
    
    //
    public function setNameAttribute($value) {
        $this->attributes['name'] = ucfirst($value);
        //
        if( ! $this->string_id)
        {
            $this->attributes['string_id'] = Str::slug($value);
        }
    }
    // Relationship One To Many
    public function photo_facts() {
        return $this->hasOne('App\Models\Photo', 'id', 'photo_facts_id');
    }
    //
    public function photo_landscape() {
        return $this->hasOne('App\Models\Photo', 'id', 'photo_landscape_id');
    }
    //
    public function photo_preview() {
        return $this->hasOne('App\Models\Photo', 'id', 'photo_preview_id');
    }
    //
    public function location() {
        return $this->hasOne('App\Models\Location','id', 'location_id');
    }
    //
    public function photos_generic() {
        return $this->belongsToMany('App\Models\Photo', 'hikes_photos', 'hike_id', 'photo_id');
    }
    // Relationships many to many
    public function keywords() {
        return $this->belongsToMany('App\Models\Keyword', 'hikes_keywords');
    }
    //
    public function maps() {
        return $this->belongsToMany('App\Models\Map', 'hikes_maps');
    }
    
    //
    
    //
}
