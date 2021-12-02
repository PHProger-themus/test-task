<?php

namespace App\Repositories;

use App\Models\Scooter;

class ScooterRepository
{

    public function getScooters(): \Illuminate\Support\Collection
    {
        return Scooter::orderBy('id')
            ->get();
    }

    public function storeScooter(array $inputs) : void
    {
        $this->updateScooter(new Scooter(), $inputs);
    }

    public function updateScooter(Scooter $scooter, array $inputs) : void
    {
        $scooter->num = $inputs['num'];
        $scooter->point_id = $inputs['point'];
        $scooter->save();
    }

    public function destroyScooter(Scooter $scooter) : void
    {
        $scooter->delete();
    }

}
