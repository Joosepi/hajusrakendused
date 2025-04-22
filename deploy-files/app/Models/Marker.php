<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Marker extends Model
{
    public $timestamps = false;  // We're using custom timestamp fields
    
    protected $fillable = [
        'name',
        'description',
        'latitude',
        'longitude'
    ];

    protected $dates = [
        'added',
        'edited'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 