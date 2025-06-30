<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParkingSlot extends Model
{
    protected $fillable = [
        'slot_code',
        'status',
        'last_detection',
    ];

    protected $casts = [
        'last_detection' => 'datetime',
    ];

    public function slotEvents()
    {
        return $this->hasMany(SlotEvent::class);
    }
}
