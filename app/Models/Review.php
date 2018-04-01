<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'reviews';
    //
    public $timestamps = false;
    //
    protected $fillable = [
        'string_id',
        'status',
        'api_verb',
        'api_body',
        'hike_string_id'
    ];
}
