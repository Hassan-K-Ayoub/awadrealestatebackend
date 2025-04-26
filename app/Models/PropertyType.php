<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyType extends Model
{
    protected $table = 'property_type';
    protected $fillable = ['property_id', 'type_id'];

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }
    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id');
    }
}
