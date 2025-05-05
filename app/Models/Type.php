<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    protected $fillable = [
        'type',
        'count'
    ];

    public function properties()
    {
        return $this->belongsToMany(Property::class, 'property_type');
    }
}
