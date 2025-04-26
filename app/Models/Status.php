<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $fillable = [
        'status'
    ];

    public function properties()
    {
        return $this->belongsToMany(Property::class, 'property_status');
    }
}
