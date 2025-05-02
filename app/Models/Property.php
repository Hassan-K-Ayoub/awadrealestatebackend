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
        'condition',
        'featured'
    ];

    // Property.php model
    public function location()
{
    return $this->hasOneThrough(
        \App\Models\Location::class,
        \App\Models\PropertyLocation::class,
        'property_id',  // Foreign key on the PropertyLocation table...
        'id',           // Foreign key on the Location table...
        'id',           // Local key on the Property table...
        'location_id'   // Local key on the PropertyLocation table...
    );
}


    public function type()
    {
        return $this->hasOneThrough(
            \App\Models\Type::class,
            \App\Models\PropertyType::class,
            'property_id',  // Foreign key on the PropertyLocation table...
            'id',           // Foreign key on the Location table...
            'id',           // Local key on the Property table...
            'type_id'   // Local key on the PropertyLocation table...
        );
    }

    public function status()
    {
        return $this->hasOneThrough(
            \App\Models\Status::class,
            \App\Models\PropertyStatus::class,
            'property_id',  // Foreign key on the PropertyLocation table...
            'id',           // Foreign key on the Location table...
            'id',           // Local key on the Property table...
            'status_id'   // Local key on the PropertyLocation table...
        );
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
