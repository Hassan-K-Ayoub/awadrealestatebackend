<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyLocation extends Model
{
    protected $table = 'property_location';
    protected $fillable = ['property_id', 'location_id'];

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }
    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }
}
