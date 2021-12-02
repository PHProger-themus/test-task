<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scooter extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'num',
        'point_id',
        'booked_by',
        'booked_at',
    ];

    public function point()
    {
        return $this->belongsTo(Point::class);
    }

}
