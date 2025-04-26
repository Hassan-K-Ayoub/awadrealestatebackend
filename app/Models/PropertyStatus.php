<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyStatus extends Model
{
    protected $table = 'property_status';
    protected $fillable = ['property_id', 'status_id'];

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }
}
