<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Location;
use App\Models\Status;
use App\Models\Type;

class Property extends Model
{
    protected $fillable=[
        'title',
        'images',
        'description',
        'price',
        'space',
        'bedrooms',
        'bathrooms',
        'salons',
        'kitchens',
        'terraces',
        'terraces_count',
        'floors',
        'living_rooms',
        'swimming_pools',
        'swimming_pools_count',
        'parking',
        'parking_count',
        'garden',
        'garden_count',
        'condition'

    ];

    // Property.php model
    public function location()
    {
        return $this->hasOne(\App\Models\PropertyLocation::class);
    }

    public function type()
    {
        return $this->hasOne(\App\Models\PropertyType::class);
    }

    public function status()
    {
        return $this->hasOne(\App\Models\PropertyStatus::class);
    }


    public function getHasTerrascesAttribute(){
        return $this->terraces ? $this->terraces_count : false;
    }

    public function getHasGardenAttribute(){
        return $this->garden ? $this->garden_count : false;
    }

    public function getHasSwimmingPoolsAttribute(){
        return $this->swimming_pools ? $this->swimming_pools_count : false;
    }

    public function getHasParkingAttribute(){
        return $this->parking ? $this->parking_count : false;
    }
}
