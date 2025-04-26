<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = [
        'location'
    ];

    public function properties()
    {
    return $this->belongsToMany(Property::class, 'property_location');
    }
}
