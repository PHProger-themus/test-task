<?php

namespace App\Repositories;

use App\Models\Point;

class PointRepository
{

    public function getPoints(): \Illuminate\Support\Collection
    {
        return Point::orderBy('id')
            ->get();
    }

    public function storePoint(array $inputs) : void
    {
        $this->updatePoint(new Point(), $inputs);
    }

    public function updatePoint(Point $point, array $inputs) : void
    {
        $point->street = $inputs['street'];
        $point->save();
    }

    public function destroyPoint(Point $point) : void
    {
        $point->delete();
    }

}
