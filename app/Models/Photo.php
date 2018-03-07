<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $table = 'photos';
    //
    public $timestamps = false;
    //
    protected $fillable = ['string_id','width', 'height', 'alt', 'attribution_link'];
    //
    public function hike_facts()
    {
        return $this->belongsTo('App\Models\Hike', 'photo_facts_id', 'id');
    }
    //
    public function hike_landscape()
    {
        return $this->belongsTo('App\Models\Hike', 'photo_landscape_id', 'id');
    }
    //
    public function hike_preview()
    {
        return $this->belongsTo('App\Models\Hike', 'photo_preview_id', 'id');
    }
    //
    public function hikes() {
        return $this->belongsToMany('App\Models\Hike', 'hikes_photos', 'hike_id', 'photo_id');
    }
    //
}
