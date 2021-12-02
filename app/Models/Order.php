<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'point_id',
        'scooter_id',
        'user_id',
        'manager_id',
        'price',
        'status',
        'collateral',
        'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scooter()
    {
        return $this->belongsTo(Scooter::class);
    }

    public function point()
    {
        return $this->belongsTo(Point::class);
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
}
