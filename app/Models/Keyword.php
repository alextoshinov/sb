<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keyword extends Model
{
    protected $table = 'keywords';
    //
    protected $fillable = ['keyword'];
    //
    public $timestamps = false;
    //
    // Relationships many to many
    public function hikes() {
        return $this->belongsToMany('App\Models\Hike', 'hikes_keywords');
    }
}
