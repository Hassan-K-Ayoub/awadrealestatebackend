<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Property;

class Location extends Model
{
    protected $fillable = [
        'location',
        'count'
    ];

    public function properties()
    {
    return $this->belongsToMany(Property::class, 'property_location');
    }
}
